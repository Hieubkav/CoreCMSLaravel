@props(['order' => 1])

@php
    $staff = collect();
    $positions = collect();
    $globalSettings = null;

    // Kiểm tra xem model Staff có tồn tại không
    if (class_exists('App\Models\Staff')) {
        try {
            // Lấy nhân viên theo thứ tự
            $staff = \App\Models\Staff::where('status', 'active')
                ->orderBy('order', 'asc')
                ->orderBy('name', 'asc')
                ->limit(8)
                ->get();

            // Lấy các chức vụ để hiển thị
            $positions = \App\Models\Staff::where('status', 'active')
                ->whereNotNull('position')
                ->select('position')
                ->distinct()
                ->orderBy('position')
                ->pluck('position')
                ->take(4);
        } catch (\Exception $e) {
            $staff = collect();
            $positions = collect();
        }
    }

    // Lấy global settings
    try {
        $globalSettings = \App\Models\Setting::first();
    } catch (\Exception $e) {
        $globalSettings = null;
    }
@endphp

@if($staff->count() > 0)
<section class="py-16 bg-white" style="order: {{ $order }};">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                {{ $globalSettings->staff_title ?? 'Đội ngũ của chúng tôi' }}
            </h2>
            @if($globalSettings->staff_description ?? '')
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ $globalSettings->staff_description }}
                </p>
            @endif
        </div>

        <!-- Position Filter (if multiple positions exist) -->
        @if($positions->count() > 1)
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button onclick="filterStaff('all')" 
                    class="filter-btn active px-4 py-2 rounded-full border-2 border-red-600 bg-red-600 text-white font-medium transition-colors hover:bg-red-700">
                Tất cả
            </button>
            @foreach($positions as $position)
                <button onclick="filterStaff('{{ Str::slug($position) }}')" 
                        class="filter-btn px-4 py-2 rounded-full border-2 border-gray-300 text-gray-700 font-medium transition-colors hover:border-red-600 hover:text-red-600">
                    {{ $position }}
                </button>
            @endforeach
        </div>
        @endif

        <!-- Staff Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($staff as $member)
            <div class="staff-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2" 
                 data-position="{{ Str::slug($member->position ?? '') }}">
                <!-- Avatar -->
                <div class="relative">
                    <div class="aspect-w-1 aspect-h-1 bg-gray-100">
                        @if($member->image_url)
                            <img src="{{ $member->image_url }}"
                                 alt="{{ $member->name }}"
                                 class="w-full h-64 object-cover">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <i class="fas fa-user text-4xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Social Links Overlay -->
                    @if(method_exists($member, 'hasSocialLinks') && $member->hasSocialLinks())
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <div class="flex space-x-3">
                            @if($member->social_links && is_array($member->social_links))
                                @foreach($member->social_links as $platform => $url)
                                    @if($url)
                                        <a href="{{ $url }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-700 hover:text-red-600 transition-colors"
                                           title="{{ ucfirst($platform) }}">
                                            <i class="{{ method_exists(\App\Models\Staff::class, 'getSocialIcon') ? \App\Models\Staff::getSocialIcon($platform) : 'fas fa-link' }}"></i>
                                        </a>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Info -->
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        <a href="#" onclick="alert('Staff detail page not implemented yet')"
                           class="hover:text-red-600 transition-colors">
                            {{ $member->name }}
                        </a>
                    </h3>
                    
                    @if($member->position)
                        <p class="text-red-600 font-medium mb-3">{{ $member->position }}</p>
                    @endif
                    
                    @if($member->description)
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                            {{ Str::limit(strip_tags($member->description), 100) }}
                        </p>
                    @endif
                    
                    <!-- Contact Info -->
                    <div class="space-y-2 text-sm text-gray-500">
                        @if($member->email)
                            <div class="flex items-center justify-center">
                                <i class="fas fa-envelope mr-2"></i>
                                <a href="mailto:{{ $member->email }}" 
                                   class="hover:text-red-600 transition-colors">
                                    {{ $member->email }}
                                </a>
                            </div>
                        @endif
                        @if($member->phone)
                            <div class="flex items-center justify-center">
                                <i class="fas fa-phone mr-2"></i>
                                <a href="tel:{{ $member->phone }}" 
                                   class="hover:text-red-600 transition-colors">
                                    {{ $member->phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- View Profile Button -->
                    <div class="mt-4">
                        <a href="#" onclick="alert('Staff detail page not implemented yet')"
                           class="inline-flex items-center text-red-600 hover:text-red-700 font-medium text-sm">
                            Xem chi tiết
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="#" onclick="alert('Staff index page not implemented yet')"
               class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                Xem tất cả nhân viên
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.aspect-w-1 {
    position: relative;
    padding-bottom: 100%; /* 1:1 aspect ratio */
}

.aspect-w-1 > * {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}

.staff-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.filter-btn.active {
    background-color: #dc2626;
    border-color: #dc2626;
    color: white;
}
</style>

<script>
function filterStaff(position) {
    const cards = document.querySelectorAll('.staff-card');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button states
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-red-600', 'text-white');
        btn.classList.add('border-gray-300', 'text-gray-700');
    });
    
    event.target.classList.add('active', 'bg-red-600', 'text-white');
    event.target.classList.remove('border-gray-300', 'text-gray-700');
    
    // Filter cards
    cards.forEach(card => {
        if (position === 'all' || card.dataset.position === position) {
            card.style.display = 'block';
            card.style.animation = 'fadeIn 0.5s ease-in-out';
        } else {
            card.style.display = 'none';
        }
    });
}

// Add fade in animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);
</script>
