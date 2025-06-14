<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

class ServiceIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $priceRange = '';
    public $featured = false;
    public $sort = 'order';
    public $perPage = 12;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'priceRange' => ['except' => ''],
        'featured' => ['except' => false],
        'sort' => ['except' => 'order'],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->category = request('category', '');
        $this->priceRange = request('price_range', '');
        $this->featured = request('featured', false);
        $this->sort = request('sort', 'order');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedPriceRange()
    {
        $this->resetPage();
    }

    public function updatedFeatured()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'category', 'priceRange', 'featured', 'sort']);
        $this->resetPage();
    }

    public function getServicesProperty()
    {
        $query = Service::with(['images'])
            ->where('status', 'active');

        // Filter by category
        if ($this->category) {
            $query->where('category', $this->category);
        }

        // Filter by price range
        if ($this->priceRange) {
            if ($this->priceRange === '100000000+') {
                $query->where('price', '>=', 100000000);
            } else {
                $ranges = explode('-', $this->priceRange);
                if (count($ranges) === 2) {
                    $query->whereBetween('price', [(int)$ranges[0], (int)$ranges[1]]);
                }
            }
        }

        // Search by name, description, or category
        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter featured services
        if ($this->featured) {
            $query->where('is_featured', true);
        }

        // Sort
        switch ($this->sort) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'price_low':
                $query->orderByRaw('CASE WHEN price IS NULL THEN 1 ELSE 0 END, price ASC');
                break;
            case 'price_high':
                $query->orderByRaw('CASE WHEN price IS NULL THEN 1 ELSE 0 END, price DESC');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('order', 'asc');
                break;
            default: // order
                $query->orderBy('order', 'asc')->orderBy('name', 'asc');
                break;
        }

        return $query->paginate($this->perPage);
    }

    public function getCategoriesProperty()
    {
        return Cache::remember('service_categories_active', 3600, function() {
            $activeCategories = Service::where('status', 'active')
                ->whereNotNull('category')
                ->select('category')
                ->distinct()
                ->pluck('category')
                ->filter()
                ->values();

            $allCategories = Service::getCategories();
            
            return $activeCategories->mapWithKeys(function($category) use ($allCategories) {
                return [$category => $allCategories[$category] ?? $category];
            })->sort();
        });
    }

    public function getPriceRangesProperty()
    {
        return Service::getPriceRanges();
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
            'price_low' => 'Giá thấp → cao',
            'price_high' => 'Giá cao → thấp',
            'featured' => 'Nổi bật trước',
            'newest' => 'Mới nhất',
        ];
    }

    public function getFeaturedServicesProperty()
    {
        return Cache::remember('featured_services_sidebar', 1800, function() {
            return Service::where('status', 'active')
                ->where('is_featured', true)
                ->with(['images'])
                ->ordered()
                ->limit(3)
                ->get();
        });
    }

    public function render()
    {
        return view('livewire.service-index', [
            'services' => $this->services,
            'categories' => $this->categories,
            'priceRanges' => $this->priceRanges,
            'sortOptions' => $this->sortOptions,
            'featuredServices' => $this->featuredServices,
            'globalSettings' => $this->globalSettings,
        ]);
    }
}
