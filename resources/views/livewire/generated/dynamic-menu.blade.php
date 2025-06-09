{{-- Dynamic Menu Livewire Component --}}
@if($menuItems->count() > 0)
    <ul class="{{ $menuClass }}">
        @foreach($menuItems as $item)
            <li class="{{ $itemClass }} {{ $item->hasActiveChildren() && $showDropdown ? 'has-dropdown' : '' }}">
                <a href="{{ $item->full_url }}" 
                   target="{{ $item->target }}"
                   class="{{ $linkClass }} {{ $item->css_class }}"
                   @if($item->hasActiveChildren() && $showDropdown) 
                       x-data="{ open: false }" 
                       @mouseenter="open = true" 
                       @mouseleave="open = false"
                   @endif>
                   
                    @if($showIcons && $item->icon)
                        <i class="{{ $item->icon }} mr-2"></i>
                    @endif
                    
                    {{ $item->title }}
                    
                    @if($item->hasActiveChildren() && $showDropdown)
                        <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200" 
                           :class="{ 'rotate-180': open }"></i>
                    @endif
                </a>

                {{-- Dropdown Menu --}}
                @if($item->hasActiveChildren() && $showDropdown)
                    <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200"
                         x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95">
                        
                        <div class="py-1">
                            @foreach($item->activeChildren as $child)
                                <a href="{{ $child->full_url }}" 
                                   target="{{ $child->target }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-red-600 transition-colors {{ $child->css_class }}">
                                    
                                    @if($showIcons && $child->icon)
                                        <i class="{{ $child->icon }} mr-2"></i>
                                    @endif
                                    
                                    {{ $child->title }}
                                    
                                    @if($child->isExternalUrl())
                                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
@else
    {{-- No Menu Items --}}
    @if(app()->environment('local'))
        <div class="text-gray-500 text-sm italic">
            Chưa có menu items cho vị trí "{{ $location }}"
        </div>
    @endif
@endif

{{-- Mobile Accordion Style (for mobile location) --}}
@if($location === 'mobile')
    <style>
        .mobile-menu-item.has-children > a::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            float: right;
            transition: transform 0.2s;
        }
        
        .mobile-menu-item.has-children.open > a::after {
            transform: rotate(180deg);
        }
        
        .mobile-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .mobile-submenu.open {
            max-height: 500px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu accordion functionality
            document.querySelectorAll('.mobile-menu-item.has-children > a').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const parent = this.parentElement;
                    const submenu = parent.querySelector('.mobile-submenu');
                    
                    // Toggle current item
                    parent.classList.toggle('open');
                    submenu.classList.toggle('open');
                    
                    // Close other open items
                    document.querySelectorAll('.mobile-menu-item.has-children').forEach(function(item) {
                        if (item !== parent) {
                            item.classList.remove('open');
                            item.querySelector('.mobile-submenu').classList.remove('open');
                        }
                    });
                });
            });
        });
    </script>
@endif

{{-- Desktop Dropdown Styles --}}
@if($location !== 'mobile' && $showDropdown)
    <style>
        .has-dropdown {
            position: relative;
        }
        
        .has-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }
        
        /* Mega menu styles for large dropdowns */
        .has-dropdown.mega-menu .dropdown-menu {
            width: 600px;
            left: 50%;
            transform: translateX(-50%) translateY(-10px);
        }
        
        .has-dropdown.mega-menu:hover .dropdown-menu {
            transform: translateX(-50%) translateY(0);
        }
    </style>
@endif
