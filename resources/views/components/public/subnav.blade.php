{{-- Simple subnav for Core Framework --}}
@if(isset($settings) && $settings)
<div class="bg-blue-600 text-white py-2">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center text-sm">
            <div class="flex items-center gap-4">
                @if($settings->email)
                <a href="mailto:{{ $settings->email }}" class="hover:text-blue-200 transition">
                    <i class="fas fa-envelope mr-1"></i> {{ $settings->email }}
                </a>
                @endif
                @if($settings->hotline)
                <a href="tel:{{ $settings->hotline }}" class="hover:text-blue-200 transition">
                    <i class="fas fa-phone mr-1"></i> {{ $settings->hotline }}
                </a>
                @endif
            </div>
            <div class="hidden md:block">
                <span>Welcome to Core Framework</span>
            </div>
        </div>
    </div>
</div>
@else
<div class="bg-blue-600 text-white py-2">
    <div class="container mx-auto px-4">
        <div class="text-center text-sm">
            <span>Welcome to Core Framework</span>
        </div>
    </div>
</div>
@endif