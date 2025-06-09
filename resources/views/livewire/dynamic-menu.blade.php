<nav class="{{ $this->getContainerClasses() }}" x-data="{ openDropdown: null }">
    @foreach($menuItems as $menuItem)
        <div class="relative" x-data="{ open: false }">
            <!-- Top Level Menu Item -->
            <div class="relative">
                @if($menuItem->hasChildren())
                    <!-- Menu with Dropdown -->
                    <button
                        @click="open = !open"
                        @click.away="open = false"
                        class="{{ $this->getMenuItemClasses($menuItem) }} group"
                    >
                        @if($showIcons && $menuItem->icon)
                            <i class="{{ $menuItem->icon_class }} mr-2"></i>
                        @endif

                        {{ $menuItem->label }}

                        <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200"
                           :class="{ 'rotate-180': open }"></i>
                    </button>
                @else
                    <!-- Simple Menu Link -->
                    <a
                        href="{{ $menuItem->url }}"
                        class="{{ $this->getMenuItemClasses($menuItem) }}"
                    >
                        @if($showIcons && $menuItem->icon)
                            <i class="{{ $menuItem->icon_class }} mr-2"></i>
                        @endif

                        {{ $menuItem->label }}
                    </a>
                @endif
            </div>

            <!-- Dropdown Menu -->
            @if($menuItem->hasChildren())
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="{{ $this->getDropdownClasses() }}"
                    style="display: none;"
                >
                    @foreach($menuItem->children as $childItem)
                        <div>
                            @if($childItem->hasChildren() && $childItem->depth < $maxDepth)
                                <!-- Nested Dropdown (Level 2) -->
                                <div x-data="{ childOpen: false }" class="relative">
                                    <button
                                        @click="childOpen = !childOpen"
                                        class="{{ $this->getMenuItemClasses($childItem, true) }} w-full text-left group"
                                    >
                                        @if($showIcons && $childItem->icon)
                                            <i class="{{ $childItem->icon_class }} mr-2"></i>
                                        @endif

                                        {{ $childItem->label }}

                                        <i class="fas fa-chevron-right ml-auto text-xs transition-transform duration-200"
                                           :class="{ 'rotate-90': childOpen }"></i>
                                    </button>

                                    <!-- Level 2 Dropdown -->
                                    <div
                                        x-show="childOpen"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        class="ml-4 mt-1 space-y-1"
                                        style="display: none;"
                                    >
                                        @foreach($childItem->children as $grandChildItem)
                                            <a
                                                href="{{ $grandChildItem->url }}"
                                                class="{{ $this->getMenuItemClasses($grandChildItem, true) }} pl-4"
                                            >
                                                @if($showIcons && $grandChildItem->icon)
                                                    <i class="{{ $grandChildItem->icon_class }} mr-2"></i>
                                                @endif

                                                {{ $grandChildItem->label }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <!-- Simple Child Link -->
                                <a
                                    href="{{ $childItem->url }}"
                                    class="{{ $this->getMenuItemClasses($childItem, true) }}"
                                >
                                    @if($showIcons && $childItem->icon)
                                        <i class="{{ $childItem->icon_class }} mr-2"></i>
                                    @endif

                                    {{ $childItem->label }}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</nav>
