<?php

namespace App\Http\Controllers;

use App\Models\TrainingBlueprint;
use App\Models\CurriculumDraft;
use Illuminate\Http\Request;

class SmeController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // Ambil blueprint yang ditugaskan ke SME saat ini
        $blueprints = TrainingBlueprint::with('sme')
            ->where('sme_id', $user ? $user->id : 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // Jika kosong (misal demo dengan user lain), tampilkan semua blueprint agar tetap kaya
        if ($blueprints->isEmpty()) {
            $blueprints = TrainingBlueprint::with('sme')->orderBy('created_at', 'desc')->get();
        }

        $totalAssigned = $blueprints->count();
        $waitingReview = $blueprints->where('status', 'assigned_to_sme')->count();
        $validatedCount = $blueprints->whereIn('status', ['studio_production', 'approved', 'released'])->count();
        $pendingApprovalCount = $blueprints->whereIn('status', ['material_submitted', 'revision_required'])->count();

        return view('pages.sme.index', [
            'blueprints' => $blueprints,
            'totalAssigned' => $totalAssigned,
            'waitingReview' => $waitingReview,
            'validatedCount' => $validatedCount,
            'pendingApprovalCount' => $pendingApprovalCount
        ]);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $perPage = $request->input('per_page', 5);
        $sortCol = $request->input('sort', 'created_at');
        $sortDir = $request->input('dir', 'desc');
        
        $search = $request->input('search');
        $category = $request->input('category');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = TrainingBlueprint::with('sme');
        if ($user && TrainingBlueprint::where('sme_id', $user->id)->exists()) {
            $query->where('sme_id', $user->id);
        }

        // Apply Search Query (title or id)
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        // Apply Category Filter
        if (!empty($category) && $category !== 'all') {
            $query->where('category', $category);
        }

        // Apply Date Range Filter (deadline)
        if (!empty($startDate)) {
            $query->where('deadline', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->where('deadline', '<=', $endDate);
        }

        // Pastikan kolom yang di-sort valid untuk mencegah SQL injection
        $allowedSorts = ['id', 'title', 'category', 'status', 'deadline', 'created_at'];
        if (in_array($sortCol, $allowedSorts)) {
            $query->orderBy($sortCol, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $blueprints = $query->paginate($perPage)->withQueryString();

        // Ambil daftar kategori unik untuk dropdown filter dari seluruh data (bukan cuma halaman aktif)
        $categories = TrainingBlueprint::select('category')->distinct()->pluck('category');

        return view('pages.sme.list', [
            'blueprints' => $blueprints,
            'categories' => $categories
        ]);
    }

    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search', '');
        $category = $request->input('category', 'all');
        $smeId = $user ? $user->id : 0;

        $fileName = 'Daftar_Penugasan_Blueprint_' . date('Ymd_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BlueprintExport($search, $category, $smeId), $fileName);
    }

    public function revisionList()
    {
        $user = auth()->user();
        $blueprints = TrainingBlueprint::with('sme')
            ->where('sme_id', $user ? $user->id : 0)
            ->where('status', 'revision_required')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($blueprints->isEmpty()) {
            $blueprints = TrainingBlueprint::with('sme')
                ->where('status', 'revision_required')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('pages.sme.list-revision', [
            'blueprints' => $blueprints
        ]);
    }

    public function masterclassIndex()
    {
        $user = auth()->user();
        $blueprints = TrainingBlueprint::with('sme')
            ->where('sme_id', $user ? $user->id : 0)
            ->whereIn('status', ['studio_production', 'curriculum_submitted'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($blueprints->isEmpty()) {
            $blueprints = TrainingBlueprint::with('sme')
                ->whereIn('status', ['studio_production', 'curriculum_submitted'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('pages.sme.list-masterclass', [
            'blueprints' => $blueprints
        ]);
    }

    public function validatedIndex()
    {
        $user = auth()->user();
        $blueprints = TrainingBlueprint::with('sme')
            ->where('sme_id', $user ? $user->id : 0)
            ->whereIn('status', ['approved', 'released'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($blueprints->isEmpty()) {
            $blueprints = TrainingBlueprint::with('sme')
                ->whereIn('status', ['approved', 'released'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('pages.sme.list-validated', [
            'blueprints' => $blueprints
        ]);
    }

    public function showBlueprint($id)
    {
        $blueprint = TrainingBlueprint::with('sme')->where('id', $id)->firstOrFail();

        return view('pages.sme.review', [
            'blueprint' => $blueprint
        ]);
    }

    public function submitMaterial(Request $request, $id)
    {
        $blueprint = TrainingBlueprint::where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'material_notes' => 'required|string',
            'file_name' => 'nullable|string'
        ]);

        $materials = is_string($blueprint->sme_submitted_materials) ? json_decode($blueprint->sme_submitted_materials, true) : ($blueprint->sme_submitted_materials ?? []);
        
        $materials[] = [
            'notes' => $validated['material_notes'],
            'file_name' => $validated['file_name'] ?? 'Materi_Pelatihan_SIG.pdf',
            'submitted_at' => now()->toIso8601String(),
            'submitted_by' => auth()->user() ? auth()->user()->name : 'Subject Matter Expert'
        ];

        $blueprint->sme_submitted_materials = $materials;
        $blueprint->sme_submission_notes = $validated['material_notes']; // Menyimpan catatan penyerahan revisi secara langsung (Gap 2 Solution)
        $blueprint->status = 'material_submitted'; // Berubah status menjadi menunggu persetujuan Admin
        $blueprint->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Materi berhasil dikirim ke Admin Coordinator untuk di-review.',
                'redirect' => route('sme.dashboard')
            ]);
        }

        return redirect()->route('sme.dashboard')->with('success', 'Materi berhasil dikirim ke Admin Coordinator untuk di-review.');
    }

    public function masterclassCurriculum($id)
    {
        $blueprint = TrainingBlueprint::with('sme')->where('id', $id)->firstOrFail();

        return view('pages.sme.masterclass-curriculum', [
            'blueprint' => $blueprint
        ]);
    }

    public function saveCurriculumDraft(Request $request, $id)
    {
        $blueprint = TrainingBlueprint::where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'curriculum_structure' => 'required|array'
        ]);

        $blueprint->curriculum_structure = $validated['curriculum_structure'];
        $blueprint->save();

        return response()->json([
            'success' => true,
            'message' => 'Draft struktur kurikulum Masterclass berhasil disimpan.',
            'data' => $blueprint->curriculum_structure
        ]);
    }

    public function submitFinalCurriculum(Request $request, $id)
    {
        $blueprint = TrainingBlueprint::where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'curriculum_structure' => 'required|array'
        ]);

        $blueprint->curriculum_structure = $validated['curriculum_structure'];
        $blueprint->status = 'curriculum_submitted'; // Menunggu Pagar Kedua dari Learning Administrator
        $blueprint->save();

        return response()->json([
            'success' => true,
            'message' => 'Kurikulum Masterclass final berhasil dikirim ke Learning Administrator untuk ditinjau.',
            'redirect' => route('sme.dashboard')
        ]);
    }

    public function saveQuizDraft(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'chapter_id' => 'nullable|string',
                'payload' => 'required|array'
            ]);

            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            if ($request->hasSession()) {
                $request->session()->put('last_draft_saved_at', now()->toDateTimeString());
            }

            $draft = CurriculumDraft::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'blueprint_id' => $id,
                    'chapter_id' => $validated['chapter_id'] ?? null,
                ],
                [
                    'payload' => $validated['payload'],
                    'last_saved_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Draf evaluasi kuis berhasil diamankan di cloud.',
                'last_saved_at' => $draft->last_saved_at->format('H:i')
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Save Quiz Draft Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan draf: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getQuizDraft(Request $request, $id)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $chapterId = $request->query('chapter_id');

            $draft = CurriculumDraft::where('user_id', $user->id)
                ->where('blueprint_id', $id)
                ->where('chapter_id', $chapterId)
                ->first();

            if (!$draft) {
                return response()->json(['success' => true, 'has_draft' => false]);
            }

            return response()->json([
                'success' => true,
                'has_draft' => true,
                'draft' => [
                    'payload' => $draft->payload,
                    'last_saved_at' => $draft->last_saved_at ? $draft->last_saved_at->format('d M Y, H:i') : null
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Get Quiz Draft Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil draf: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveVideoDraft(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'chapter_id' => 'nullable|string',
                'payload' => 'required|array'
            ]);

            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            if ($request->hasSession()) {
                $request->session()->put('last_video_draft_saved_at', now()->toDateTimeString());
            }

            $draft = CurriculumDraft::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'blueprint_id' => $id,
                    'chapter_id' => $validated['chapter_id'] ?? null,
                ],
                [
                    'payload' => $validated['payload'],
                    'last_saved_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Draf materi video berhasil diamankan di cloud.',
                'last_saved_at' => $draft->last_saved_at->format('H:i')
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Save Video Draft Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan draf video: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getVideoDraft(Request $request, $id)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $chapterId = $request->query('chapter_id');

            $draft = CurriculumDraft::where('user_id', $user->id)
                ->where('blueprint_id', $id)
                ->where('chapter_id', $chapterId)
                ->first();

            if (!$draft) {
                return response()->json(['success' => true, 'has_draft' => false]);
            }

            return response()->json([
                'success' => true,
                'has_draft' => true,
                'draft' => [
                    'payload' => $draft->payload,
                    'last_saved_at' => $draft->last_saved_at ? $draft->last_saved_at->format('d M Y, H:i') : null
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Get Video Draft Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil draf video: ' . $e->getMessage()
            ], 500);
        }
    }
}


