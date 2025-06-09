<?php

namespace App\Generated\Actions;

use App\Generated\Models\AdvancedSearch;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class AdvancedSearchAction
{
    use AsAction;

    /**
     * Perform advanced search
     */
    public function handle(array $params): array
    {
        $startTime = microtime(true);
        
        $query = $params['query'] ?? '';
        $searchType = $params['search_type'] ?? 'general';
        $filters = $params['filters'] ?? [];
        $sortBy = $params['sort_by'] ?? 'relevance';
        $sortDirection = $params['sort_direction'] ?? 'desc';
        $page = $params['page'] ?? 1;
        $perPage = $params['per_page'] ?? 20;
        
        // Perform search based on type
        $results = $this->performSearch($query, $searchType, $filters, $sortBy, $sortDirection, $page, $perPage);
        
        $executionTime = microtime(true) - $startTime;
        
        // Log search
        $this->logSearch([
            'query' => $query,
            'search_type' => $searchType,
            'filters' => $filters,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'page' => $page,
            'per_page' => $perPage,
            'results_count' => $results['total'],
            'execution_time' => $executionTime,
            'has_results' => $results['total'] > 0,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return [
            'results' => $results['data'],
            'total' => $results['total'],
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($results['total'] / $perPage),
            'execution_time' => $executionTime,
            'suggestions' => $this->getSearchSuggestions($query),
            'related_searches' => $this->getRelatedSearches($query),
        ];
    }

    /**
     * Perform search based on type
     */
    private function performSearch(string $query, string $searchType, array $filters, string $sortBy, string $sortDirection, int $page, int $perPage): array
    {
        switch ($searchType) {
            case 'posts':
                return $this->searchPosts($query, $filters, $sortBy, $sortDirection, $page, $perPage);
            case 'general':
            default:
                return $this->searchGeneral($query, $filters, $sortBy, $sortDirection, $page, $perPage);
        }
    }

    /**
     * Search posts with advanced features
     */
    private function searchPosts(string $query, array $filters, string $sortBy, string $sortDirection, int $page, int $perPage): array
    {
        $queryBuilder = Post::query()
            ->with(['category', 'user'])
            ->where('status', 'published');

        // Full-text search
        if (!empty($query)) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%")
                  ->orWhere('meta_description', 'LIKE', "%{$query}%");
            });
        }

        // Apply filters
        if (!empty($filters['category_id'])) {
            $queryBuilder->where('cat_post_id', $filters['category_id']);
        }

        if (!empty($filters['author_id'])) {
            $queryBuilder->where('user_id', $filters['author_id']);
        }

        if (!empty($filters['date_from'])) {
            $queryBuilder->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $queryBuilder->where('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['featured'])) {
            $queryBuilder->where('featured', true);
        }

        // Apply sorting
        switch ($sortBy) {
            case 'date':
                $queryBuilder->orderBy('created_at', $sortDirection);
                break;
            case 'title':
                $queryBuilder->orderBy('title', $sortDirection);
                break;
            case 'views':
                $queryBuilder->orderBy('views', $sortDirection);
                break;
            case 'relevance':
            default:
                if (!empty($query)) {
                    // Calculate relevance score
                    $queryBuilder->selectRaw('*, (
                        CASE 
                            WHEN title LIKE ? THEN 10
                            WHEN excerpt LIKE ? THEN 5
                            WHEN content LIKE ? THEN 1
                            ELSE 0
                        END
                    ) as relevance_score', ["%{$query}%", "%{$query}%", "%{$query}%"])
                    ->orderBy('relevance_score', 'desc')
                    ->orderBy('created_at', 'desc');
                } else {
                    $queryBuilder->orderBy('created_at', 'desc');
                }
                break;
        }

        // Get total count
        $total = $queryBuilder->count();

        // Apply pagination
        $results = $queryBuilder->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(function ($post) use ($query) {
                return [
                    'id' => $post->id,
                    'type' => 'post',
                    'title' => $post->title,
                    'excerpt' => $this->highlightSearchTerms($post->excerpt, $query),
                    'url' => route('posts.show', $post->slug),
                    'image' => $post->image,
                    'category' => $post->category?->name,
                    'author' => $post->user?->name,
                    'date' => $post->created_at->format('d/m/Y'),
                    'views' => $post->views ?? 0,
                    'relevance_score' => $post->relevance_score ?? 0,
                ];
            });

        return [
            'data' => $results,
            'total' => $total,
        ];
    }

    /**
     * General search across multiple content types
     */
    private function searchGeneral(string $query, array $filters, string $sortBy, string $sortDirection, int $page, int $perPage): array
    {
        $results = collect();
        
        // Search posts
        $postResults = $this->searchPosts($query, $filters, $sortBy, $sortDirection, 1, $perPage);
        $results = $results->concat($postResults['data']);
        
        // TODO: Add other content types (products, courses, etc.)
        
        // Sort combined results
        if ($sortBy === 'relevance') {
            $results = $results->sortByDesc('relevance_score');
        } elseif ($sortBy === 'date') {
            $results = $results->sortBy('date', SORT_REGULAR, $sortDirection === 'desc');
        }
        
        // Apply pagination to combined results
        $total = $results->count();
        $paginatedResults = $results->skip(($page - 1) * $perPage)->take($perPage);
        
        return [
            'data' => $paginatedResults->values(),
            'total' => $total,
        ];
    }

    /**
     * Highlight search terms in text
     */
    private function highlightSearchTerms(string $text, string $query): string
    {
        if (empty($query) || empty($text)) {
            return $text;
        }

        $terms = explode(' ', $query);
        
        foreach ($terms as $term) {
            if (strlen($term) > 2) {
                $text = preg_replace(
                    '/(' . preg_quote($term, '/') . ')/i',
                    '<mark class="bg-yellow-200">$1</mark>',
                    $text
                );
            }
        }

        return $text;
    }

    /**
     * Get search suggestions
     */
    private function getSearchSuggestions(string $query): array
    {
        if (strlen($query) < 2) {
            return [];
        }

        return AdvancedSearch::getSearchSuggestions($query, 5);
    }

    /**
     * Get related searches
     */
    private function getRelatedSearches(string $query): array
    {
        if (strlen($query) < 3) {
            return [];
        }

        // Get popular searches that contain similar terms
        $terms = explode(' ', $query);
        $relatedSearches = [];

        foreach ($terms as $term) {
            if (strlen($term) > 2) {
                $related = AdvancedSearch::where('query', 'LIKE', "%{$term}%")
                    ->where('query', '!=', $query)
                    ->where('has_results', true)
                    ->select('query', DB::raw('COUNT(*) as frequency'))
                    ->groupBy('query')
                    ->orderBy('frequency', 'desc')
                    ->limit(3)
                    ->pluck('query')
                    ->toArray();
                
                $relatedSearches = array_merge($relatedSearches, $related);
            }
        }

        return array_unique(array_slice($relatedSearches, 0, 5));
    }

    /**
     * Log search query
     */
    private function logSearch(array $data): void
    {
        try {
            AdvancedSearch::logSearch($data);
        } catch (\Exception $e) {
            // Log error but don't break search functionality
            \Log::error('Failed to log search: ' . $e->getMessage());
        }
    }

    /**
     * Get search autocomplete suggestions
     */
    public function getAutocomplete(string $query, int $limit = 10): array
    {
        if (strlen($query) < 2) {
            return [];
        }

        // Get suggestions from search history
        $historySuggestions = AdvancedSearch::getSearchSuggestions($query, $limit);
        
        // Get suggestions from content
        $contentSuggestions = $this->getContentSuggestions($query, $limit - count($historySuggestions));
        
        return array_merge($historySuggestions, $contentSuggestions);
    }

    /**
     * Get suggestions from actual content
     */
    private function getContentSuggestions(string $query, int $limit): array
    {
        if ($limit <= 0) {
            return [];
        }

        $suggestions = [];
        
        // Get suggestions from post titles
        $postTitles = Post::where('title', 'LIKE', "%{$query}%")
            ->where('status', 'published')
            ->limit($limit)
            ->pluck('title')
            ->toArray();
        
        $suggestions = array_merge($suggestions, $postTitles);
        
        return array_slice(array_unique($suggestions), 0, $limit);
    }

    /**
     * Get search analytics
     */
    public function getAnalytics(int $days = 30): array
    {
        return AdvancedSearch::getSearchAnalytics($days);
    }

    /**
     * Get popular searches
     */
    public function getPopularSearches(int $limit = 10, int $days = 30): array
    {
        return AdvancedSearch::getPopularSearches($limit, $days);
    }

    /**
     * Get failed searches
     */
    public function getFailedSearches(int $limit = 20, int $days = 7): array
    {
        return AdvancedSearch::getFailedSearches($limit, $days);
    }
}
