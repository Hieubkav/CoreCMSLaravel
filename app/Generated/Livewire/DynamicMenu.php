<?php

namespace App\Generated\Livewire;

use Livewire\Component;
use App\Generated\Models\MenuItem;

class DynamicMenu extends Component
{
    public string $location = 'main';
    public string $menuClass = '';
    public string $itemClass = '';
    public string $linkClass = '';
    public bool $showIcons = true;
    public bool $showDropdown = true;
    public int $maxDepth = 2;

    public function mount(
        string $location = 'main',
        string $menuClass = '',
        string $itemClass = '',
        string $linkClass = '',
        bool $showIcons = true,
        bool $showDropdown = true,
        int $maxDepth = 2
    ) {
        $this->location = $location;
        $this->menuClass = $menuClass;
        $this->itemClass = $itemClass;
        $this->linkClass = $linkClass;
        $this->showIcons = $showIcons;
        $this->showDropdown = $showDropdown;
        $this->maxDepth = $maxDepth;
    }

    public function getMenuItemsProperty()
    {
        return MenuItem::root()
            ->location($this->location)
            ->active()
            ->with(['activeChildren' => function ($query) {
                $query->ordered();
                if ($this->maxDepth > 1) {
                    $query->with(['activeChildren' => function ($subQuery) {
                        $subQuery->ordered();
                    }]);
                }
            }])
            ->ordered()
            ->get();
    }

    public function render()
    {
        return view('livewire.generated.dynamic-menu', [
            'menuItems' => $this->menuItems,
        ]);
    }
}
