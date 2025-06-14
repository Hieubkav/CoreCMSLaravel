<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StaffController extends Controller
{
    /**
     * Display a listing of staff members.
     */
    public function index(Request $request)
    {
        $query = Staff::with(['images'])
            ->where('status', 'active')
            ->ordered();

        // Filter by position if provided
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $staff = $query->paginate(12);

        // Get available positions for filter
        $positions = Staff::where('status', 'active')
            ->whereNotNull('position')
            ->distinct()
            ->pluck('position')
            ->sort();

        return view('staff.index', compact('staff', 'positions'));
    }

    /**
     * Display the specified staff member.
     */
    public function show(string $slug)
    {
        $staff = Staff::with(['images'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Get other staff members from the same position
        $relatedStaff = $this->getRelatedStaff($staff);

        return view('staff.show', compact('staff', 'relatedStaff'));
    }

    /**
     * Display staff members by position.
     */
    public function position(string $position)
    {
        $staff = Staff::with(['images'])
            ->where('position', $position)
            ->where('status', 'active')
            ->ordered()
            ->paginate(12);

        return view('staff.position', compact('staff', 'position'));
    }

    /**
     * Get related staff members based on position.
     */
    private function getRelatedStaff(Staff $staff, int $limit = 3)
    {
        return Cache::remember("related_staff_{$staff->id}", 3600, function() use ($staff, $limit) {
            $query = Staff::with(['images'])
                ->where('id', '!=', $staff->id)
                ->where('status', 'active');

            // Prioritize staff from the same position
            if ($staff->position) {
                $query->where('position', $staff->position);
            }

            return $query->ordered()
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get staff members for API/AJAX requests.
     */
    public function api(Request $request)
    {
        $query = Staff::where('status', 'active');

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        if ($request->filled('limit')) {
            $query->limit($request->limit);
        }

        $staff = $query->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $staff->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'slug' => $member->slug,
                    'position' => $member->position,
                    'image_url' => $member->image_url,
                    'description' => strip_tags($member->description),
                    'email' => $member->email,
                    'phone' => $member->phone,
                    'social_links' => $member->social_links,
                ];
            })
        ]);
    }
}
