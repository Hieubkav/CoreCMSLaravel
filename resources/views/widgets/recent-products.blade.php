{{-- Widget: Recent Products --}}
<div class="widget widget-recent-products">
    @if($products->isNotEmpty())
        <ul class="list-unstyled">
            @foreach($products as $product)
                <li class="mb-3 pb-3 border-bottom">
                    <div class="d-flex">
                        @if($product->image)
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ $product->image }}" 
                                     alt="{{ $product->name }}" 
                                     class="rounded"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <a href="{{ route('products.show', $product->slug) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ Str::limit($product->name, 50) }}
                                </a>
                            </h6>
                            @if($product->price)
                                <div class="text-primary fw-bold">
                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                </div>
                            @endif
                            @if($product->rating)
                                <div class="text-warning small">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $product->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        
        <div class="text-center mt-3">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-right me-1"></i>
                Xem tất cả sản phẩm
            </a>
        </div>
    @else
        <p class="text-muted text-center py-3">
            <i class="fas fa-info-circle me-1"></i>
            Chưa có sản phẩm nào.
        </p>
    @endif
</div>
