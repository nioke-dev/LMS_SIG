<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TnaExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    private $status;
    private $search;
    private $category;
    private $dateFrom;
    private $dateTo;

    public function __construct($status, $search, $category, $dateFrom, $dateTo)
    {
        $this->status = $status;
        $this->search = $search;
        $this->category = $category;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function array(): array
    {
        $query = \App\Models\TnaSubmission::query();

        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
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

        if ($this->dateFrom) {
            $query->where('submission_date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->where('submission_date', '<=', $this->dateTo);
        }

        $data = $query->get();
        
        // Map data for export
        return $data->map(function($item) {
            return [
                $item->id,
                $item->title,
                $item->submission_date->format('d M Y'),
                $item->category,
                $item->urgency,
                ucfirst($item->status),
                $item->participants,
                $item->description,
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            'ID Usulan',
            'Judul Pelatihan',
            'Tanggal Pengajuan',
            'Kategori',
            'Urgensi',
            'Status',
            'Jumlah Peserta',
            'Deskripsi',
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
                'color' => ['argb' => 'FF2563EB'],
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
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);
            
            // Center align IDs and Dates
            $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C2:E' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F2:G' . $highestRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // Add AutoFilter to the header row
        $sheet->setAutoFilter('A1:' . $highestColumn . '1');

        return [];
    }
}
