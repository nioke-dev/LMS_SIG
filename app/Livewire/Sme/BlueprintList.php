<?php

namespace App\Livewire\Sme;

use App\Models\TrainingBlueprint;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class BlueprintList extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $category = 'all';

    #[Url(history: true)]
    public $startDate = '';

    #[Url(history: true)]
    public $endDate = '';

    #[Url(history: true)]
    public $sortOrder = 'asc';

    #[Url(history: true)]
    public $perPage = 5;

    #[Url(history: true)]
    public $sortCol = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingSortOrder($value)
    {
        $this->sortCol = 'deadline';
        $this->sortDir = $value;
        $this->resetPage();
    }

    public function sortBy($column)
    {
        $allowedSorts = ['title', 'category', 'status', 'deadline', 'created_at'];
        if (!in_array($column, $allowedSorts)) return;

        if ($this->sortCol === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortCol = $column;
            $this->sortDir = 'asc';
        }

        if ($column === 'deadline') {
            $this->sortOrder = $this->sortDir;
        }

        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        $query = TrainingBlueprint::with('sme');
        if ($user && TrainingBlueprint::where('sme_id', $user->id)->exists()) {
            $query->where('sme_id', $user->id);
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->category) && $this->category !== 'all') {
            $query->where('category', $this->category);
        }

        if (!empty($this->startDate)) {
            $query->where('deadline', '>=', $this->startDate);
        }
        if (!empty($this->endDate)) {
            $query->where('deadline', '<=', $this->endDate);
        }

        // Gunakan sortCol dan sortDir dari klik tabel / dropdown
        $allowedSorts = ['id', 'title', 'category', 'status', 'deadline', 'created_at'];
        if (in_array($this->sortCol, $allowedSorts)) {
            $query->orderBy($this->sortCol, $this->sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $blueprints = $query->paginate($this->perPage);

        $categories = TrainingBlueprint::select('category')->distinct()->pluck('category');

        return view('livewire.sme.blueprint-list', [
            'blueprints' => $blueprints,
            'categories' => $categories
        ]);
    }
}
