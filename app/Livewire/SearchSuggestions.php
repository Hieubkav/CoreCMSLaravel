<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Product;

class SearchSuggestions extends Component
{
    public string $query = '';
    public bool $showSuggestions = false;
    public array $suggestions = [];
    public int $selectedIndex = -1;

    protected $listeners = ['hideSearch' => 'hideSuggestions'];

    /**
     * Updated query - tìm kiếm khi gõ từ ký tự đầu tiên
     */
    public function updatedQuery()
    {
        if (strlen($this->query) >= 1) {
            $this->searchSuggestions();
            $this->showSuggestions = true;
            $this->selectedIndex = -1;
        } else {
            $this->hideSuggestions();
        }
    }

    /**
     * Tìm kiếm suggestions
     */
    private function searchSuggestions()
    {
        $posts = Post::where('title', 'like', "%{$this->query}%")
                    ->orWhere('content', 'like', "%{$this->query}%")
                    ->where('status', 'active')
                    ->published()
                    ->with('category')
                    ->limit(8)
                    ->get();

        $this->suggestions = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'excerpt' => $post->excerpt,
                'url' => route('posts.show', $post->slug),
                'thumbnail' => $post->featured_image_url,
                'category' => $post->category?->name,
                'type' => 'post'
            ];
        })->toArray();

        // Thêm products
        $this->addProductSuggestions();
    }

    /**
     * Thêm product suggestions
     */
    private function addProductSuggestions()
    {
        $products = Product::where('name', 'like', "%{$this->query}%")
                    ->orWhere('short_description', 'like', "%{$this->query}%")
                    ->where('status', 'active')
                    ->with('category')
                    ->limit(5)
                    ->get();

        $productSuggestions = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->name,
                'excerpt' => $product->short_description,
                'url' => route('products.show', $product->slug),
                'thumbnail' => $product->featured_image_url,
                'category' => $product->category?->name,
                'type' => 'product',
                'price' => number_format($product->display_price) . 'đ'
            ];
        })->toArray();

        $this->suggestions = array_merge($this->suggestions, $productSuggestions);
    }

    /**
     * Ẩn suggestions
     */
    public function hideSuggestions()
    {
        $this->showSuggestions = false;
        $this->suggestions = [];
        $this->selectedIndex = -1;
    }

    /**
     * Chọn suggestion
     */
    public function selectSuggestion($index)
    {
        if (isset($this->suggestions[$index])) {
            $suggestion = $this->suggestions[$index];
            return redirect($suggestion['url']);
        }
    }

    /**
     * Navigate với keyboard
     */
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

    /**
     * Enter để chọn suggestion được highlight
     */
    public function selectHighlighted()
    {
        if ($this->selectedIndex >= 0 && isset($this->suggestions[$this->selectedIndex])) {
            $suggestion = $this->suggestions[$this->selectedIndex];
            return redirect($suggestion['url']);
        } else {
            // Nếu không có suggestion nào được chọn, redirect đến trang search
            return redirect()->route('search', ['q' => $this->query]);
        }
    }

    /**
     * Clear search
     */
    public function clearSearch()
    {
        $this->query = '';
        $this->hideSuggestions();
    }

    public function render()
    {
        return view('livewire.search-suggestions');
    }
}
