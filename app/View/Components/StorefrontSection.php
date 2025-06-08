<?php

namespace App\View\Components;

use Illuminate\View\Component;

/**
 * Simple Storefront Section Component
 * 
 * Standardize tất cả sections với:
 * 1. Consistent styling
 * 2. Animation classes
 * 3. Fallback UI
 */
class StorefrontSection extends Component
{
    public string $title;
    public string $description;
    public string $bgColor;
    public string $animationClass;
    public bool $hasData;
    public string $emptyIcon;
    public string $emptyMessage;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $title = '',
        string $description = '',
        string $bgColor = 'bg-white',
        string $animationClass = 'animate-fade-in-optimized',
        bool $hasData = true,
        string $emptyIcon = 'fas fa-box-open',
        string $emptyMessage = 'Không có dữ liệu để hiển thị'
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->bgColor = $bgColor;
        $this->animationClass = $animationClass;
        $this->hasData = $hasData;
        $this->emptyIcon = $emptyIcon;
        $this->emptyMessage = $emptyMessage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.storefront-section');
    }
}
