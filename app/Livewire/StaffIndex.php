<?php

namespace App\Livewire;

use App\Models\Staff;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

class StaffIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $position = '';
    public $sort = 'order';
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'position' => ['except' => ''],
        'sort' => ['except' => 'order'],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->position = request('position', '');
        $this->sort = request('sort', 'order');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPosition()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'position', 'sort']);
        $this->resetPage();
    }

    public function getStaffProperty()
    {
        $query = Staff::with(['images'])
            ->where('status', 'active');

        // Filter by position
        if ($this->position) {
            $query->where('position', $this->position);
        }

        // Search by name, position, or description
        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        switch ($this->sort) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'position':
                $query->orderBy('position', 'asc')->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default: // order
                $query->orderBy('order', 'asc')->orderBy('name', 'asc');
                break;
        }

        return $query->paginate($this->perPage);
    }

    public function getPositionsProperty()
    {
        return Cache::remember('staff_positions_active', 3600, function() {
            return Staff::where('status', 'active')
                ->whereNotNull('position')
                ->select('position')
                ->distinct()
                ->orderBy('position')
                ->pluck('position')
                ->filter()
                ->values();
        });
    }

    public function getGlobalSettingsProperty()
    {
        return Cache::remember('global_settings', 3600, function () {
            try {
                return Setting::first();
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    public function getSortOptionsProperty()
    {
        return [
            'order' => 'Thứ tự',
            'name' => 'Tên A-Z',
            'position' => 'Chức vụ',
            'newest' => 'Mới nhất',
        ];
    }

    public function render()
    {
        return view('livewire.staff-index', [
            'staff' => $this->staff,
            'positions' => $this->positions,
            'sortOptions' => $this->sortOptions,
            'globalSettings' => $this->globalSettings,
        ]);
    }
}
