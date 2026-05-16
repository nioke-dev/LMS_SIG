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

        // Map database records to the structure expected by the frontend
        $submissions = $realSubmissions->map(function ($s) use ($participantDetails) {
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

            return [
                'id' => $s->id,
                'title' => $s->title,
                'category' => $s->category,
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

    public function blueprintDirectory()
    {
        $blueprints = [
            [
                'id' => 'CUR-2024-001',
                'title' => 'Vibration Analysis Masterclass',
                'category' => 'Maintenance Management',
                'sme' => [
                    'name' => 'Dr. Ir. Budi Santoso',
                    'avatar' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=random'
                ],
                'deadline' => '15 Des 2024',
                'status' => 'IN PROGRESS (DRAFTING)',
                'status_type' => 'drafting', // gold
                'description' => 'Pelatihan tingkat lanjut mengenai teknik analisis vibrasi untuk deteksi dini kerusakan mesin rotasi.'
            ],
            [
                'id' => 'CUR-2024-042',
                'title' => 'Supply Chain Optimization Strategy',
                'category' => 'Supply Chain Management',
                'sme' => [
                    'name' => 'Rendra Wijaya',
                    'avatar' => 'https://ui-avatars.com/api/?name=Rendra+Wijaya&background=random'
                ],
                'deadline' => '20 Jan 2025',
                'status' => 'PENDING APPROVAL',
                'status_type' => 'pending', // blue
                'description' => 'Strategi optimasi rantai pasokan untuk meningkatkan efisiensi distribusi produk semen.'
            ],
            [
                'id' => 'CUR-2024-019',
                'title' => 'Safety & Risk Management in Mining',
                'category' => 'Mining Operation',
                'sme' => [
                    'name' => 'Agus Hermawan',
                    'avatar' => 'https://ui-avatars.com/api/?name=Agus+Hermawan&background=random'
                ],
                'deadline' => '10 Feb 2025',
                'status' => 'APPROVED & READY',
                'status_type' => 'approved', // green
                'description' => 'Manajemen risiko dan protokol keselamatan kerja khusus untuk operasional tambang.'
            ],
            [
                'id' => 'CUR-2024-088',
                'title' => 'Digital Leadership & Transformation',
                'category' => 'Leadership',
                'sme' => [
                    'name' => 'Siti Aminah',
                    'avatar' => 'https://ui-avatars.com/api/?name=Siti+Aminah&background=random'
                ],
                'deadline' => '05 Mar 2025',
                'status' => 'IN PROGRESS (DRAFTING)',
                'status_type' => 'drafting',
                'description' => 'Membangun kemampuan kepemimpinan di era digital dan transformasi industri.'
            ]
        ];

        $stats = [
            'total_blueprints' => 124,
            'active_smes' => 48,
            'avg_completion' => 82
        ];

        $categories = ['Maintenance Management', 'Supply Chain Management', 'Mining Operation', 'Leadership', 'Management'];

        return view('pages.admin-coordinator.blueprint-directory', [
            'blueprints' => $blueprints,
            'stats' => $stats,
            'categories' => $categories
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

        // Aggregate data
        $categories = $selectedSubmissions->pluck('kategori')->unique()->filter()->toArray();
        $categoryList = implode(', ', $categories);
        
        $totalParticipants = $selectedSubmissions->sum(function($s) {
            return is_array($s->participants_list) ? count($s->participants_list) : 0;
        });

        // Mock SMEs
        $smes = [
            [
                'id' => 1,
                'name' => 'Dr. Ir. Budi Santoso',
                'position' => 'Expert of Mechanical Engineering',
                'status' => 'Available',
                'load' => '1 Blueprint Aktif',
                'avatar' => 'https://i.pravatar.cc/150?u=budi'
            ],
            [
                'id' => 2,
                'name' => 'Siti Aminah, M.T.',
                'position' => 'Senior Specialist Maintenance',
                'status' => 'Busy',
                'load' => '4 Blueprint Aktif',
                'avatar' => 'https://i.pravatar.cc/150?u=siti'
            ],
            [
                'id' => 3,
                'name' => 'Agung Setyawan',
                'position' => 'Specialist Kiln & Production',
                'status' => 'Available',
                'load' => '0 Blueprint Aktif',
                'avatar' => 'https://i.pravatar.cc/150?u=agung'
            ]
        ];

        return view('pages.admin-coordinator.initiate-blueprint', [
            'submissions' => $selectedSubmissions,
            'categoryList' => $categoryList,
            'totalParticipants' => $totalParticipants,
            'smes' => $smes,
            'proposalCount' => count($selectedSubmissions)
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
