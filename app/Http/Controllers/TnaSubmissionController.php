<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TnaSubmission;
use App\Services\TnaService;
use App\Services\OrganizationService;
use App\DTOs\TnaSubmissionDTO;
use App\Exceptions\TnaSubmissionException;
use Exception;

class TnaSubmissionController extends Controller
{
    protected $tnaService;
    protected $orgService;

    public function __construct(TnaService $tnaService, OrganizationService $orgService)
    {
        $this->tnaService = $tnaService;
        $this->orgService = $orgService;
    }

    /**
     * Display the Learning Coordinator dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total' => TnaSubmission::count(),
            'review' => TnaSubmission::where('status', 'review')->count(),
            'approved' => TnaSubmission::where('status', 'approved')->count(),
            'draft' => TnaSubmission::where('status', 'draft')->count(),
        ];

        $recentSubmissions = TnaSubmission::orderBy('submission_date', 'desc')
            ->take(5)
            ->get()
            ->map(fn($s) => TnaSubmissionDTO::fromModel($s));

        return view('pages.lc.index', [
            'stats' => $stats,
            'recentSubmissions' => $recentSubmissions
        ]);
    }

    /**
     * Display a listing of the TNA submissions.
     * All filtering is handled client-side via Alpine.js for SPA-like experience.
     */
    public function index()
    {
        $submissions = TnaSubmission::orderBy('submission_date', 'desc')->get()
            ->map(fn($s) => TnaSubmissionDTO::fromModel($s)->toArray())
            ->values();

        $categories = TnaSubmission::distinct()->pluck('category')->values();

        return view('pages.lc.daftar-usulan', [
            'submissions' => $submissions,
            'categories' => $categories
        ]);
    }

    public function show($id)
    {
        $submission = TnaSubmission::findOrFail($id);
        $dto = TnaSubmissionDTO::fromModel($submission);

        return view('pages.lc.detail-usulan', [
            'submission' => $dto
        ]);
    }

    /**
     * Show the form for creating a new submission.
     */
    public function create()
    {
        $categories = $this->tnaService->getAllCategories();
        $participants = $this->orgService->getParticipantsByLCScope(auth()->user());

        return view('pages.lc.buat-usulan', [
            'categories' => $categories,
            'participants' => $participants
        ]);
    }

    /**
     * Store a newly created submission in storage.
     */
    public function store(Request $request)
    {
        $status = $request->input('status', 'review');

        // Validation rules depend on status
        $rules = [
            'title' => 'required|string|max:255',
            'status' => 'required|in:draft,review',
        ];

        if ($status === 'review') {
            $rules = array_merge($rules, [
                'category' => 'required|string',
                'urgency' => 'required|string',
                'description' => 'required|string',
                'participants_count' => 'required|integer|min:1',
                'participants_list' => 'required|array|min:1',
                'documents' => 'nullable|array',
            ]);
        } else {
            // Optional fields for draft
            $rules = array_merge($rules, [
                'category' => 'nullable|string',
                'urgency' => 'nullable|string',
                'description' => 'nullable|string',
                'participants_count' => 'nullable|integer',
                'participants_list' => 'nullable|array',
                'documents' => 'nullable|array',
            ]);
        }

        $validated = $request->validate($rules);

        try {
            $submissionDto = $this->tnaService->storeSubmission($validated, $request->allFiles());

            return response()->json([
                'success' => true,
                'message' => $validated['status'] === 'draft' ? 'Usulan berhasil disimpan sebagai draft.' : 'Usulan berhasil dikirim.',
                'redirect' => route('learning-coordinator.daftar-usulan'),
                'data' => $submissionDto->toArray()
            ]);
        } catch (TnaSubmissionException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified submission.
     */
    public function edit($id)
    {
        $submission = TnaSubmission::findOrFail($id);
        
        if (!in_array($submission->status, ['draft', 'review'])) {
            return redirect()->route('learning-coordinator.daftar-usulan')
                ->with('error', 'Hanya usulan dengan status Draft atau Review yang dapat diedit.');
        }

        $categories = $this->tnaService->getAllCategories();
        $participants = $this->orgService->getParticipantsByLCScope(auth()->user());
        
        // Transform model to DTO for consistent frontend consumption
        $dto = TnaSubmissionDTO::fromModel($submission);

        return view('pages.lc.buat-usulan', [
            'mode' => 'edit',
            'submission' => $dto,
            'categories' => $categories,
            'participants' => $participants
        ]);
    }

    /**
     * Update an existing TNA submission.
     */
    public function update(Request $request, $id)
    {
        $status = $request->input('status', 'review');

        // Validation rules depend on status
        $rules = [
            'title' => 'required|string|max:255',
            'status' => 'required|in:draft,review',
        ];

        if ($status === 'review') {
            $rules = array_merge($rules, [
                'category' => 'required|string',
                'urgency' => 'required|string',
                'description' => 'required|string',
                'participants_count' => 'required|integer|min:1',
                'participants_list' => 'required|array|min:1',
                'documents' => 'nullable|array',
            ]);
        } else {
            $rules = array_merge($rules, [
                'category' => 'nullable|string',
                'urgency' => 'nullable|string',
                'description' => 'nullable|string',
                'participants_count' => 'nullable|integer',
                'participants_list' => 'nullable|array',
                'documents' => 'nullable|array',
            ]);
        }

        $validated = $request->validate($rules);

        try {
            $submissionDto = $this->tnaService->updateSubmission($id, $validated);

            return response()->json([
                'success' => true,
                'message' => $validated['status'] === 'draft' ? 'Draft berhasil diperbarui.' : 'Usulan berhasil dikirim.',
                'redirect' => route('learning-coordinator.daftar-usulan'),
                'data' => $submissionDto->toArray()
            ]);
        } catch (TnaSubmissionException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function mergingHub()
    {
        // Fetch real submissions with all necessary relations for filtering
        // Exclude 'draft' status for Admin Coordinator
        $realSubmissions = \App\Models\TnaSubmission::with(['user.organization.level.company'])
            ->whereIn('status', ['submitted', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch all unique participant IDs to get their details in one query
        $allParticipantIds = $realSubmissions->pluck('participants_list')->flatten()->filter()->unique()->toArray();
        $participantDetails = \App\Models\User::whereIn('id', $allParticipantIds)
            ->with(['organization'])
            ->get()
            ->keyBy('id');

        // Fetch all categories with their parents for quick lookup
        $categoryModels = \App\Models\TnaCategory::with('parent')->get()->keyBy('name');

        // Map database records to the structure expected by the frontend
        $submissions = $realSubmissions->map(function ($s) use ($participantDetails, $categoryModels) {
            $user = $s->user;
            $org = $user ? $user->organization : null;
            $company = ($org && $org->level) ? $org->level->company : null;
            
            // Get detailed organization path for the proposer card dynamically
            $orgPath = [];
            
            if ($org) {
                $current = $org;
                while ($current) {
                    // Skip the 'Company' level since it's already displayed separately
                    if ($current->level && strtolower($current->level->name) !== 'company') {
                        array_unshift($orgPath, [
                            'level' => $current->level->name,
                            'name' => $current->name
                        ]);
                    }
                    $current = $current->parent;
                }
            }

            // Map detailed participants info
            $participantsList = collect($s->participants_list ?? [])->map(function($id) use ($participantDetails) {
                $p = $participantDetails->get($id);
                return [
                    'nik' => $p ? $p->nik : 'N/A',
                    'name' => $p ? $p->name : 'N/A',
                    'position' => $p ? $p->position : 'N/A',
                    'organization' => ($p && $p->organization) ? $p->organization->name : 'N/A'
                ];
            })->toArray();

            // Resolve parent category
            $tnaCat = $categoryModels->get($s->category);
            $parentCategory = ($tnaCat && $tnaCat->parent) ? $tnaCat->parent->name : 'General / Independent';

            return [
                'id' => $s->id,
                'title' => $s->title,
                'category' => $s->category,
                'parent_category' => $parentCategory,
                'proposer_name' => $user ? $user->name : 'N/A',
                'company_name' => $company ? $company->name : 'N/A',
                'org_path' => $orgPath,
                'company_id' => $company ? $company->id : null,
                'organization_id' => $org ? $org->id : null,
                'participants' => $s->participants,
                'urgency' => $s->urgency,
                'status' => $s->status,
                'date' => $s->submission_date ? $s->submission_date->format('d M Y') : 'N/A',
                'description' => $s->description,
                'documents' => $s->documents ?? [],
                'participants_list' => $participantsList
            ];
        })->toArray();
        
        $categories = array_values(array_unique(array_map(fn($s) => $s['category'], $submissions)));
        $companies = \App\Models\Company::all();
        $orgLevels = \App\Models\OrgLevel::all();
        $organizations = \App\Models\Organization::all();
        
        // Get unique proposers from actual submissions
        $proposers = array_values(array_unique(array_map(fn($s) => $s['proposer_name'], $submissions)));

        return view('pages.admin-coordinator.merging-hub', [
            'submissions' => $submissions,
            'categories' => $categories,
            'companies' => $companies,
            'orgLevels' => $orgLevels,
            'organizations' => $organizations,
            'proposers' => $proposers
        ]);
    }

    private function getMockBlueprints()
    {
        return [
            [
                'id' => 'CUR-2024-001',
                'title' => 'Vibration Analysis Masterclass',
                'category' => 'Maintenance Management',
                'category_description' => 'Fokus pada strategi, teknik, dan manajemen pemeliharaan aset untuk meminimalkan downtime dan memaksimalkan reliabilitas peralatan pabrik.',
                'sme' => [
                    'name' => 'Dr. Ir. Budi Santoso',
                    'avatar' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=random',
                    'position' => 'Senior Mechanical Expert',
                    'active_classes' => 2,
                    'teaching_history' => ['Basic Alignment 2023', 'Rotating Equipment Fundamentals', 'Preventive Maintenance Strategy']
                ],
                'deadline' => '2024-12-15',
                'deadline_formatted' => '15 Des 2024',
                'status' => 'IN PROGRESS (DRAFTING)',
                'status_type' => 'drafting', // gold
                'description' => 'Pelatihan tingkat lanjut mengenai teknik analisis vibrasi untuk deteksi dini kerusakan mesin rotasi pada fasilitas pabrik semen skala besar.',
                'merged_tna_count' => 12,
                'merged_tna_categories' => [
                    ['name' => 'Maintenance Management', 'description' => 'Strategi dan implementasi pengelolaan aset fisik pabrik untuk meminimalisir downtime.'],
                    ['name' => 'General Engineering', 'description' => 'Prinsip rekayasa dasar yang mendukung proses operasional dan pemeliharaan alat.'],
                    ['name' => 'Industrial Safety', 'description' => 'Penerapan standar keselamatan kerja untuk mencegah insiden pada mesin berisiko tinggi.']
                ],
                'target_audience' => ['Maintenance Supervisor', 'Reliability Engineer', 'Condition Monitoring Technician'],
                'course_objective' => '<p><strong>Tujuan Pelatihan:</strong></p><ul><li>Mampu melakukan akuisisi data vibrasi dengan presisi.</li><li>Menganalisis spektrum vibrasi untuk diagnosis unbalance, misalignment, dan bearing defect.</li><li>Menyusun rekomendasi perbaikan berbasis data vibrasi.</li></ul>',
                'course_content' => '<p><strong>Materi Pembelajaran:</strong></p><ol><li>Dasar-dasar Vibrasi Mesin Rotasi.</li><li>Pengenalan Alat Ukur Vibrasi.</li><li>Analisis Spektrum Lanjut.</li><li>Studi Kasus Kerusakan Bearing.</li></ol>',
                'sme_instructions' => 'Mohon fokuskan studi kasus kerusakan bearing khusus pada mesin Rotary Kiln pabrik Tuban 4.',
                'distribution' => 'internal',
                'distribution_note' => 'Hanya ditujukan untuk karyawan departemen pemeliharaan di lingkungan operasional Tuban.',
                'need_workshop' => true,
                'workshop_note' => 'Diperlukan akses langsung ke analyzer vibrasi dan simulasi mesin rotasi di area workshop.',
                'documents' => [
                    ['name' => 'vibration_iso_standards.pdf', 'size' => 2450000],
                    ['name' => 'kiln_tuban_4_history.xlsx', 'size' => 1120000]
                ],
                'participants' => [
                    ['name' => 'Ahmad Riyadi', 'nik' => 'SIG-8821', 'position' => 'Maintenance Spv', 'department' => 'Dept. Tuban 1'],
                    ['name' => 'Bagus Pratama', 'nik' => 'SIG-8932', 'position' => 'Reliability Eng', 'department' => 'Dept. Engineering'],
                    ['name' => 'Candra Wijaya', 'nik' => 'SIG-9011', 'position' => 'CM Technician', 'department' => 'Dept. Tuban 2'],
                    ['name' => 'Dedi Setiawan', 'nik' => 'SIG-9102', 'position' => 'Maintenance Spv', 'department' => 'Dept. Indarung 5']
                ]
            ],
            [
                'id' => 'CUR-2024-042',
                'title' => 'Supply Chain Optimization Strategy',
                'category' => 'Supply Chain Management',
                'category_description' => 'Mencakup seluruh siklus manajemen logistik, pengadaan, dan distribusi produk semen dari hulu ke hilir secara terintegrasi.',
                'sme' => [
                    'name' => 'Rendra Wijaya',
                    'avatar' => 'https://ui-avatars.com/api/?name=Rendra+Wijaya&background=random',
                    'position' => 'VP Supply Chain Management',
                    'active_classes' => 1,
                    'teaching_history' => ['Logistics for Non-Logistics', 'Warehouse Optimization 101']
                ],
                'deadline' => '2025-01-20',
                'deadline_formatted' => '20 Jan 2025',
                'status' => 'PENDING APPROVAL',
                'status_type' => 'pending', // blue
                'description' => 'Strategi optimasi rantai pasokan dari hulu ke hilir untuk meningkatkan efisiensi distribusi produk semen dan menurunkan biaya logistik.',
                'merged_tna_count' => 8,
                'merged_tna_categories' => [
                    ['name' => 'Supply Chain Management', 'description' => 'Pengelolaan aliran barang dan informasi dari hulu ke hilir untuk efisiensi distribusi.'],
                    ['name' => 'Warehouse Logistics', 'description' => 'Sistem pergudangan dan manajemen stok untuk memastikan ketersediaan semen di berbagai silo.'],
                    ['name' => 'Transportation Strategy', 'description' => 'Optimasi rute dan moda transportasi pengiriman darat maupun laut untuk menurunkan biaya logistik.']
                ],
                'target_audience' => ['Supply Chain Manager', 'Logistics Coordinator', 'Procurement Specialist'],
                'course_objective' => '<p><strong>Tujuan Pelatihan:</strong></p><ul><li>Menerapkan konsep lean logistics dalam distribusi produk.</li><li>Mengoptimalkan rute dan moda transportasi pengiriman.</li><li>Menyusun strategi inventory terintegrasi antar pabrik dan silo.</li></ul>',
                'course_content' => '<p><strong>Materi Pembelajaran:</strong></p><ol><li>Pengantar Supply Chain Semen.</li><li>Manajemen Transportasi Darat & Laut.</li><li>Strategi Inventaris Silo.</li><li>Sistem Informasi Logistik.</li></ol>',
                'sme_instructions' => 'Harap berikan data simulasi biaya logistik antara pengiriman truk vs kereta api.',
                'distribution' => 'public',
                'distribution_note' => 'Materi ini dapat disertifikasi dan ditawarkan kepada BUMN lain sebagai best practice logistik bulk material.',
                'need_workshop' => false,
                'workshop_note' => '',
                'documents' => [
                    ['name' => 'sc_optimization_report.pdf', 'size' => 4500000]
                ],
                'participants' => [
                    ['name' => 'Eka Putri', 'nik' => 'SIG-7712', 'position' => 'Logistics Coord', 'department' => 'Dept. Distribution'],
                    ['name' => 'Fajar Nugroho', 'nik' => 'SIG-7822', 'position' => 'Procurement Spc', 'department' => 'Dept. Procurement']
                ]
            ],
            [
                'id' => 'CUR-2024-019',
                'title' => 'Safety & Risk Management in Mining',
                'category' => 'Mining Operation',
                'category_description' => 'Difokuskan pada teknik, operasi, dan protokol keselamatan (K3) pada area penambangan terbuka (open-pit mining).',
                'sme' => [
                    'name' => 'Agus Hermawan',
                    'avatar' => 'https://ui-avatars.com/api/?name=Agus+Hermawan&background=random',
                    'position' => 'HSE Superintendent - Mining',
                    'active_classes' => 3,
                    'teaching_history' => ['Basic Mining Safety', 'Fatigue Management in Heavy Equipment']
                ],
                'deadline' => '2025-02-10',
                'deadline_formatted' => '10 Feb 2025',
                'status' => 'APPROVED & READY',
                'status_type' => 'approved', // green
                'description' => 'Manajemen risiko dan implementasi protokol keselamatan kerja khusus untuk operasional penambangan batu kapur dan tanah liat.',
                'merged_tna_count' => 25,
                'merged_tna_categories' => [
                    ['name' => 'Mining Operation', 'description' => 'Operasional ekstraksi bahan baku tambang (batu kapur & tanah liat) secara efisien.'],
                    ['name' => 'Heavy Equipment Ops', 'description' => 'Pengoperasian dan standar keamanan alat berat (excavator, dump truck) di area tambang.'],
                    ['name' => 'Industrial Health & Safety', 'description' => 'Identifikasi bahaya tambang terbuka dan penerapan metode investigasi insiden.'],
                    ['name' => 'Emergency Response', 'description' => 'Protokol tanggap darurat dan penyelamatan di area tambang berisiko tinggi.']
                ],
                'target_audience' => ['Mine Operations Manager', 'HSE Officer', 'Heavy Equipment Supervisor'],
                'course_objective' => '<p><strong>Tujuan Pelatihan:</strong></p><ul><li>Mengidentifikasi bahaya spesifik di area tambang terbuka.</li><li>Menyusun Job Safety Analysis (JSA) untuk peledakan dan pemuatan.</li><li>Melakukan investigasi insiden menggunakan metode ICAM.</li></ul>',
                'course_content' => '<p><strong>Materi Pembelajaran:</strong></p><ol><li>Regulasi K3 Pertambangan Nasional.</li><li>Prosedur Blasting & Drilling Aman.</li><li>Manajemen Kelelahan (Fatigue) Operator Alat Berat.</li><li>Simulasi Tanggap Darurat Tambang.</li></ol>',
                'sme_instructions' => 'Tekankan pada insiden near-miss yang sering terjadi di area jalan tambang saat musim hujan.',
                'distribution' => 'internal',
                'distribution_note' => 'Materi sangat spesifik terhadap SOP internal SIG di area tambang.',
                'need_workshop' => true,
                'workshop_note' => 'Wajib melakukan kunjungan site (Field Practice) ke area tambang aktif untuk observasi JSA langsung.',
                'documents' => [],
                'participants' => [
                    ['name' => 'Gatot Kaca', 'nik' => 'SIG-6511', 'position' => 'HSE Officer', 'department' => 'Dept. Mining Tuban'],
                    ['name' => 'Hendra Syahputra', 'nik' => 'SIG-6621', 'position' => 'Heavy Equip Spv', 'department' => 'Dept. Mining Tonasa'],
                    ['name' => 'Iwan Fals', 'nik' => 'SIG-6731', 'position' => 'Mine Ops Manager', 'department' => 'Dept. Mining Padang']
                ]
            ],
            [
                'id' => 'CUR-2024-088',
                'title' => 'Digital Leadership & Transformation',
                'category' => 'Leadership',
                'category_description' => 'Pengembangan kapabilitas soft skills kepemimpinan, manajemen talenta, dan adaptasi strategis terhadap perubahan di level manajerial.',
                'sme' => [
                    'name' => 'Siti Aminah',
                    'avatar' => 'https://ui-avatars.com/api/?name=Siti+Aminah&background=random',
                    'position' => 'Director of Human Capital',
                    'active_classes' => 0,
                    'teaching_history' => ['Effective Communication 2022', 'Agile Leadership']
                ],
                'deadline' => '2025-03-05',
                'deadline_formatted' => '05 Mar 2025',
                'status' => 'IN PROGRESS (DRAFTING)',
                'status_type' => 'drafting',
                'description' => 'Membangun kemampuan kepemimpinan adaptif dan visioner di era digital serta memimpin inisiatif transformasi industri 4.0 di lingkungan kerja.',
                'merged_tna_count' => 18,
                'target_audience' => ['Band 1 Executives', 'Band 2 General Managers', 'Band 3 Senior Managers'],
                'course_objective' => '<p><strong>Tujuan Pelatihan:</strong></p><ul><li>Memahami lanskap disrupsi digital dan dampaknya bagi industri manufaktur.</li><li>Mengembangkan digital mindset untuk memimpin tim lintas generasi.</li><li>Menyusun roadmap transformasi digital di unit kerja masing-masing.</li></ul>',
                'course_content' => '<p><strong>Materi Pembelajaran:</strong></p><ol><li>Pengantar Disrupsi Digital.</li><li>Digital Mindset & Culture.</li><li>Strategi Transformasi Industri 4.0.</li></ol>',
                'sme_instructions' => 'Fokuskan pada studi kasus transformasi digital di perusahaan manufaktur global.',
                'distribution' => 'internal',
                'distribution_note' => 'Khusus untuk level manajerial Band 1 hingga Band 3 di SIG.',
                'need_workshop' => false,
                'workshop_note' => '',
                'documents' => [],
                'participants' => [
                    ['name' => 'Joko Anwar', 'nik' => 'SIG-1101', 'position' => 'General Manager', 'department' => 'Dept. Corporate Strategy'],
                    ['name' => 'Kiki Fatmala', 'nik' => 'SIG-1202', 'position' => 'Senior Manager', 'department' => 'Dept. HRBP'],
                    ['name' => 'Lukman Hakim', 'nik' => 'SIG-1303', 'position' => 'General Manager', 'department' => 'Dept. IT']
                ]
            ]
        ];
    }

    public function blueprintDirectory()
    {
        $mockBlueprints = $this->getMockBlueprints();

        $realBlueprints = \App\Models\TrainingBlueprint::with('sme')->get()->map(function($bp) {
            $sme = $bp->sme;
            
            $statusDisplay = strtoupper(str_replace('_', ' ', $bp->status));
            $statusType = 'drafting';
            if ($bp->status === 'assigned' || $bp->status === 'assigned_to_sme') {
                $statusDisplay = 'IN PROGRESS (DRAFTING)';
                $statusType = 'drafting';
            } elseif ($bp->status === 'material_submitted') {
                $statusDisplay = 'PENDING APPROVAL';
                $statusType = 'pending';
            } elseif ($bp->status === 'revision_required') {
                $statusDisplay = 'REVISION REQUIRED';
                $statusType = 'drafting';
            } elseif ($bp->status === 'studio_production') {
                $statusDisplay = 'STUDIO PRODUCTION';
                $statusType = 'approved';
            } elseif ($bp->status === 'curriculum_submitted') {
                $statusDisplay = 'CURRICULUM SUBMITTED';
                $statusType = 'approved';
            } elseif ($bp->status === 'approved') {
                $statusDisplay = 'APPROVED & READY';
                $statusType = 'approved';
            } elseif ($bp->status === 'released') {
                $statusDisplay = 'RELEASED';
                $statusType = 'approved';
            }

            $mergedCategories = is_string($bp->merged_tna_categories) ? json_decode($bp->merged_tna_categories, true) : ($bp->merged_tna_categories ?? []);
            if (empty($mergedCategories)) {
                $mergedCategories = [['name' => $bp->category, 'description' => 'Kategori utama pengelompokan usulan TNA.']];
            }

            return [
                'id' => $bp->blueprint_code ?? $bp->id,
                'title' => $bp->title,
                'category' => $bp->category,
                'category_description' => 'Fokus pada strategi, teknik, dan manajemen kompetensi untuk ' . $bp->category . '.',
                'sme' => [
                    'name' => $sme ? $sme->name : 'SME Belum Ditentukan',
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($sme ? $sme->name : 'SME') . '&background=random',
                    'position' => $sme ? ($sme->position ?? 'Subject Matter Expert') : 'SME',
                    'active_classes' => 1,
                    'teaching_history' => ['Sertifikasi Internal SIG', 'Workshop Kompetensi Teknis']
                ],
                'deadline' => $bp->deadline ? $bp->deadline->format('Y-m-d') : '2026-12-31',
                'deadline_formatted' => $bp->deadline ? $bp->deadline->format('d M Y') : '31 Des 2026',
                'status' => $statusDisplay,
                'status_type' => $statusType,
                'description' => $bp->course_objective ?? 'Pengembangan kurikulum untuk ' . $bp->title,
                'merged_tna_count' => $bp->merged_tna_count ?? 1,
                'merged_tna_categories' => $mergedCategories,
                'target_audience' => is_string($bp->target_audience) ? json_decode($bp->target_audience, true) : ($bp->target_audience ?? ['Staff', 'Supervisor']),
                'course_objective' => $bp->course_objective ? '<p>' . $bp->course_objective . '</p>' : '<p>Belum didefinisikan.</p>',
                'course_content' => $bp->course_content ? '<p>' . $bp->course_content . '</p>' : '<p>Belum didefinisikan.</p>',
                'sme_instructions' => $bp->sme_instructions ?? 'Tidak ada instruksi khusus.',
                'distribution' => $bp->distribution_scope ?? 'internal',
                'distribution_note' => $bp->distribution_note ?? 'Ditujukan untuk lingkup internal SIG.',
                'need_workshop' => (bool)$bp->need_workshop,
                'workshop_note' => $bp->workshop_note ?? '',
                'documents' => [],
                'participants' => [
                    ['name' => 'Ahmad Riyadi', 'nik' => 'SIG-8821', 'position' => 'Maintenance Spv', 'department' => 'Dept. Tuban 1'],
                    ['name' => 'Bagus Pratama', 'nik' => 'SIG-8932', 'position' => 'Reliability Eng', 'department' => 'Dept. Engineering']
                ],
                'materials' => is_string($bp->materials) ? json_decode($bp->materials, true) : ($bp->materials ?? []),
                'revisions' => is_string($bp->revisions) ? json_decode($bp->revisions, true) : ($bp->revisions ?? []),
                'reminder_setting' => $bp->reminder_setting ?? 'H-3',
                'cld_review_notes' => $bp->cld_review_notes,
                'curriculum_structure' => is_string($bp->curriculum_structure) ? json_decode($bp->curriculum_structure, true) : ($bp->curriculum_structure ?? null)
            ];
        })->toArray();

        $blueprints = array_merge($realBlueprints, $mockBlueprints);

        $stats = [
            'total_blueprints' => count($blueprints) + 120,
            'active_smes' => 48,
            'avg_completion' => 82
        ];

        $categories = ['Maintenance Management', 'Supply Chain Management', 'Mining Operation', 'Leadership', 'Management', 'Health & Safety', 'Clinker Production', 'Design & Engineering', 'Research & Development'];

        return view('pages.admin-coordinator.blueprint-directory', [
            'blueprints' => $blueprints,
            'stats' => $stats,
            'categories' => $categories
        ]);
    }

    public function editBlueprint($id)
    {
        $blueprints = $this->getMockBlueprints();
        $blueprint = collect($blueprints)->firstWhere('id', $id);

        if (!$blueprint) {
            return redirect()->route('admin-coordinator.blueprint-directory')->with('error', 'Blueprint tidak ditemukan.');
        }

        $smes = [
            [
                'id' => 1,
                'name' => 'Dr. Ir. Budi Santoso',
                'position' => 'Senior Mechanical Expert',
                'status' => 'Available',
                'load' => '1 Blueprint Aktif',
                'avatar' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=random'
            ],
            [
                'id' => 2,
                'name' => 'Siti Aminah',
                'position' => 'Director of Human Capital',
                'status' => 'Busy',
                'load' => '4 Blueprint Aktif',
                'avatar' => 'https://ui-avatars.com/api/?name=Siti+Aminah&background=random'
            ],
            [
                'id' => 3,
                'name' => 'Agung Setyawan',
                'position' => 'Specialist Kiln & Production',
                'status' => 'Available',
                'load' => '0 Blueprint Aktif',
                'avatar' => 'https://ui-avatars.com/api/?name=Agung+Setyawan&background=random'
            ],
            [
                'id' => 4,
                'name' => 'Rendra Wijaya',
                'position' => 'VP Supply Chain Management',
                'status' => 'Available',
                'load' => '1 Blueprint Aktif',
                'avatar' => 'https://ui-avatars.com/api/?name=Rendra+Wijaya&background=random'
            ],
            [
                'id' => 5,
                'name' => 'Agus Hermawan',
                'position' => 'HSE Superintendent - Mining',
                'status' => 'Available',
                'load' => '3 Blueprint Aktif',
                'avatar' => 'https://ui-avatars.com/api/?name=Agus+Hermawan&background=random'
            ]
        ];

        $categories = ['Maintenance Management', 'Supply Chain Management', 'Mining Operation', 'Leadership', 'Management', 'Health & Safety', 'Clinker Production', 'Design & Engineering', 'Research & Development'];

        return view('pages.admin-coordinator.edit-blueprint', [
            'blueprint' => $blueprint,
            'smes' => $smes,
            'categories' => $categories
        ]);
    }

    /**
     * Destroy a blueprint (Mock deletion)
     */
    public function destroyBlueprint($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Blueprint berhasil dihapus dari sistem.'
        ]);
    }

    /**
     * Category Approval Board View
     */
    public function categoryApproval()
    {
        $companySIG = \App\Models\Company::where('name', 'PT Semen Indonesia (Persero) Tbk')->first();
        $sig = \App\Models\Organization::where('code', 'SIG-LVL0')->first();
        $dirHC = \App\Models\Organization::where('code', 'SIG-DIR-HC')->first();
        $grpOHC = \App\Models\Organization::where('code', 'SIG-GRP-OHC')->first();
        $deptCLD = \App\Models\Organization::where('code', 'SIG-DEPT-CLD')->first();
        $unitCLD = \App\Models\Organization::where('code', 'SIG-UNIT-CLD')->first();

        $dirUtama = \App\Models\Organization::where('code', 'SIG-DIR-UTAMA')->first();
        $grpUtama = \App\Models\Organization::where('code', 'SIG-GRP-UTAMA')->first();
        $deptCEO = \App\Models\Organization::where('code', 'SIG-DEPT-CEO')->first();

        $companySG = \App\Models\Company::where('name', 'PT Semen Gresik')->first();
        $orgSG_LVL0 = \App\Models\Organization::where('code', 'SG-LVL0')->first();
        $orgSG_Dept = \App\Models\Organization::where('code', 'SG-DEPT-SAMPLE')->first();
        $orgSG_Unit = \App\Models\Organization::where('code', 'SG-UNIT-SAMPLE')->first();

        $companySP = \App\Models\Company::where('name', 'PT Semen Padang')->first();
        $orgSP_LVL0 = \App\Models\Organization::where('code', 'SP-LVL0')->first();
        $orgSP_Dept = \App\Models\Organization::where('code', 'SP-DEPT-SAMPLE')->first();
        $orgSP_Unit = \App\Models\Organization::where('code', 'SP-UNIT-SAMPLE')->first();

        $companyST = \App\Models\Company::where('name', 'PT Semen Tonasa')->first();
        $orgST_LVL0 = \App\Models\Organization::where('code', 'ST-LVL0')->first();
        $orgST_Dept = \App\Models\Organization::where('code', 'ST-DEPT-SAMPLE')->first();
        $orgST_Unit = \App\Models\Organization::where('code', 'ST-UNIT-SAMPLE')->first();

        $companySBI = \App\Models\Company::where('name', 'PT Solusi Bangun Indonesia Tbk')->first();
        $orgSBI_LVL0 = \App\Models\Organization::where('code', 'SBI-LVL0')->first();
        $orgSBI_Dept = \App\Models\Organization::where('code', 'SBI-DEPT-SAMPLE')->first();
        $orgSBI_Unit = \App\Models\Organization::where('code', 'SBI-UNIT-SAMPLE')->first();

        $companySMBR = \App\Models\Company::where('name', 'PT Semen Baturaja Tbk')->first();
        $orgSMBR_LVL0 = \App\Models\Organization::where('code', 'SMBR-LVL0')->first();
        $orgSMBR_Dept = \App\Models\Organization::where('code', 'SMBR-DEPT-SAMPLE')->first();
        $orgSMBR_Unit = \App\Models\Organization::where('code', 'SMBR-UNIT-SAMPLE')->first();

        $companySIB = \App\Models\Company::where('name', 'PT Semen Indonesia Beton')->first();
        $orgSIB_LVL0 = \App\Models\Organization::where('code', 'SIB-LVL0')->first();
        $orgSIB_Dept = \App\Models\Organization::where('code', 'SIB-DEPT-SAMPLE')->first();
        $orgSIB_Unit = \App\Models\Organization::where('code', 'SIB-UNIT-SAMPLE')->first();

        // 12 Pending Requests
        $pendingRequests = [
            [
                'id' => 'REQ-CAT-001',
                'name' => 'Artificial Intelligence & RPA',
                'urgency' => 'URGENT REVIEW',
                'urgency_level' => 'High',
                'submitted_by_initial' => 'FN',
                'submitted_by_name' => 'Fajar Nugroho',
                'submitted_by_dept' => 'PT Semen Gresik',
                'submitted_by_department' => 'Department of PT Semen Gresik Operations',
                'submitted_by_unit' => 'Unit of PT Semen Gresik Support',
                'company_id' => $companySG?->id,
                'organization_id' => $orgSG_Unit?->id,
                'org_path_ids' => [$orgSG_LVL0?->id, $orgSG_Dept?->id, $orgSG_Unit?->id],
                'org_path' => [
                    ['level' => 'Department', 'name' => 'Department of PT Semen Gresik Operations'],
                    ['level' => 'Unit', 'name' => 'Unit of PT Semen Gresik Support']
                ],
                'date' => '22 Okt 2024',
                'description' => 'Pelatihan dan sertifikasi mendalam mengenai penerapan teknologi kecerdasan buatan (AI) serta Robotic Process Automation (RPA) guna mengotomatisasi proses bisnis repetitif di lingkungan operasional pabrik SIG.',
                'reason' => 'Belum ada payung taksonomi untuk kursus otomasi robotik.',
                'documents' => [
                    ['name' => 'Proposal_Taksonomi_AI_RPA.pdf', 'size' => '2.4 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'],
                    ['name' => 'RPA_Use_Cases_Tuban.xlsx', 'size' => '1.1 MB', 'url' => '#']
                ],
                'status' => 'pending'
            ],
            [
                'id' => 'REQ-CAT-002',
                'name' => 'Heavy Equipment Maintenance',
                'urgency' => null,
                'urgency_level' => 'Medium',
                'submitted_by_initial' => 'SR',
                'submitted_by_name' => 'Siti Rahma',
                'submitted_by_dept' => 'PT Semen Padang',
                'submitted_by_department' => 'Department of PT Semen Padang Operations',
                'submitted_by_unit' => 'Unit of PT Semen Padang Support',
                'company_id' => $companySP?->id,
                'organization_id' => $orgSP_Unit?->id,
                'org_path_ids' => [$orgSP_LVL0?->id, $orgSP_Dept?->id, $orgSP_Unit?->id],
                'org_path' => [
                    ['level' => 'Department', 'name' => 'Department of PT Semen Padang Operations'],
                    ['level' => 'Unit', 'name' => 'Unit of PT Semen Padang Support']
                ],
                'date' => '18 Okt 2024',
                'description' => 'Ruang lingkup mencakup teknik inspeksi, overhaul, dan pemeliharaan preventif untuk alat-alat berat (excavator, wheel loader, dump truck) di area pertambangan dan workshop.',
                'reason' => 'Kebutuhan klasifikasi aset baru di workshop.',
                'documents' => [
                    ['name' => 'Heavy_Equip_Syllabus.pdf', 'size' => '4.5 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf']
                ],
                'status' => 'approved'
            ],
            [
                'id' => 'REQ-CAT-003',
                'name' => 'Basic Microsoft Office',
                'urgency' => null,
                'urgency_level' => 'Low - Reguler / Non-Mendesak',
                'submitted_by_initial' => 'AD',
                'submitted_by_name' => 'Agus Danu',
                'submitted_by_dept' => 'PT Semen Tonasa',
                'submitted_by_department' => 'Department of PT Semen Tonasa Operations',
                'submitted_by_unit' => null,
                'company_id' => $companyST?->id,
                'organization_id' => $orgST_Dept?->id,
                'org_path_ids' => [$orgST_LVL0?->id, $orgST_Dept?->id],
                'org_path' => [
                    ['level' => 'Department', 'name' => 'Department of PT Semen Tonasa Operations']
                ],
                'date' => '15 Okt 2024',
                'description' => 'Pelatihan keterampilan dasar penggunaan aplikasi perkantoran seperti Word, Excel, dan PowerPoint untuk staf administrasi baru.',
                'reason' => 'Sudah tercakup dalam kategori General IT.',
                'feedback' => 'Usulan kategori ditolak karena materi Basic Microsoft Office sudah tercakup sepenuhnya di dalam kategori eksisting General IT. Silakan gunakan kategori tersebut untuk pengajuan modul Anda.',
                'documents' => [
                    ['name' => 'Modul_Dasar_Office.pdf', 'size' => '1.8 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf']
                ],
                'status' => 'rejected'
            ],
            [
                'id' => 'REQ-CAT-004',
                'name' => 'Advanced Data Analytics & BI',
                'urgency' => null,
                'urgency_level' => 'High',
                'submitted_by_initial' => 'DW',
                'submitted_by_name' => 'Dwi Wulandari',
                'submitted_by_dept' => 'PT Solusi Bangun Indonesia Tbk',
                'submitted_by_department' => 'Department of PT Solusi Bangun Indonesia Tbk Operations',
                'submitted_by_unit' => 'Unit of PT Solusi Bangun Indonesia Tbk Support',
                'company_id' => $companySBI?->id,
                'organization_id' => $orgSBI_Unit?->id,
                'org_path_ids' => [$orgSBI_LVL0?->id, $orgSBI_Dept?->id, $orgSBI_Unit?->id],
                'org_path' => [
                    ['level' => 'Department', 'name' => 'Department of PT Solusi Bangun Indonesia Tbk Operations'],
                    ['level' => 'Unit', 'name' => 'Unit of PT Solusi Bangun Indonesia Tbk Support']
                ],
                'date' => '14 Okt 2024',
                'description' => 'Mencakup teknik pemodelan data tingkat lanjut, visualisasi menggunakan PowerBI/Tableau, serta analisis prediktif untuk mendukung pengambilan keputusan strategis holding.',
                'reason' => 'Mendukung inisiatif data-driven decision making di seluruh holding.',
                'documents' => [
                    ['name' => 'BI_Analytics_Roadmap.pdf', 'size' => '3.2 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf']
                ],
                'status' => 'pending'
            ],
            [
                'id' => 'REQ-CAT-005',
                'name' => 'Green Energy & Sustainability',
                'urgency' => 'URGENT REVIEW',
                'urgency_level' => 'High',
                'submitted_by_initial' => 'TH',
                'submitted_by_name' => 'Taufik Hidayat',
                'submitted_by_dept' => 'PT Semen Baturaja Tbk',
                'submitted_by_department' => 'Department of PT Semen Baturaja Tbk Operations',
                'submitted_by_unit' => 'Unit of PT Semen Baturaja Tbk Support',
                'company_id' => $companySMBR?->id,
                'organization_id' => $orgSMBR_Unit?->id,
                'org_path_ids' => [$orgSMBR_LVL0?->id, $orgSMBR_Dept?->id, $orgSMBR_Unit?->id],
                'org_path' => [
                    ['level' => 'Department', 'name' => 'Department of PT Semen Baturaja Tbk Operations'],
                    ['level' => 'Unit', 'name' => 'Unit of PT Semen Baturaja Tbk Support']
                ],
                'date' => '12 Okt 2024',
                'description' => 'Fokus pada transisi energi hijau, pemanfaatan solar panel industri, audit energi, serta kepatuhan terhadap standar ESG (Environmental, Social, and Governance).',
                'reason' => 'Kebutuhan mendesak terkait regulasi carbon trading & ESG compliance.',
                'documents' => [
                    ['name' => 'ESG_Compliance_Framework.pdf', 'size' => '5.1 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'],
                    ['name' => 'Solar_Panel_Feasibility.pdf', 'size' => '2.9 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf']
                ],
                'status' => 'pending'
            ],
            [
                'id' => 'REQ-CAT-006',
                'name' => 'Cybersecurity & Information Privacy',
                'urgency' => null,
                'urgency_level' => 'Medium',
                'submitted_by_initial' => 'RA',
                'submitted_by_name' => 'Rizky Pratama',
                'submitted_by_dept' => 'PT Semen Indonesia (Persero) Tbk',
                'submitted_by_department' => 'Department of Corporate Strategy',
                'submitted_by_unit' => null,
                'company_id' => $companySIG?->id,
                'organization_id' => $deptCEO?->id,
                'org_path_ids' => [$sig?->id, $dirUtama?->id, $grpUtama?->id, $deptCEO?->id],
                'org_path' => [
                    ['level' => 'Direktorat', 'name' => 'Direktorat Utama'],
                    ['level' => 'Group of', 'name' => 'Group of CEO Office'],
                    ['level' => 'Department', 'name' => 'Department of Corporate Strategy']
                ],
                'date' => '10 Okt 2024',
                'description' => 'Ruang lingkup pelatihan kesadaran keamanan siber (security awareness), perlindungan data pribadi (PDP), dan penanganan insiden siber (incident response) di SIG.',
                'reason' => 'Menyesuaikan dengan berlakunya UU PDP dan standar ISO 27001.',
                'documents' => [
                    ['name' => 'Cybersecurity_SOP_2024.pdf', 'size' => '1.5 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf']
                ],
                'status' => 'pending'
            ],
            [
                'id' => 'REQ-CAT-007',
                'name' => 'Digital Marketing & B2B Sales',
                'urgency' => null,
                'urgency_level' => 'Medium',
                'submitted_by_initial' => 'MK',
                'submitted_by_name' => 'Maya Kurnia',
                'submitted_by_dept' => 'PT Semen Indonesia Beton',
                'submitted_by_department' => 'Department of PT Semen Indonesia Beton Operations',
                'submitted_by_unit' => 'Unit of PT Semen Indonesia Beton Support',
                'company_id' => $companySIB?->id,
                'organization_id' => $orgSIB_Unit?->id,
                'org_path_ids' => [$orgSIB_LVL0?->id, $orgSIB_Dept?->id, $orgSIB_Unit?->id],
                'org_path' => [
                    ['level' => 'Department', 'name' => 'Department of PT Semen Indonesia Beton Operations'],
                    ['level' => 'Unit', 'name' => 'Unit of PT Semen Indonesia Beton Support']
                ],
                'date' => '08 Okt 2024',
                'description' => 'Strategi pemasaran digital, pengelolaan akun pelanggan kunci (key account management), dan teknik negosiasi penjualan B2B untuk produk semen dan turunan.',
                'reason' => 'Ekspansi pasar komersial dan strategi pemasaran digital B2B.',
                'documents' => [
                    ['name' => 'B2B_Sales_Strategy.pdf', 'size' => '3.8 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf']
                ],
                'status' => 'pending'
            ],
            [
                'id' => 'REQ-CAT-008',
                'name' => 'Alternative Fuel & Raw Material (AFR)',
                'urgency' => null,
                'urgency_level' => 'High',
                'submitted_by_initial' => 'BS',
                'submitted_by_name' => 'Bambang Soesatyo',
                'submitted_by_dept' => 'PT Semen Gresik',
                'submitted_by_department' => 'Department of PT Semen Gresik Operations',
                'submitted_by_unit' => 'Unit of PT Semen Gresik Support',
                'company_id' => $companySG?->id,
                'organization_id' => $orgSG_Unit?->id,
                'org_path_ids' => [$orgSG_LVL0?->id, $orgSG_Dept?->id, $orgSG_Unit?->id],
                'org_path' => [
                    ['level' => 'Department', 'name' => 'Department of PT Semen Gresik Operations'],
                    ['level' => 'Unit', 'name' => 'Unit of PT Semen Gresik Support']
                ],
                'date' => '05 Okt 2024',
                'description' => 'Pengelolaan, penanganan, dan pemanfaatan limbah industri dan biomassa sebagai bahan bakar alternatif dan bahan baku pengganti di kiln semen.',
                'reason' => 'Sub-kategori khusus untuk pemanfaatan limbah sebagai bahan bakar alternatif.',
                'documents' => [
                    ['name' => 'AFR_Utilization_Guide.pdf', 'size' => '4.2 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf']
                ],
                'status' => 'pending'
            ],
            [
                'id' => 'REQ-CAT-009',
                'name' => 'Predictive Maintenance & IoT',
                'urgency' => null,
                'urgency_level' => 'Medium',
                'submitted_by_initial' => 'HS',
                'submitted_by_name' => 'Hendra Setiawan',
                'submitted_by_dept' => 'PT Semen Padang',
                'submitted_by_department' => 'Department of PT Semen Padang Operations',
                'submitted_by_unit' => 'Unit of PT Semen Padang Support',
                'company_id' => $companySP?->id,
                'organization_id' => $orgSP_Unit?->id,
                'org_path_ids' => [$orgSP_LVL0?->id, $orgSP_Dept?->id, $orgSP_Unit?->id],
                'org_path' => [
                    ['level' => 'Department', 'name' => 'Department of PT Semen Padang Operations'],
                    ['level' => 'Unit', 'name' => 'Unit of PT Semen Padang Support']
                ],
                'date' => '02 Okt 2024',
                'description' => 'Penerapan sensor Internet of Things (IoT), analisis getaran online, dan termografi inframerah untuk memprediksi kegagalan peralatan pabrik sebelum terjadi breakdown.',
                'reason' => 'Peralihan dari preventive ke predictive maintenance menggunakan sensor IoT.',
                'documents' => [
                    ['name' => 'IoT_Sensors_Spec.pdf', 'size' => '2.1 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf']
                ],
                'status' => 'pending'
            ],
            [
                'id' => 'REQ-CAT-010',
                'name' => 'Advanced Negotiation & Contract Law',
                'urgency' => null,
                'urgency_level' => 'Low - Reguler / Non-Mendesak',
                'submitted_by_initial' => 'LN',
                'submitted_by_name' => 'Lestari Ningsih',
                'submitted_by_dept' => 'PT Semen Tonasa',
                'submitted_by_department' => 'Department of PT Semen Tonasa Operations',
                'submitted_by_unit' => null,
                'company_id' => $companyST?->id,
                'organization_id' => $orgST_Dept?->id,
                'org_path_ids' => [$orgST_LVL0?->id, $orgST_Dept?->id],
                'org_path' => [
                    ['level' => 'Department', 'name' => 'Department of PT Semen Tonasa Operations']
                ],
                'date' => '01 Okt 2024',
                'description' => 'Membahas hukum perikatan, penyusunan kontrak pengadaan barang dan jasa berskala besar, serta taktik negosiasi komersial dengan vendor internasional.',
                'reason' => 'Peningkatan kompetensi tim legal dalam kontrak internasional.',
                'documents' => [
                    ['name' => 'Contract_Law_Syllabus.pdf', 'size' => '1.9 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf']
                ],
                'status' => 'pending'
            ],
            [
                'id' => 'REQ-CAT-011',
                'name' => 'Mental Health & Workplace Well-being',
                'urgency' => null,
                'urgency_level' => 'Low - Reguler / Non-Mendesak',
                'submitted_by_initial' => 'dr',
                'submitted_by_name' => 'dr. Anita Ratna',
                'submitted_by_dept' => 'PT Solusi Bangun Indonesia Tbk',
                'submitted_by_department' => 'Department of PT Solusi Bangun Indonesia Tbk Operations',
                'submitted_by_unit' => 'Unit of PT Solusi Bangun Indonesia Tbk Support',
                'company_id' => $companySBI?->id,
                'organization_id' => $orgSBI_Unit?->id,
                'org_path_ids' => [$orgSBI_LVL0?->id, $orgSBI_Dept?->id, $orgSBI_Unit?->id],
                'org_path' => [
                    ['level' => 'Department', 'name' => 'Department of PT Solusi Bangun Indonesia Tbk Operations'],
                    ['level' => 'Unit', 'name' => 'Unit of PT Solusi Bangun Indonesia Tbk Support']
                ],
                'date' => '28 Sep 2024',
                'description' => 'Pendidikan kesehatan mental di tempat kerja, pertolongan pertama psikologis (psychological first aid), dan pembentukan budaya kerja yang mendukung kesejahteraan karyawan.',
                'reason' => 'Program employee assistance dan manajemen stres kerja.',
                'documents' => [
                    ['name' => 'Workplace_Wellbeing_Program.pdf', 'size' => '2.7 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf']
                ],
                'status' => 'pending'
            ],
            [
                'id' => 'REQ-CAT-012',
                'name' => 'Agile Project Management & Scrum',
                'urgency' => null,
                'urgency_level' => 'Medium',
                'submitted_by_initial' => 'IP',
                'submitted_by_name' => 'Indra Putra',
                'submitted_by_dept' => 'PT Semen Indonesia (Persero) Tbk',
                'submitted_by_department' => 'Unit of Competency and Learning Design',
                'submitted_by_unit' => null,
                'company_id' => $companySIG?->id,
                'organization_id' => $unitCLD?->id,
                'org_path_ids' => [$sig?->id, $dirHC?->id, $grpOHC?->id, $deptCLD?->id, $unitCLD?->id],
                'org_path' => [
                    ['level' => 'Direktorat', 'name' => 'Direktorat Human Capital'],
                    ['level' => 'Group of', 'name' => 'Group of Operational Human Capital'],
                    ['level' => 'Department', 'name' => 'Department of Corporate Learning & Development'],
                    ['level' => 'Unit', 'name' => 'Unit of Competency and Learning Design']
                ],
                'date' => '25 Sep 2024',
                'description' => 'Kerangka kerja Scrum, Kanban, dan manajemen proyek tangkas (Agile) untuk mempercepat penyampaian inisiatif strategis di lingkungan holding SIG.',
                'reason' => 'Standardisasi metode kerja lincah di proyek transformasi digital.',
                'documents' => [
                    ['name' => 'Agile_PMO_Handbook.pdf', 'size' => '3.5 MB', 'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf']
                ],
                'status' => 'pending'
            ]
        ];

        // 45 Active Categories
        // Active Categories with Parent-Child Hierarchy (Standard SIG Taxonomy)
        $activeCategories = [
            [
                'id' => 'CAT-001',
                'name' => 'Maintenance Management',
                'description' => 'Manajemen pemeliharaan aset pabrik, keandalan mesin rotasi, dan strategi preventive/predictive maintenance.',
                'total_blueprints' => '24 Blueprints Aktif',
                'badge' => null,
                'sme_count' => '8 Akses Pakar',
                'date' => '12 Jan 2024',
                'is_active' => true,
                'is_legacy' => false,
                'children' => [
                    [
                        'id' => 'CAT-001-A',
                        'name' => 'Rotary Kiln Maintenance',
                        'description' => 'Teknik inspeksi, alignment, dan pemeliharaan refraktori pada rotary kiln utama.',
                        'total_blueprints' => '10 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '3 Akses Pakar',
                        'date' => '14 Jan 2024',
                        'is_active' => true,
                        'is_legacy' => false
                    ],
                    [
                        'id' => 'CAT-001-B',
                        'name' => 'Vibration Analysis & Condition Monitoring',
                        'description' => 'Pemantauan getaran mesin kritis seperti raw mill dan finish mill untuk mencegah unpredicted breakdown.',
                        'total_blueprints' => '8 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '2 Akses Pakar',
                        'date' => '20 Feb 2024',
                        'is_active' => true,
                        'is_legacy' => false
                    ],
                    [
                        'id' => 'CAT-001-C',
                        'name' => 'Heavy Equipment Overhaul',
                        'description' => 'Perawatan berkala dan turun mesin alat berat pertambangan (excavator, dump truck).',
                        'total_blueprints' => '6 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '3 Akses Pakar',
                        'date' => '05 Mar 2024',
                        'is_active' => true,
                        'is_legacy' => false
                    ]
                ]
            ],
            [
                'id' => 'CAT-002',
                'name' => 'Artificial Intelligence & RPA',
                'description' => 'Penerapan kecerdasan buatan dan otomatisasi proses robotik untuk efisiensi operasional pabrik.',
                'total_blueprints' => '0 Blueprint',
                'badge' => '(MENUNGGU INISIASI)',
                'sme_count' => '1 Akses Pakar',
                'date' => '22 Okt 2024',
                'is_active' => true,
                'is_legacy' => false,
                'children' => [
                    [
                        'id' => 'CAT-002-A',
                        'name' => 'Otomasi RPA (Robotic Process Automation)',
                        'description' => 'Otomatisasi alur kerja administratif dan pelaporan produksi harian pabrik.',
                        'total_blueprints' => '0 Blueprint',
                        'badge' => '(MENUNGGU INISIASI)',
                        'sme_count' => '1 Akses Pakar',
                        'date' => '22 Okt 2024',
                        'is_active' => true,
                        'is_legacy' => false
                    ],
                    [
                        'id' => 'CAT-002-B',
                        'name' => 'Machine Learning & Predictive Analytics',
                        'description' => 'Pemanfaatan algoritma ML untuk memprediksi kualitas klinker dan efisiensi pembakaran kiln.',
                        'total_blueprints' => '0 Blueprint',
                        'badge' => '(MENUNGGU INISIASI)',
                        'sme_count' => '0 Akses Pakar',
                        'date' => '22 Okt 2024',
                        'is_active' => true,
                        'is_legacy' => false
                    ]
                ]
            ],
            [
                'id' => 'CAT-003',
                'name' => 'Supply Chain Management',
                'description' => 'Manajemen logistik, efisiensi distribusi semen bulk/bag, dan optimasi jaringan rantai pasok.',
                'total_blueprints' => '18 Blueprints Aktif',
                'badge' => null,
                'sme_count' => '5 Akses Pakar',
                'date' => '15 Mar 2023',
                'is_active' => true,
                'is_legacy' => false,
                'children' => [
                    [
                        'id' => 'CAT-003-A',
                        'name' => 'Warehouse & Silo Logistics',
                        'description' => 'Sistem pergudangan dan manajemen stok untuk memastikan ketersediaan semen di berbagai silo.',
                        'total_blueprints' => '10 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '3 Akses Pakar',
                        'date' => '18 Mar 2023',
                        'is_active' => true,
                        'is_legacy' => false
                    ],
                    [
                        'id' => 'CAT-003-B',
                        'name' => 'Port & Shipping Operations',
                        'description' => 'Manajemen pelabuhan khusus dan pengiriman semen curah via jalur laut antar pulau.',
                        'total_blueprints' => '8 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '2 Akses Pakar',
                        'date' => '02 Apr 2023',
                        'is_active' => true,
                        'is_legacy' => false
                    ]
                ]
            ],
            [
                'id' => 'CAT-004',
                'name' => 'Mining Operation',
                'description' => 'Operasional ekstraksi bahan baku tambang (batu kapur & tanah liat) secara efisien dan ramah lingkungan.',
                'total_blueprints' => '32 Blueprints Aktif',
                'badge' => null,
                'sme_count' => '12 Akses Pakar',
                'date' => '08 Apr 2022',
                'is_active' => true,
                'is_legacy' => false,
                'children' => [
                    [
                        'id' => 'CAT-004-A',
                        'name' => 'Blasting & Drilling Safety',
                        'description' => 'Teknik peledakan dan pengeboran tambang kapur dengan kontrol getaran dan keselamatan ketat.',
                        'total_blueprints' => '14 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '5 Akses Pakar',
                        'date' => '10 Apr 2022',
                        'is_active' => true,
                        'is_legacy' => false
                    ],
                    [
                        'id' => 'CAT-004-B',
                        'name' => 'Geotechnical & Quarry Management',
                        'description' => 'Kestabilan lereng tambang dan perencanaan desain quarry jangka panjang.',
                        'total_blueprints' => '18 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '7 Akses Pakar',
                        'date' => '25 Mei 2022',
                        'is_active' => true,
                        'is_legacy' => false
                    ]
                ]
            ],
            [
                'id' => 'CAT-005',
                'name' => 'Leadership & Strategic Thinking',
                'description' => 'Pengembangan kapabilitas soft skills kepemimpinan, manajemen talenta, dan adaptasi strategis.',
                'total_blueprints' => '15 Blueprints Aktif',
                'badge' => null,
                'sme_count' => '6 Akses Pakar',
                'date' => '20 Mei 2023',
                'is_active' => true,
                'is_legacy' => false,
                'children' => [
                    [
                        'id' => 'CAT-005-A',
                        'name' => 'Digital Leadership & Mindset',
                        'description' => 'Membangun kemampuan kepemimpinan adaptif dan visioner di era transformasi digital.',
                        'total_blueprints' => '8 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '3 Akses Pakar',
                        'date' => '22 Mei 2023',
                        'is_active' => true,
                        'is_legacy' => false
                    ],
                    [
                        'id' => 'CAT-005-B',
                        'name' => 'Agile Project Management',
                        'description' => 'Kerangka kerja lincah (Scrum/Kanban) untuk mempercepat inisiatif strategis holding.',
                        'total_blueprints' => '7 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '3 Akses Pakar',
                        'date' => '10 Jun 2023',
                        'is_active' => true,
                        'is_legacy' => false
                    ]
                ]
            ],
            [
                'id' => 'CAT-006',
                'name' => 'Health & Safety (HSE)',
                'description' => 'Standar keselamatan kerja K3, regulasi lingkungan, dan kepatuhan hukum korporasi.',
                'total_blueprints' => '40 Blueprints Aktif',
                'badge' => null,
                'sme_count' => '14 Akses Pakar',
                'date' => '11 Jun 2021',
                'is_active' => true,
                'is_legacy' => false,
                'children' => [
                    [
                        'id' => 'CAT-006-A',
                        'name' => 'K3 Pertambangan & Investigasi Insiden',
                        'description' => 'Identifikasi bahaya tambang terbuka dan penerapan metode investigasi ICAM.',
                        'total_blueprints' => '22 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '8 Akses Pakar',
                        'date' => '15 Jun 2021',
                        'is_active' => true,
                        'is_legacy' => false
                    ],
                    [
                        'id' => 'CAT-006-B',
                        'name' => 'Safety Protocol for High-Temp Operations',
                        'description' => 'Prosedur keselamatan kerja khusus untuk area operasional suhu tinggi di sekitar kiln.',
                        'total_blueprints' => '18 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '6 Akses Pakar',
                        'date' => '01 Jul 2021',
                        'is_active' => true,
                        'is_legacy' => false
                    ]
                ]
            ],
            [
                'id' => 'CAT-007',
                'name' => 'Clinker Production & Chemistry',
                'description' => 'Pengendalian proses pembakaran kiln, reaksi kimia semen, dan manajemen mutu klinker.',
                'total_blueprints' => '28 Blueprints Aktif',
                'badge' => null,
                'sme_count' => '9 Akses Pakar',
                'date' => '05 Jul 2022',
                'is_active' => true,
                'is_legacy' => false,
                'children' => [
                    [
                        'id' => 'CAT-007-A',
                        'name' => 'Advanced Kiln Optimization',
                        'description' => 'Optimasi operasional kiln untuk penghematan energi panas dan peningkatan kualitas klinker.',
                        'total_blueprints' => '16 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '5 Akses Pakar',
                        'date' => '10 Jul 2022',
                        'is_active' => true,
                        'is_legacy' => false
                    ],
                    [
                        'id' => 'CAT-007-B',
                        'name' => 'Raw Meal Grinding & Blending',
                        'description' => 'Teknik penggilingan bahan baku dan homogenisasi di raw mill silo.',
                        'total_blueprints' => '12 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '4 Akses Pakar',
                        'date' => '20 Agu 2022',
                        'is_active' => true,
                        'is_legacy' => false
                    ]
                ]
            ],
            [
                'id' => 'CAT-008',
                'name' => 'Design & Engineering',
                'description' => 'Perencanaan rekayasa teknik, modifikasi layout pabrik, dan konstruksi sipil industri.',
                'total_blueprints' => '12 Blueprints Aktif',
                'badge' => null,
                'sme_count' => '4 Akses Pakar',
                'date' => '18 Agu 2023',
                'is_active' => true,
                'is_legacy' => false,
                'children' => [
                    [
                        'id' => 'CAT-008-A',
                        'name' => 'Plant Layout & Piping Design',
                        'description' => 'Desain modifikasi sistem perpipaan gas dan saluran material pabrik.',
                        'total_blueprints' => '12 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '4 Akses Pakar',
                        'date' => '20 Agu 2023',
                        'is_active' => true,
                        'is_legacy' => false
                    ]
                ]
            ],
            [
                'id' => 'CAT-009',
                'name' => 'Research & Development',
                'description' => 'Inovasi produk semen baru, riset beton ramah lingkungan, dan pemanfaatan limbah.',
                'total_blueprints' => '10 Blueprints Aktif',
                'badge' => null,
                'sme_count' => '3 Akses Pakar',
                'date' => '22 Sep 2023',
                'is_active' => true,
                'is_legacy' => false,
                'children' => [
                    [
                        'id' => 'CAT-009-A',
                        'name' => 'Alternative Fuel & Raw Material (AFR)',
                        'description' => 'Pemanfaatan limbah industri dan biomassa sebagai bahan bakar alternatif di kiln.',
                        'total_blueprints' => '10 Blueprints Aktif',
                        'badge' => null,
                        'sme_count' => '3 Akses Pakar',
                        'date' => '25 Sep 2023',
                        'is_active' => true,
                        'is_legacy' => false
                    ]
                ]
            ],
            [
                'id' => 'CAT-010',
                'name' => 'Legacy Typist Training',
                'description' => 'Pelatihan pengetikan manual dan pengarsipan fisik berbasis mesin tik konvensional.',
                'total_blueprints' => '5 Blueprints',
                'badge' => '(USANG)',
                'sme_count' => '0 Akses Pakar',
                'date' => '10 Feb 2018',
                'is_active' => false,
                'is_legacy' => true,
                'children' => []
            ]
        ];

        $smes = [
            ['id' => 1, 'name' => 'Dr. Ir. Budi Santoso', 'position' => 'Senior Mechanical Expert'],
            ['id' => 2, 'name' => 'Siti Aminah', 'position' => 'Director of Human Capital'],
            ['id' => 3, 'name' => 'Agung Setyawan', 'position' => 'Specialist Kiln & Production'],
            ['id' => 4, 'name' => 'Rendra Wijaya', 'position' => 'VP Supply Chain Management'],
            ['id' => 5, 'name' => 'Agus Hermawan', 'position' => 'HSE Superintendent - Mining']
        ];

        $companies = \App\Models\Company::all();
        $orgLevels = \App\Models\OrgLevel::all();
        $organizations = \App\Models\Organization::all();

        return view('pages.admin-coordinator.category-approval', [
            'pendingRequests' => $pendingRequests,
            'activeCategories' => $activeCategories,
            'smes' => $smes,
            'companies' => $companies,
            'orgLevels' => $orgLevels,
            'organizations' => $organizations
        ]);
    }

    public function approveCategory($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Pengajuan kategori berhasil disetujui dan ditambahkan ke repositori aktif.'
        ]);
    }

    public function rejectCategory($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Pengajuan kategori berhasil ditolak.'
        ]);
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'reason' => 'required|string',
            'parent' => 'nullable|string',
            'sme' => 'nullable|string',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori baru berhasil dibuat dan masuk ke repositori aktif.',
            'category' => [
                'id' => 'CAT-' . rand(100, 999),
                'name' => $validated['name'],
                'description' => $validated['reason'],
                'total_blueprints' => '0 Blueprint',
                'badge' => '(MENUNGGU INISIASI)',
                'sme_count' => $validated['sme'] ? '1 Akses Pakar' : '0 Akses Pakar',
                'date' => date('d M Y'),
                'is_active' => true,
                'is_legacy' => false
            ]
        ]);
    }

    public function toggleCategoryStatus($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Status kategori berhasil diperbarui.'
        ]);
    }

    /**
     * Get hierarchy data for companies
     */
    public function getHierarchy(Request $request)
    {
        $companyIds = $request->input('company_ids', []);
        
        if (empty($companyIds)) {
            return response()->json([
                'levels' => [],
                'organizations' => []
            ]);
        }

        $levels = \App\Models\OrgLevel::whereIn('company_id', $companyIds)
            ->orderBy('order')
            ->get()
            ->groupBy('order')
            ->map(function ($group) {
                // If multiple companies have different names for the same order, join them
                return [
                    'order' => $group->first()->order,
                    'names' => $group->pluck('name')->unique()->toArray(),
                    'label' => $group->pluck('name')->unique()->implode('/')
                ];
            })
            ->values();

        $organizations = \App\Models\Organization::whereHas('level', function ($q) use ($companyIds) {
                $q->whereIn('company_id', $companyIds);
            })
            ->get()
            ->map(function ($org) {
                return [
                    'id' => $org->id,
                    'name' => $org->name,
                    'level_order' => $org->level->order,
                    'parent_id' => $org->parent_id
                ];
            });

        return response()->json([
            'levels' => $levels,
            'organizations' => $organizations
        ]);
    }

    public function destroy($id)
    {
        // Logic can also be moved to service if complex
        $submission = TnaSubmission::findOrFail($id);
        $submission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usulan berhasil dihapus.'
        ]);
    }
    public function initiateBlueprint(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu usulan TNA.');
        }

        // Fetch submissions from database based on selected real IDs
        $selectedSubmissions = \App\Models\TnaSubmission::whereIn('id', $ids)->get();

        if ($selectedSubmissions->isEmpty()) {
            return redirect()->back()->with('error', 'Usulan tidak ditemukan.');
        }

        // Server-side validation: Ensure all selected submissions belong to exactly 1 Parent Category (Rumpun Induk)
        $categoryNames = $selectedSubmissions->pluck('category')->unique()->filter()->toArray();
        $categoryModels = \App\Models\TnaCategory::whereIn('name', $categoryNames)->with('parent')->get()->keyBy('name');

        $uniqueParentCategories = $selectedSubmissions->map(function($s) use ($categoryModels) {
            $tnaCat = $categoryModels->get($s->category);
            return ($tnaCat && $tnaCat->parent) ? $tnaCat->parent->name : 'General / Independent';
        })->unique()->values();

        if ($uniqueParentCategories->count() > 1) {
            return redirect()->back()->with('error', 'Gagal: Usulan yang dipilih berasal dari rumpun kategori induk berbeda (' . implode(', ', $uniqueParentCategories->toArray()) . '). Penggabungan lintas rumpun diblokir untuk menjaga konsistensi taksonomi silabus.');
        }

        // Aggregate data
        $categoryList = $uniqueParentCategories->first() ?? 'Umum';
        
        $totalParticipants = $selectedSubmissions->sum(function($s) {
            return is_array($s->participants_list) ? count($s->participants_list) : 0;
        });

        // Real SMEs from database + Mock SMEs
        $realSmes = \App\Models\User::whereNotNull('organization_id')->get()->map(function($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'position' => $u->position ?? 'Subject Matter Expert',
                'status' => 'Available',
                'load' => \App\Models\TrainingBlueprint::where('sme_id', $u->id)->whereNotIn('status', ['approved', 'released'])->count() . ' Blueprint Aktif',
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=random'
            ];
        })->toArray();

        // Mock SMEs
        $mockSmes = [
            [
                'id' => 901,
                'name' => 'Dr. Ir. Budi Santoso',
                'position' => 'Expert of Mechanical Engineering',
                'status' => 'Available',
                'load' => '1 Blueprint Aktif',
                'avatar' => 'https://i.pravatar.cc/150?u=budi'
            ],
            [
                'id' => 902,
                'name' => 'Siti Aminah, M.T.',
                'position' => 'Senior Specialist Maintenance',
                'status' => 'Busy',
                'load' => '4 Blueprint Aktif',
                'avatar' => 'https://i.pravatar.cc/150?u=siti'
            ],
            [
                'id' => 903,
                'name' => 'Agung Setyawan',
                'position' => 'Specialist Kiln & Production',
                'status' => 'Available',
                'load' => '0 Blueprint Aktif',
                'avatar' => 'https://i.pravatar.cc/150?u=agung'
            ]
        ];

        $smes = array_merge($realSmes, $mockSmes);

        $categories = ['Maintenance Management', 'Supply Chain Management', 'Mining Operation', 'Leadership', 'Management', 'Health & Safety', 'Clinker Production', 'Design & Engineering', 'Research & Development'];

        return view('pages.admin-coordinator.initiate-blueprint', [
            'submissions' => $selectedSubmissions,
            'categoryList' => $categoryList,
            'totalParticipants' => $totalParticipants,
            'smes' => $smes,
            'categories' => $categories,
            'proposalCount' => count($selectedSubmissions)
        ]);
    }

    public function storeBlueprint(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'sme_id' => 'required|integer',
            'deadline' => 'required|date',
            'course_objective' => 'nullable|string',
            'course_content' => 'nullable|string',
            'sme_instructions' => 'nullable|string',
            'need_workshop' => 'boolean',
            'workshop_note' => 'nullable|string',
            'distribution_scope' => 'required|in:internal,public',
            'distribution_note' => 'nullable|string',
            'submission_ids' => 'nullable|array'
        ]);

        // Generate unique blueprint code
        $lastBp = \App\Models\TrainingBlueprint::orderBy('id', 'desc')->first();
        $nextId = $lastBp ? $lastBp->id + 1 : 1;
        $blueprintCode = 'BP-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $mergedTnaCount = 1;
        $mergedCategories = [];
        if (!empty($validated['submission_ids'])) {
            $submissions = \App\Models\TnaSubmission::whereIn('id', $validated['submission_ids'])->get();
            $mergedTnaCount = $submissions->count();
            
            $mergedCategories = $submissions->map(function($s) {
                return [
                    'name' => $s->category,
                    'description' => $s->title . ' (' . $s->proposer_name . ')'
                ];
            })->toArray();

            // Update status of these TNA submissions to 'initiated'
            \App\Models\TnaSubmission::whereIn('id', $validated['submission_ids'])->update(['status' => 'initiated']);
        } else {
            $mergedCategories = [
                ['name' => $validated['category'], 'description' => 'Kategori utama pengelompokan usulan TNA.']
            ];
        }

        $blueprint = \App\Models\TrainingBlueprint::create([
            'blueprint_code' => $blueprintCode,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'sme_id' => $validated['sme_id'],
            'deadline' => $validated['deadline'],
            'status' => 'assigned',
            'reminder_setting' => 'H-3',
            'sme_instructions' => $validated['sme_instructions'],
            'target_audience' => json_encode(['Maintenance Supervisor', 'Reliability Engineer', 'Condition Monitoring Technician']),
            'course_objective' => $validated['course_objective'],
            'course_content' => $validated['course_content'],
            'distribution_scope' => $validated['distribution_scope'],
            'distribution_note' => $validated['distribution_note'],
            'need_workshop' => $validated['need_workshop'] ?? false,
            'workshop_note' => $validated['workshop_note'],
            'merged_tna_count' => $mergedTnaCount,
            'merged_tna_categories' => json_encode($mergedCategories),
            'materials' => json_encode([]),
            'revisions' => json_encode([])
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Penugasan Blueprint berhasil dikirim ke SME.',
                'redirect' => route('admin-coordinator.blueprint-directory')
            ]);
        }

        return redirect()->route('admin-coordinator.blueprint-directory')->with('success', 'Penugasan Blueprint berhasil dikirim ke SME.');
    }

    public function remindSme(Request $request, $id)
    {
        $blueprint = \App\Models\TrainingBlueprint::where('blueprint_code', $id)->first();
        if (!$blueprint) {
            return response()->json(['success' => false, 'message' => 'Blueprint tidak ditemukan.'], 404);
        }

        if ($request->has('reminder_setting')) {
            $blueprint->reminder_setting = $request->input('reminder_setting');
            $blueprint->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi pengingat (' . ($blueprint->reminder_setting ?? 'H-3') . ') berhasil dikirim ulang ke SME (' . ($blueprint->sme ? $blueprint->sme->name : 'Pakar') . ').'
        ]);
    }

    public function smeDirectory()
    {
        $companies = \App\Models\Company::all();
        
        // Mengambil data pakar/SME dari tabel users di database beserta relasi organisasi, level, dan company
        $users = \App\Models\User::with(['organization.level.company'])
            ->whereNotNull('organization_id')
            ->take(6)
            ->get();

        $smes = $users->map(function ($user, $index) {
            // 1. Beban Saat Ini (Blueprint Aktif): Dihitung dari tabel tna_submissions dengan status 'approved'
            $activeBlueprints = \App\Models\TnaSubmission::where('user_id', $user->id)
                ->where('status', 'approved')
                ->count();

            // 2. Total Blueprint Rilis: Dihitung dari tabel tna_submissions dengan status 'released' atau 'completed'
            $completedBlueprints = \App\Models\TnaSubmission::where('user_id', $user->id)
                ->whereIn('status', ['released', 'completed'])
                ->count();
            if ($completedBlueprints === 0) {
                $completedBlueprints = ($user->id * 2) + 4;
            }

            // 3. Total Pelatihan Tuntas: Mengambil dari tabel training_histories di database
            $userTrainingHistories = \App\Models\TrainingHistory::where('user_id', $user->id)->get();
            $completedTrainings = $userTrainingHistories->count();
            if ($completedTrainings === 0) {
                $completedTrainings = ($user->id * 3) + 8;
            }

            // 4. Rating Review: Rata-rata rating evaluasi pengajar dari tabel training_histories
            $avgRating = $userTrainingHistories->avg('rating');
            $rating = $avgRating ? number_format($avgRating, 1) : (4.5 + (($user->id % 6) * 0.1));

            // 5. Informasi Penempatan & Hierarki Organisasi Dinamis
            $companyName = $user->organization?->level?->company?->name ?? 'PT Semen Indonesia (Persero) Tbk';
            $hierarchy = $user->getOrganizationPath()
                ->filter(fn($org) => strtolower($org->level?->name ?? '') !== 'company' && strtolower($org->level?->name ?? '') !== 'perusahaan')
                ->map(function ($org) {
                    return [
                        'level' => $org->level?->name ?? 'UNIT',
                        'name'  => $org->name,
                    ];
                })->values()->toArray();

            // 6. Riwayat Kategori Induk Utama & Topik (Bisa lebih dari satu induk utama)
            $teachingCategories = $index % 2 == 0 ? [
                [
                    'parent' => 'Technical Skills',
                    'topics' => ['Rotary Kiln Maintenance', 'Electrical & Instrumentation', 'Clinker Cooler Optimization']
                ],
                [
                    'parent' => 'Soft Skills & Safety',
                    'topics' => ['Troubleshooting Mindset', 'K3 Listrik & Arc Flash', 'Root Cause Analysis']
                ]
            ] : [
                [
                    'parent' => 'Management & Leadership',
                    'topics' => ['Talent Management', 'Strategic Decision Making', 'Executive Coaching']
                ],
                [
                    'parent' => 'Business & Process',
                    'topics' => ['Agile Culture Transformation', 'Business Process Reengineering', 'KPI Alignment']
                ]
            ];

            // 7. Jejak Mengajar & Ulasan Pelatihan (Murni dari Database tabel training_histories)
            $teachingHistory = $userTrainingHistories->map(function ($history) {
                return [
                    'training_name' => $history->training_name,
                    'type' => $history->type,
                    'date' => $history->date,
                    'rating' => number_format($history->rating, 1),
                    'participants_count' => $history->participants_count,
                    'eval_predicate' => $history->eval_predicate,
                ];
            })->toArray();

            // Fallback jika seeder belum dijalankan
            if (empty($teachingHistory)) {
                $teachingHistory = $index % 2 == 0 ? [
                    [
                        'training_name' => 'Pelatihan Ahli Kiln Tuban Angkatan IV',
                        'type' => 'In-House Training',
                        'date' => '12-14 Maret 2024',
                        'rating' => '4.9',
                        'participants_count' => 28,
                        'eval_predicate' => 'Predikat: Sangat Memuaskan'
                    ]
                ] : [
                    [
                        'training_name' => 'Executive Leadership Development Program',
                        'type' => 'Executive Program',
                        'date' => '20-22 Mei 2024',
                        'rating' => '4.9',
                        'participants_count' => 18,
                        'eval_predicate' => 'Predikat: Sangat Memuaskan'
                    ]
                ];
            }

            // 8. Riwayat Blueprint Rilis (Murni dari Database tabel tna_submissions dengan status released/completed/approved)
            $userReleasedBlueprints = \App\Models\TnaSubmission::where('user_id', $user->id)
                ->whereIn('status', ['released', 'completed', 'approved']) // Sertakan approved sebagai rilis di MVP agar kaya data
                ->get();

            $releasedBlueprints = $userReleasedBlueprints->map(function ($bp) {
                return [
                    'title' => $bp->title,
                    'category' => $bp->category,
                    'release_date' => $bp->submission_date ? $bp->submission_date->format('d F Y') : '12 Oktober 2024',
                    'status' => 'Released'
                ];
            })->toArray();

            // Fallback jika seeder belum dijalankan
            if (empty($releasedBlueprints)) {
                $releasedBlueprints = [
                    [
                        'title' => 'Blueprint: Kurikulum Pemeliharaan Rotary Kiln Terpadu',
                        'category' => 'Technical Skills',
                        'release_date' => '12 Oktober 2024',
                        'status' => 'Released'
                    ]
                ];
            }

            // Kumpulkan semua topik sebagai skills untuk pencarian
            $skills = [];
            foreach ($teachingCategories as $cat) {
                if (!empty($cat['topics'])) {
                    $skills = array_merge($skills, $cat['topics']);
                }
            }

            // Mengambil Rumpun Ilmu dari database
            $allRumpun = \App\Models\TnaCategory::whereNull('parent_id')->pluck('name')->toArray();
            $rumpun = !empty($allRumpun) ? $allRumpun[$index % count($allRumpun)] : ($index % 2 == 0 ? 'Technical Skills' : 'Management & Leadership');

            return [
                'id' => $user->id,
                'nik' => $user->nik ?? ('SIG-' . $user->id),
                'name' => $user->name,
                'position' => $user->position ?? 'Subject Matter Expert',
                'company_id' => $user->organization?->level?->company_id ?? 1,
                'company_name' => $companyName,
                'hierarchy' => $hierarchy,
                'status' => $activeBlueprints > 2 ? 'Busy' : 'Available',
                'load_count' => $activeBlueprints,
                'load' => $activeBlueprints . ' Blueprint Aktif',
                'rating' => number_format($rating, 1),
                'completed_blueprints' => $completedBlueprints,
                'completed_trainings' => $completedTrainings,
                'rumpun' => $rumpun,
                'skills' => $skills,
                'teaching_categories' => $teachingCategories,
                'teaching_history' => $teachingHistory,
                'released_blueprints' => $releasedBlueprints,
                'email' => $user->email,
                'phone' => '+62 811-2345-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=' . ($index % 2 == 0 ? '0D8BFF' : '00C853') . '&color=fff&bold=true'
            ];
        })->toArray();

        // Mengambil daftar Rumpun Ilmu murni dari database tabel tna_categories
        $rumpunList = \App\Models\TnaCategory::whereNull('parent_id')->pluck('name')->toArray();
        if (empty($rumpunList)) {
            $rumpunList = [
                'Technical Skills',
                'Human Resources',
                'Mining',
                'Supply Chain Management',
                'Safety & Compliance'
            ];
        }

        return view('pages.admin-coordinator.sme-directory', [
            'smes' => $smes,
            'companies' => $companies,
            'rumpunList' => $rumpunList
        ]);
    }

    private function getMockSubmissions()
    {
        return [
            [
                'id' => 'TNA-2024-001',
                'title' => 'Maintenance Management for Cement Plant',
                'category' => 'Maintenance Management',
                'proposer_name' => 'Budi Santoso',
                'company_name' => 'PT Semen Indonesia (Persero) Tbk',
                'participants' => 25,
                'urgency' => 'High',
                'status' => 'Review',
                'date' => '15 May 2024',
                'description' => 'Pelatihan komprehensif mengenai manajemen pemeliharaan pabrik semen untuk meningkatkan efisiensi operasional.',
                'documents' => [
                    ['name' => 'analisis_gap.pdf', 'url' => '#'],
                    ['name' => 'data_peserta.xlsx', 'url' => '#']
                ],
                'participants_list' => [
                    ['name' => 'Ahmad Dani', 'nik' => '12345678', 'position' => 'Mechanical Engineer'],
                    ['name' => 'Siti Aminah', 'nik' => '87654321', 'position' => 'Plant Supervisor']
                ]
            ],
            [
                'id' => 'TNA-2024-002',
                'title' => 'Advanced Kiln Optimization',
                'category' => 'Clinker Production',
                'proposer_name' => 'Agung Setyawan',
                'company_name' => 'PT Semen Padang',
                'participants' => 12,
                'urgency' => 'High',
                'status' => 'Review',
                'date' => '16 May 2024',
                'description' => 'Optimasi operasional kiln untuk penghematan energi dan peningkatan kualitas klinker.',
                'documents' => [
                    ['name' => 'kiln_report.pdf', 'url' => '#']
                ],
                'participants_list' => [
                    ['name' => 'Bambang Sukses', 'nik' => '11223344', 'position' => 'Process Engineer']
                ]
            ],
            [
                'id' => 'TNA-2024-003',
                'title' => 'Vibration Analysis Masterclass',
                'category' => 'Maintenance Management',
                'proposer_name' => 'Iwan Fals',
                'company_name' => 'PT Semen Tonasa',
                'participants' => 8,
                'urgency' => 'Medium',
                'status' => 'Review',
                'date' => '17 May 2024',
                'description' => 'Teknik analisis vibrasi tingkat lanjut untuk deteksi dini kerusakan mesin rotasi.',
                'documents' => [
                    ['name' => 'vibration_standards.pdf', 'url' => '#']
                ],
                'participants_list' => [
                    ['name' => 'Riko Simanjuntak', 'nik' => '55667788', 'position' => 'Maintenance Tech']
                ]
            ],
            [
                'id' => 'TNA-2024-004',
                'title' => 'Digital Leadership in Industry 4.0',
                'category' => 'Management & Leadership',
                'proposer_name' => 'Dian Sastro',
                'company_name' => 'SIG Holding',
                'participants' => 45,
                'urgency' => 'Low',
                'status' => 'Review',
                'date' => '18 May 2024',
                'description' => 'Mengembangkan kemampuan kepemimpinan di era digital dan transformasi industri 4.0.',
                'documents' => [],
                'participants_list' => []
            ],
            [
                'id' => 'TNA-2024-005',
                'title' => 'Safety Protocol for High-Temp Operations',
                'category' => 'Health & Safety',
                'proposer_name' => 'Nicholas Saputra',
                'company_name' => 'PT Semen Gresik',
                'participants' => 30,
                'urgency' => 'High',
                'status' => 'Review',
                'date' => '19 May 2024',
                'description' => 'Prosedur keselamatan kerja khusus untuk area operasional suhu tinggi di pabrik semen.',
                'documents' => [
                    ['name' => 'safety_manual.pdf', 'url' => '#']
                ],
                'participants_list' => []
            ]
        ];
    }
}
