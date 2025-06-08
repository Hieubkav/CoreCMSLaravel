@props([
    'type' => 'card', // card, text, image, list
    'count' => 1,
    'class' => ''
])

@switch($type)
    @case('card')
        @for($i = 0; $i < $count; $i++)
            <div class="bg-white rounded-lg shadow-md overflow-hidden {{ $class }}">
                <div class="skeleton h-48 w-full"></div>
                <div class="p-4 space-y-3">
                    <div class="skeleton h-4 w-3/4"></div>
                    <div class="skeleton h-3 w-full"></div>
                    <div class="skeleton h-3 w-2/3"></div>
                    <div class="flex justify-between items-center mt-4">
                        <div class="skeleton h-6 w-20"></div>
                        <div class="skeleton h-8 w-24 rounded-full"></div>
                    </div>
                </div>
            </div>
        @endfor
        @break

    @case('text')
        @for($i = 0; $i < $count; $i++)
            <div class="space-y-2 {{ $class }}">
                <div class="skeleton h-4 w-full"></div>
                <div class="skeleton h-4 w-5/6"></div>
                <div class="skeleton h-4 w-4/6"></div>
            </div>
        @endfor
        @break

    @case('image')
        @for($i = 0; $i < $count; $i++)
            <div class="skeleton aspect-ratio-16-9 w-full {{ $class }}"></div>
        @endfor
        @break

    @case('list')
        @for($i = 0; $i < $count; $i++)
            <div class="flex items-center space-x-3 {{ $class }}">
                <div class="skeleton h-12 w-12 rounded-full"></div>
                <div class="flex-1 space-y-2">
                    <div class="skeleton h-4 w-3/4"></div>
                    <div class="skeleton h-3 w-1/2"></div>
                </div>
            </div>
        @endfor
        @break

    @case('course')
        @for($i = 0; $i < $count; $i++)
            <div class="bg-white rounded-lg shadow-md overflow-hidden {{ $class }}">
                <div class="skeleton h-40 w-full"></div>
                <div class="p-4 space-y-3">
                    <div class="skeleton h-5 w-full"></div>
                    <div class="skeleton h-3 w-2/3"></div>
                    <div class="flex items-center justify-between mt-4">
                        <div class="skeleton h-4 w-16"></div>
                        <div class="skeleton h-4 w-20"></div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="skeleton h-6 w-24"></div>
                        <div class="skeleton h-8 w-20 rounded"></div>
                    </div>
                </div>
            </div>
        @endfor
        @break

    @case('post')
        @for($i = 0; $i < $count; $i++)
            <div class="bg-white rounded-lg shadow-md overflow-hidden {{ $class }}">
                <div class="skeleton h-32 w-full"></div>
                <div class="p-4 space-y-2">
                    <div class="skeleton h-4 w-full"></div>
                    <div class="skeleton h-3 w-4/5"></div>
                    <div class="skeleton h-3 w-3/5"></div>
                    <div class="flex items-center justify-between mt-3">
                        <div class="skeleton h-3 w-16"></div>
                        <div class="skeleton h-3 w-20"></div>
                    </div>
                </div>
            </div>
        @endfor
        @break

    @case('hero')
        <div class="relative {{ $class }}">
            <div class="skeleton h-96 w-full"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center space-y-4">
                    <div class="skeleton h-8 w-80 mx-auto"></div>
                    <div class="skeleton h-4 w-60 mx-auto"></div>
                    <div class="skeleton h-10 w-32 mx-auto rounded-full"></div>
                </div>
            </div>
        </div>
        @break

    @default
        @for($i = 0; $i < $count; $i++)
            <div class="skeleton h-20 w-full {{ $class }}"></div>
        @endfor
@endswitch
