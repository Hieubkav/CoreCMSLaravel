{{-- Page Builder Block: Products --}}
<div class="products-block py-5">
    <div class="container">
        @if(isset($title) && $title)
            <div class="text-center mb-5">
                <h2 class="fw-bold">{{ $title }}</h2>
                @if(isset($subtitle) && $subtitle)
                    <p class="text-muted">{{ $subtitle }}</p>
                @endif
            </div>
        @endif
        
        @if($products->isNotEmpty())
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card h-100 shadow-sm border-0 product-card">
                            @if($product->image)
                                <div class="card-img-top position-relative overflow-hidden" style="height: 200px;">
                                    <img src="{{ $product->image }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-100 h-100 object-fit-cover product-image">
                                    
                                    @if($product->is_featured)
                                        <span class="position-absolute top-0 end-0 badge bg-warning text-dark m-2">
                                            <i class="fas fa-star me-1"></i>Nổi bật
                                        </span>
                                    @endif
                                    
                                    @if($product->discount_percentage)
                                        <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                                            -{{ $product->discount_percentage }}%
                                        </span>
                                    @endif
                                    
                                    <div class="product-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                        <a href="{{ route('products.show', $product->slug) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                @if($product->category)
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark small">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>
                                @endif
                                
                                <h6 class="card-title">
                                    <a href="{{ route('products.show', $product->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ Str::limit($product->name, 50) }}
                                    </a>
                                </h6>
                                
                                @if($product->short_description)
                                    <p class="card-text text-muted small flex-grow-1">
                                        {{ Str::limit($product->short_description, 80) }}
                                    </p>
                                @endif
                                
                                @if($product->rating)
                                    <div class="mb-2">
                                        <div class="text-warning small">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $product->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="text-muted ms-1">({{ $product->reviews_count ?? 0 }})</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="mt-auto">
                                    @if($product->price)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="price">
                                                @if($product->sale_price && $product->sale_price < $product->price)
                                                    <span class="text-danger fw-bold">
                                                        {{ number_format($product->sale_price, 0, ',', '.') }}đ
                                                    </span>
                                                    <small class="text-muted text-decoration-line-through ms-1">
                                                        {{ number_format($product->price, 0, ',', '.') }}đ
                                                    </small>
                                                @else
                                                    <span class="text-primary fw-bold">
                                                        {{ number_format($product->price, 0, ',', '.') }}đ
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            @if($product->in_stock)
                                                <button class="btn btn-outline-primary btn-sm add-to-cart" 
                                                        data-product-id="{{ $product->id }}">
                                                    <i class="fas fa-cart-plus"></i>
                                                </button>
                                            @else
                                                <span class="badge bg-secondary">Hết hàng</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if(isset($show_more_link) && $show_more_link)
                <div class="text-center mt-5">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Xem tất cả sản phẩm
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-box-open fa-3x mb-3"></i>
                    <h4>Chưa có sản phẩm nào</h4>
                    <p>Hãy quay lại sau để xem các sản phẩm mới nhất.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.product-overlay {
    background: rgba(0,0,0,0.7);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.product-image {
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}
</style>
