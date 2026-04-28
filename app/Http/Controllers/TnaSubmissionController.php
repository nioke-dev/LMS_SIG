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
            ]);
        } else {
            // Optional fields for draft
            $rules = array_merge($rules, [
                'category' => 'nullable|string',
                'urgency' => 'nullable|string',
                'description' => 'nullable|string',
                'participants_count' => 'nullable|integer',
                'participants_list' => 'nullable|array',
            ]);
        }

        $validated = $request->validate($rules);

        try {
            $submissionDto = $this->tnaService->storeSubmission($validated);

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
            ]);
        } else {
            $rules = array_merge($rules, [
                'category' => 'nullable|string',
                'urgency' => 'nullable|string',
                'description' => 'nullable|string',
                'participants_count' => 'nullable|integer',
                'participants_list' => 'nullable|array',
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
}
