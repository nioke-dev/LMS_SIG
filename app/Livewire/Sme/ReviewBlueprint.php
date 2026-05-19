<?php

namespace App\Livewire\Sme;

use App\Models\TrainingBlueprint;
use App\Models\TnaSubmission;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReviewBlueprint extends Component
{
    use WithFileUploads;

    public TrainingBlueprint $blueprint;
    public $mergedSubmissions = [];
    public $materialNotes = '';
    public $materialFile;
    public $fileName = '';

    public function mount(TrainingBlueprint $blueprint)
    {
        $this->blueprint = $blueprint;
        $tnaIds = is_array($blueprint->tna_submission_ids) ? $blueprint->tna_submission_ids : (json_decode($blueprint->tna_submission_ids, true) ?? []);
        $this->mergedSubmissions = TnaSubmission::whereIn('id', $tnaIds)->get();
    }

    public function updatedMaterialFile()
    {
        $this->validate([
            'materialFile' => 'required|file|max:51200', // max 50MB
        ]);
        $this->fileName = $this->materialFile->getClientOriginalName();
    }

    public function submitMaterial()
    {
        $this->validate([
            'materialNotes' => 'required|string',
            'materialFile' => 'nullable|file|max:51200',
        ]);

        $materials = is_string($this->blueprint->sme_submitted_materials) ? json_decode($this->blueprint->sme_submitted_materials, true) : ($this->blueprint->sme_submitted_materials ?? []);

        // Ambil nama file dari properti atau file upload
        $finalFileName = $this->fileName ?: 'Materi_Pelatihan_SIG.pdf';
        if ($this->materialFile) {
            $finalFileName = $this->materialFile->getClientOriginalName();
            // Simpan ke storage jika diperlukan (opsional, untuk demo/simulasi cukup nama file)
            // $this->materialFile->storeAs('materials', $finalFileName, 'public');
        }

        $materials[] = [
            'notes' => $this->materialNotes,
            'file_name' => $finalFileName,
            'submitted_at' => now()->toIso8601String(),
            'submitted_by' => auth()->user() ? auth()->user()->name : 'Subject Matter Expert'
        ];

        $this->blueprint->sme_submitted_materials = $materials;
        $this->blueprint->sme_submission_notes = $this->materialNotes;
        $this->blueprint->status = 'material_submitted'; // Berubah status menjadi menunggu persetujuan Admin
        $this->blueprint->save();

        session()->flash('success', 'Materi berhasil dikirim ke Admin Coordinator untuk di-review.');

        return $this->redirect(route('sme.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.sme.review-blueprint');
    }
}
