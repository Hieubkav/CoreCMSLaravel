<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Tổng số ảnh</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalFiles }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Tổng dung lượng</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $this->getFormattedTotalSize() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Ảnh không sử dụng</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ count($unusedImages) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-center">
                    @if(count($unusedImages) > 0)
                        <button
                            wire:click="deleteUnusedImages"
                            wire:confirm="Bạn có chắc chắn muốn xóa {{ count($unusedImages) }} ảnh không sử dụng?"
                            class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm"
                        >
                            Xóa ảnh không dùng
                        </button>
                    @else
                        <span class="text-green-600 dark:text-green-400 font-medium">Tất cả ảnh đều được sử dụng</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Danh sách hình ảnh</h2>
            <button
                wire:click="loadImages"
                class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm"
            >
                Quét lại
            </button>
        </div>

        <!-- Images Grid -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-900/20 overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-6">
                @forelse($images as $image)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md dark:hover:shadow-gray-900/30 transition-shadow bg-gray-50 dark:bg-gray-700">
                        <!-- Image Preview -->
                        <div class="aspect-square mb-3 bg-gray-100 dark:bg-gray-600 rounded-lg overflow-hidden">
                            <img
                                src="{{ $image['url'] }}"
                                alt="{{ $image['name'] }}"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            >
                        </div>

                        <!-- Image Info -->
                        <div class="space-y-2">
                            <h3 class="font-medium text-sm text-gray-900 dark:text-white truncate" title="{{ $image['name'] }}">
                                {{ $image['name'] }}
                            </h3>

                            <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300">
                                <span>{{ $image['formatted_size'] }}</span>
                                <span class="uppercase">{{ $image['extension'] }}</span>
                            </div>

                            <div class="text-xs text-gray-600 dark:text-gray-300">
                                {{ $image['created_at'] }}
                            </div>

                            <!-- Usage Status -->
                            <div class="flex items-center justify-between">
                                @if($image['is_used'])
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        Đang sử dụng
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                        Không sử dụng
                                    </span>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2 pt-2">
                                <a
                                    href="{{ $image['url'] }}"
                                    target="_blank"
                                    class="flex-1 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white text-xs py-1 px-2 rounded text-center transition-colors duration-200"
                                >
                                    Xem
                                </a>
                                <button
                                    wire:click="deleteImage('{{ $image['path'] }}')"
                                    wire:confirm="Bạn có chắc chắn muốn xóa ảnh này?"
                                    class="flex-1 bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white text-xs py-1 px-2 rounded transition-colors duration-200"
                                >
                                    Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Không có hình ảnh</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Chưa có hình ảnh nào trong storage.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Info -->
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Lưu ý quan trọng</h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Chỉ quét các file ảnh: jpg, jpeg, png, gif, webp</li>
                            <li>Ảnh "Không sử dụng" có thể xóa an toàn</li>
                            <li>Nên backup trước khi xóa hàng loạt</li>
                            <li>Quét lại sau khi upload/xóa ảnh mới</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
