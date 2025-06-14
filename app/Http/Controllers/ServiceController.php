<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index(Request $request)
    {
        $query = Service::with(['images'])
            ->where('status', 'active')
            ->ordered();

        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by price range
        if ($request->filled('price_range')) {
            $priceRange = $request->price_range;
            if ($priceRange === '100000000+') {
                $query->where('price', '>=', 100000000);
            } else {
                $ranges = explode('-', $priceRange);
                if (count($ranges) === 2) {
                    $query->whereBetween('price', [(int)$ranges[0], (int)$ranges[1]]);
                }
            }
        }

        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('short_description', 'like', '%' . $search . '%');
            });
        }

        // Filter featured services
        if ($request->filled('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        $services = $query->paginate(12);

        // Get available categories for filter
        $categories = Service::where('status', 'active')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->mapWithKeys(function ($category) {
                $categories = Service::getCategories();
                return [$category => $categories[$category] ?? $category];
            })
            ->sort();

        return view('service.index', compact('services', 'categories'));
    }

    /**
     * Display the specified service.
     */
    public function show(string $slug)
    {
        $service = Service::with(['images'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Get related services
        $relatedServices = $this->getRelatedServices($service);

        return view('service.show', compact('service', 'relatedServices'));
    }

    /**
     * Display services by category.
     */
    public function category(string $category)
    {
        $services = Service::with(['images'])
            ->where('category', $category)
            ->where('status', 'active')
            ->ordered()
            ->paginate(12);

        $categoryName = Service::getCategories()[$category] ?? $category;

        return view('service.category', compact('services', 'category', 'categoryName'));
    }

    /**
     * Display featured services.
     */
    public function featured()
    {
        $services = Service::with(['images'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->ordered()
            ->paginate(12);

        return view('service.featured', compact('services'));
    }

    /**
     * Get related services based on category and price.
     */
    private function getRelatedServices(Service $service, int $limit = 3)
    {
        return Cache::remember("related_services_{$service->id}", 3600, function() use ($service, $limit) {
            $query = Service::with(['images'])
                ->where('id', '!=', $service->id)
                ->where('status', 'active');

            // Prioritize services from the same category
            if ($service->category) {
                $query->where('category', $service->category);
            }

            // If we have price, also consider similar price range
            if ($service->price) {
                $query->orWhere(function($q) use ($service) {
                    $q->where('status', 'active')
                      ->where('id', '!=', $service->id)
                      ->whereBetween('price', [
                          $service->price * 0.7,
                          $service->price * 1.3
                      ]);
                });
            }

            return $query->ordered()
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get services for API/AJAX requests.
     */
    public function api(Request $request)
    {
        $query = Service::where('status', 'active');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('limit')) {
            $query->limit($request->limit);
        }

        $services = $query->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'slug' => $service->slug,
                    'category' => $service->category,
                    'category_name' => $service->category_name,
                    'price' => $service->price,
                    'formatted_price' => $service->formatted_price,
                    'duration' => $service->duration,
                    'duration_name' => $service->duration_name,
                    'image_url' => $service->image_url,
                    'excerpt' => $service->excerpt,
                    'is_featured' => $service->is_featured,
                    'features' => $service->features,
                ];
            })
        ]);
    }

    /**
     * Search services
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập từ khóa tìm kiếm'
            ]);
        }

        $services = Service::search($query)
            ->with(['images'])
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'slug' => $service->slug,
                    'category_name' => $service->category_name,
                    'formatted_price' => $service->formatted_price,
                    'image_url' => $service->image_url,
                    'excerpt' => $service->excerpt,
                ];
            })
        ]);
    }
}
