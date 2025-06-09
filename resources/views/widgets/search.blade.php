{{-- Widget: Search --}}
<div class="widget widget-search">
    <form action="{{ route('search') }}" method="GET" class="search-form">
        <div class="input-group">
            <input type="text" 
                   name="q" 
                   class="form-control" 
                   placeholder="{{ $placeholder ?? 'Tìm kiếm...' }}"
                   value="{{ request('q') }}"
                   autocomplete="off">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
        
        @if(isset($search_types) && count($search_types) > 1)
            <div class="mt-2">
                <small class="text-muted">Tìm trong:</small>
                @foreach($search_types as $type)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="types[]" 
                               value="{{ $type }}" 
                               id="search_{{ $type }}"
                               {{ in_array($type, request('types', $search_types)) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="search_{{ $type }}">
                            {{ ucfirst($type) }}
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
    </form>
    
    @if(isset($show_suggestions) && $show_suggestions)
        <div id="search-suggestions" class="search-suggestions mt-2" style="display: none;">
            <div class="list-group">
                <!-- Search suggestions will be loaded here via AJAX -->
            </div>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-form input[name="q"]');
            const suggestionsContainer = document.getElementById('search-suggestions');
            let searchTimeout;
            
            if (searchInput && suggestionsContainer) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    
                    if (query.length >= 2) {
                        searchTimeout = setTimeout(() => {
                            fetchSuggestions(query);
                        }, 300);
                    } else {
                        suggestionsContainer.style.display = 'none';
                    }
                });
                
                // Hide suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                        suggestionsContainer.style.display = 'none';
                    }
                });
            }
            
            function fetchSuggestions(query) {
                fetch(`/api/search/suggestions?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.suggestions && data.suggestions.length > 0) {
                            let html = '';
                            data.suggestions.forEach(item => {
                                html += `
                                    <a href="${item.url}" class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center">
                                            ${item.image ? `<img src="${item.image}" class="me-2 rounded" style="width: 30px; height: 30px; object-fit: cover;">` : ''}
                                            <div>
                                                <div class="fw-bold">${item.title}</div>
                                                <small class="text-muted">${item.type}</small>
                                            </div>
                                        </div>
                                    </a>
                                `;
                            });
                            suggestionsContainer.querySelector('.list-group').innerHTML = html;
                            suggestionsContainer.style.display = 'block';
                        } else {
                            suggestionsContainer.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Search suggestions error:', error);
                        suggestionsContainer.style.display = 'none';
                    });
            }
        });
        </script>
    @endif
</div>
