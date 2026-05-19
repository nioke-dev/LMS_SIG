<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\TrainingBlueprint;
use App\Models\TnaSubmission;

class BlueprintExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    private $search;
    private $category;
    private $smeId;

    public function __construct($search, $category, $smeId = null)
    {
        $this->search = $search;
        $this->category = $category;
        $this->smeId = $smeId;
    }

    public function array(): array
    {
        $query = TrainingBlueprint::with('sme');

        if ($this->smeId) {
            $query->where('sme_id', $this->smeId);
        }

        if ($this->category && $this->category !== 'all') {
            $query->where('category', $this->category);
        }

        if ($this->search) {
            $q = strtolower($this->search);
            $query->where(function($w) use ($q) {
                $w->where('title', 'like', "%$q%")
                  ->orWhere('id', 'like', "%$q%");
            });
        }

        $blueprints = $query->orderBy('created_at', 'desc')->get();

        if ($blueprints->isEmpty() && $this->smeId) {
            // Fallback demo jika kosong untuk user tersebut tapi tetap terapkan filter pencarian/kategori
            $demoQuery = TrainingBlueprint::with('sme');
            if ($this->category && $this->category !== 'all') {
                $demoQuery->where('category', $this->category);
            }
            if ($this->search) {
                $q = strtolower($this->search);
                $demoQuery->where(function($w) use ($q) {
                    $w->where('title', 'like', "%$q%")
                      ->orWhere('id', 'like', "%$q%");
                });
            }
            $blueprints = $demoQuery->orderBy('created_at', 'desc')->get();
        }

        return $blueprints->map(function($bp) {
            // Komposisi Penggabungan TNA
            $tnaIds = is_array($bp->tna_submission_ids) ? $bp->tna_submission_ids : (json_decode($bp->tna_submission_ids, true) ?? []);
            $mergedSubmissions = TnaSubmission::whereIn('id', $tnaIds)->get();
            $komposisiTna = $mergedSubmissions->map(function($sub) {
                return "- " . $sub->title . " (ID: " . $sub->id . " | Urgensi: " . $sub->urgency . ")";
            })->implode("\n");

            if (empty($komposisiTna)) {
                $komposisiTna = "Data child category / usulan TNA spesifik tidak ditemukan atau menggunakan entri langsung.";
            }

            $cleanHtml = function($html, $default) {
                if (empty($html)) return $default;
                $text = str_replace(['<br>', '<br/>', '</p>', '</li>'], ["\n", "\n", "\n\n", "\n"], $html);
                $text = str_replace('<li>', '- ', $text);
                return trim(strip_tags($text));
            };

            return [
                $bp->id,
                $bp->title,
                $bp->category,
                strtoupper(str_replace('_', ' ', $bp->status)),
                $bp->deadline ? \Carbon\Carbon::parse($bp->deadline)->translatedFormat('d F Y') : 'Belum Ditentukan',
                $bp->sme ? $bp->sme->name : 'SME Terkait',
                $cleanHtml($bp->rationalization, 'Rasionalisasi belum ditentukan.'),
                $komposisiTna,
                $cleanHtml($bp->objective, 'Course objective belum ditentukan.'),
                $cleanHtml($bp->content, 'Course content belum ditentukan.'),
                $bp->sme_instructions ?? 'Tidak ada instruksi khusus dari Admin Coordinator.',
                $bp->need_workshop ? 'Memerlukan Workshop' : 'Tanpa Workshop',
                $bp->workshop_note ?? 'Tidak ada catatan spesifik mengenai kebutuhan workshop.',
                $bp->distribution === 'public' ? 'Public Ready (Eksternal & Internal)' : 'Internal Only (SIG Group)',
                $bp->distribution_note ?? 'Tidak ada catatan spesifik distribusi.',
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            'ID Blueprint',
            'Judul Pelatihan',
            'Kategori Induk',
            'Status',
            'Tenggat Waktu (Deadline)',
            'Ditugaskan Kepada',
            'Latar Belakang / Rasionalisasi',
            'Komposisi Penggabungan TNA',
            'Course Objective',
            'Course Content',
            'Instruksi Khusus',
            'Kebutuhan Workshop',
            'Catatan Kebutuhan Workshop',
            'Target Distribusi',
            'Catatan Distribusi',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        // Style Header
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FF2563EB'], // Primary blue
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        // Style Content Rows
        if ($highestRow > 1) {
            $sheet->getStyle('A2:' . $highestColumn . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FFCCCCCC'],
                    ],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                    'wrapText' => true, // Wrap text for long paragraphs and bullet points
                ],
            ]);
            
            // Center align IDs, Status, Dates, Workshop Status, Distribution Status
            $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C2:E' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L2:L' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N2:N' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // Set specific column widths for long content columns so they look beautiful
        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(40);
        $sheet->getColumnDimension('I')->setWidth(40);
        $sheet->getColumnDimension('J')->setWidth(40);
        $sheet->getColumnDimension('K')->setWidth(30);
        $sheet->getColumnDimension('L')->setWidth(25);
        $sheet->getColumnDimension('M')->setWidth(35);
        $sheet->getColumnDimension('N')->setWidth(30);
        $sheet->getColumnDimension('O')->setWidth(35);

        // Add AutoFilter to the header row
        $sheet->setAutoFilter('A1:' . $highestColumn . '1');

        return [];
    }
}
