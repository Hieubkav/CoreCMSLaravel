{{-- Page Builder Block: Posts --}}
<div class="posts-block py-5">
    <div class="container">
        @if(isset($title) && $title)
            <div class="text-center mb-5">
                <h2 class="fw-bold">{{ $title }}</h2>
                @if(isset($subtitle) && $subtitle)
                    <p class="text-muted">{{ $subtitle }}</p>
                @endif
            </div>
        @endif
        
        @if($posts->isNotEmpty())
            <div class="row g-4">
                @foreach($posts as $post)
                    <div class="col-lg-6 col-md-6">
                        <article class="card h-100 shadow-sm border-0">
                            @if($post->thumbnail)
                                <div class="card-img-top position-relative overflow-hidden" style="height: 200px;">
                                    <img src="{{ $post->thumbnail }}" 
                                         alt="{{ $post->title }}" 
                                         class="w-100 h-100 object-fit-cover">
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-10"></div>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    @if($post->category)
                                        <span class="badge bg-primary rounded-pill small">
                                            {{ $post->category->name }}
                                        </span>
                                    @endif
                                    <small class="text-muted ms-2">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $post->created_at->format('d/m/Y') }}
                                    </small>
                                </div>
                                
                                <h5 class="card-title">
                                    <a href="{{ route('posts.show', $post->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ $post->title }}
                                    </a>
                                </h5>
                                
                                @if($post->excerpt)
                                    <p class="card-text text-muted flex-grow-1">
                                        {{ Str::limit($post->excerpt, 120) }}
                                    </p>
                                @endif
                                
                                <div class="mt-auto">
                                    <a href="{{ route('posts.show', $post->slug) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-arrow-right me-1"></i>
                                        Đọc thêm
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            
            @if(isset($show_more_link) && $show_more_link)
                <div class="text-center mt-5">
                    <a href="{{ route('posts.index') }}" class="btn btn-primary">
                        <i class="fas fa-newspaper me-2"></i>
                        Xem tất cả bài viết
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>Chưa có bài viết nào</h4>
                    <p>Hãy quay lại sau để xem các bài viết mới nhất.</p>
                </div>
            </div>
        @endif
    </div>
</div>
