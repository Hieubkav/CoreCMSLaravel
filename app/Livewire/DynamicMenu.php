<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MenuItem;

class DynamicMenu extends Component
{
    public string $menuPosition = 'horizontal'; // horizontal, vertical
    public string $menuStyle = 'navbar'; // navbar, sidebar, footer
    public bool $showIcons = true;
    public int $maxDepth = 2;

    /**
     * Mount component với các options
     */
    public function mount(
        string $position = 'horizontal',
        string $style = 'navbar',
        bool $showIcons = true,
        int $maxDepth = 2
    ) {
        $this->menuPosition = $position;
        $this->menuStyle = $style;
        $this->showIcons = $showIcons;
        $this->maxDepth = $maxDepth;
    }

    /**
     * Lấy menu items
     */
    public function getMenuItemsProperty()
    {
        return MenuItem::getMenuTree();
    }

    /**
     * Kiểm tra menu item có active không
     */
    public function isMenuItemActive(MenuItem $menuItem): bool
    {
        return $menuItem->isActive();
    }

    /**
     * Lấy CSS classes cho menu item
     */
    public function getMenuItemClasses(MenuItem $menuItem, bool $isChild = false): string
    {
        $baseClasses = $this->getBaseMenuItemClasses($isChild);
        $activeClasses = $this->isMenuItemActive($menuItem) ? $this->getActiveMenuItemClasses() : '';

        return trim($baseClasses . ' ' . $activeClasses);
    }

    /**
     * Lấy base CSS classes
     */
    private function getBaseMenuItemClasses(bool $isChild = false): string
    {
        if ($this->menuStyle === 'navbar') {
            return $isChild
                ? 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-red-600 transition-colors'
                : 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-red-600 transition-colors';
        }

        if ($this->menuStyle === 'sidebar') {
            return $isChild
                ? 'block pl-8 pr-4 py-2 text-sm text-gray-600 hover:bg-gray-100 hover:text-red-600 transition-colors'
                : 'flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-red-600 transition-colors';
        }

        if ($this->menuStyle === 'footer') {
            return 'block text-gray-600 hover:text-red-600 transition-colors text-sm';
        }

        return 'block text-gray-700 hover:text-red-600 transition-colors';
    }

    /**
     * Lấy active CSS classes
     */
    private function getActiveMenuItemClasses(): string
    {
        if ($this->menuStyle === 'navbar') {
            return 'text-red-600 border-b-2 border-red-600';
        }

        if ($this->menuStyle === 'sidebar') {
            return 'bg-red-50 text-red-600 border-r-2 border-red-600';
        }

        return 'text-red-600 font-semibold';
    }

    /**
     * Lấy container CSS classes
     */
    public function getContainerClasses(): string
    {
        if ($this->menuPosition === 'horizontal') {
            return 'flex items-center space-x-1';
        }

        return 'flex flex-col space-y-1';
    }

    /**
     * Lấy dropdown CSS classes
     */
    public function getDropdownClasses(): string
    {
        if ($this->menuStyle === 'navbar') {
            return 'absolute top-full left-0 mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50';
        }

        return 'ml-4 mt-1 space-y-1';
    }

    public function render()
    {
        return view('livewire.dynamic-menu', [
            'menuItems' => $this->menuItems
        ]);
    }
}
