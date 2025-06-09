<?php

namespace App\Generated\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;

class SearchSuggestions extends Component
{
    public string $query = '';
    public bool $showSuggestions = false;
    public array $suggestions = [];
    public int $selectedIndex = -1;

    protected $listeners = ['hideSearchSuggestions' => 'hideSuggestions'];

    public function updatedQuery()
    {
        if (strlen($this->query) >= 1) {
            $this->loadSuggestions();
            $this->showSuggestions = true;
            $this->selectedIndex = -1;
        } else {
            $this->hideSuggestions();
        }
    }

    public function loadSuggestions()
    {
        $suggestions = collect();

        // Search in posts (if blog module is enabled)
        if (class_exists('\App\Generated\Models\Post')) {
            $posts = \App\Generated\Models\Post::where('title', 'like', "%{$this->query}%")
                ->where('is_published', true)
                ->limit(5)
                ->get(['id', 'title', 'slug', 'featured_image'])
                ->map(function ($post) {
                    return [
                        'type' => 'post',
                        'title' => $post->title,
                        'url' => route('posts.show', $post->slug),
                        'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
                        'icon' => 'fas fa-newspaper',
                    ];
                });
            $suggestions = $suggestions->merge($posts);
        }

        // Search in products (if ecommerce module is enabled)
        if (class_exists('\App\Generated\Models\Product')) {
            $products = \App\Generated\Models\Product::where('name', 'like', "%{$this->query}%")
                ->where('is_active', true)
                ->limit(5)
                ->get(['id', 'name', 'slug', 'featured_image'])
                ->map(function ($product) {
                    return [
                        'type' => 'product',
                        'title' => $product->name,
                        'url' => route('products.show', $product->slug),
                        'image' => $product->featured_image ? asset('storage/' . $product->featured_image) : null,
                        'icon' => 'fas fa-shopping-cart',
                    ];
                });
            $suggestions = $suggestions->merge($products);
        }

        // Search in staff (if staff module is enabled)
        if (class_exists('\App\Generated\Models\Staff')) {
            $staff = \App\Generated\Models\Staff::where('name', 'like', "%{$this->query}%")
                ->where('is_active', true)
                ->limit(3)
                ->get(['id', 'name', 'slug', 'avatar'])
                ->map(function ($member) {
                    return [
                        'type' => 'staff',
                        'title' => $member->name,
                        'url' => route('staff.show', $member->slug),
                        'image' => $member->avatar ? asset('storage/' . $member->avatar) : null,
                        'icon' => 'fas fa-user',
                    ];
                });
            $suggestions = $suggestions->merge($staff);
        }

        // Search in menu items
        $menuItems = \App\Generated\Models\MenuItem::where('title', 'like', "%{$this->query}%")
            ->where('is_active', true)
            ->limit(3)
            ->get(['id', 'title', 'url', 'route_name', 'icon'])
            ->map(function ($item) {
                return [
                    'type' => 'page',
                    'title' => $item->title,
                    'url' => $item->full_url,
                    'image' => null,
                    'icon' => $item->icon ?: 'fas fa-link',
                ];
            });
        $suggestions = $suggestions->merge($menuItems);

        $this->suggestions = $suggestions->take(10)->toArray();
    }

    public function selectSuggestion($index)
    {
        if (isset($this->suggestions[$index])) {
            $suggestion = $this->suggestions[$index];
            $this->redirect($suggestion['url']);
        }
    }

    public function navigateUp()
    {
        if ($this->selectedIndex > 0) {
            $this->selectedIndex--;
        }
    }

    public function navigateDown()
    {
        if ($this->selectedIndex < count($this->suggestions) - 1) {
            $this->selectedIndex++;
        }
    }

    public function selectCurrent()
    {
        if ($this->selectedIndex >= 0 && isset($this->suggestions[$this->selectedIndex])) {
            $this->selectSuggestion($this->selectedIndex);
        } else {
            // Perform search
            $this->performSearch();
        }
    }

    public function performSearch()
    {
        if (!empty($this->query)) {
            $this->redirect(route('search', ['q' => $this->query]));
        }
    }

    public function hideSuggestions()
    {
        $this->showSuggestions = false;
        $this->selectedIndex = -1;
    }

    public function render()
    {
        return view('livewire.generated.search-suggestions');
    }
}
