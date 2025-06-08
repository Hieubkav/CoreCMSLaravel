<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-900">Tổng số file ảnh</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $totalFiles }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-900">Tổng dung lượng</h3>
            <p class="text-2xl font-bold text-green-600">{{ $totalSize }}</p>
        </div>
    </div>
    
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="font-semibold text-gray-900 mb-2">Thư mục được quét</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($directories as $dir)
                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                    storage/app/public/{{ $dir }}
                </span>
            @endforeach
        </div>
    </div>
    
    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
        <h4 class="font-semibold text-yellow-800">Lưu ý:</h4>
        <ul class="text-sm text-yellow-700 mt-2 space-y-1">
            <li>• Chỉ quét các file ảnh: jpg, jpeg, png, gif, webp</li>
            <li>• Ảnh "Không sử dụng" có thể xóa an toàn</li>
            <li>• Nên backup trước khi xóa hàng loạt</li>
            <li>• Quét lại sau khi upload/xóa ảnh mới</li>
        </ul>
    </div>
</div>
