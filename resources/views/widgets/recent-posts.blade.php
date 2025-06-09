{{-- Widget: Recent Posts --}}
<div class="widget widget-recent-posts">
    @if($posts->isNotEmpty())
        <ul class="list-unstyled">
            @foreach($posts as $post)
                <li class="mb-3 pb-3 border-bottom">
                    <div class="d-flex">
                        @if($post->thumbnail)
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ $post->thumbnail }}" 
                                     alt="{{ $post->title }}" 
                                     class="rounded"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <a href="{{ route('posts.show', $post->slug) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ Str::limit($post->title, 50) }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $post->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        
        <div class="text-center mt-3">
            <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-right me-1"></i>
                Xem tất cả bài viết
            </a>
        </div>
    @else
        <p class="text-muted text-center py-3">
            <i class="fas fa-info-circle me-1"></i>
            Chưa có bài viết nào.
        </p>
    @endif
</div>
