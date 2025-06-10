<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'reviewer_name',
        'reviewer_email',
        'rating',
        'title',
        'content',
        'is_verified_purchase',
        'helpful_votes',
        'helpful_count',
        'status',
        'admin_notes',
        'approved_at',
        'images',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'user_id' => 'integer',
        'order_id' => 'integer',
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean',
        'helpful_votes' => 'array',
        'helpful_count' => 'integer',
        'approved_at' => 'datetime',
        'images' => 'array',
    ];

    /**
     * Quan hệ với product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ với user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope cho reviews approved
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope theo rating
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope cho verified purchases
     */
    public function scopeVerifiedPurchase($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    /**
     * Lấy danh sách review statuses
     */
    public static function getReviewStatuses(): array
    {
        return [
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
            'spam' => 'Spam',
        ];
    }

    /**
     * Lấy status label
     */
    public function getStatusLabelAttribute(): string
    {
        return static::getReviewStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Lấy rating stars HTML
     */
    public function getRatingStarsAttribute(): string
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-yellow-400"></i>';
            } else {
                $stars .= '<i class="far fa-star text-gray-300"></i>';
            }
        }
        return $stars;
    }

    /**
     * Lấy review images URLs
     */
    public function getImageUrlsAttribute(): array
    {
        if (!$this->images) {
            return [];
        }

        return array_map(function ($image) {
            return Storage::url($image);
        }, $this->images);
    }

    /**
     * Kiểm tra user đã vote helpful chưa
     */
    public function hasUserVotedHelpful(?int $userId = null): bool
    {
        $userId = $userId ?: auth()->id();
        
        if (!$userId || !$this->helpful_votes) {
            return false;
        }

        return in_array($userId, $this->helpful_votes);
    }

    /**
     * Toggle helpful vote
     */
    public function toggleHelpfulVote(?int $userId = null): bool
    {
        $userId = $userId ?: auth()->id();
        
        if (!$userId) {
            return false;
        }

        $helpfulVotes = $this->helpful_votes ?: [];

        if (in_array($userId, $helpfulVotes)) {
            // Remove vote
            $helpfulVotes = array_diff($helpfulVotes, [$userId]);
            $this->helpful_count = max(0, $this->helpful_count - 1);
        } else {
            // Add vote
            $helpfulVotes[] = $userId;
            $this->helpful_count++;
        }

        $this->helpful_votes = array_values($helpfulVotes);
        return $this->save();
    }

    /**
     * Approve review
     */
    public function approve(): bool
    {
        $this->status = 'approved';
        $this->approved_at = now();
        
        $saved = $this->save();

        if ($saved) {
            // Update product rating
            $this->updateProductRating();
        }

        return $saved;
    }

    /**
     * Reject review
     */
    public function reject(string $reason = ''): bool
    {
        $this->status = 'rejected';
        $this->admin_notes = $reason;
        
        return $this->save();
    }

    /**
     * Update product average rating
     */
    private function updateProductRating(): void
    {
        $product = $this->product;
        
        if (!$product) {
            return;
        }

        $reviews = static::where('product_id', $product->id)
                        ->where('status', 'approved')
                        ->get();

        $averageRating = $reviews->avg('rating');
        $reviewCount = $reviews->count();

        $product->update([
            'average_rating' => round($averageRating, 2),
            'review_count' => $reviewCount,
        ]);
    }

    /**
     * Create review from order
     */
    public static function createFromOrder(
        Order $order,
        int $productId,
        int $rating,
        string $title,
        string $content,
        ?array $images = null
    ): self {
        return static::create([
            'product_id' => $productId,
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'reviewer_name' => $order->customer_name,
            'reviewer_email' => $order->customer_email,
            'rating' => $rating,
            'title' => $title,
            'content' => $content,
            'is_verified_purchase' => true,
            'images' => $images,
            'status' => 'pending',
        ]);
    }

    /**
     * Get rating distribution for product
     */
    public static function getRatingDistribution(int $productId): array
    {
        $distribution = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $count = static::where('product_id', $productId)
                          ->where('status', 'approved')
                          ->where('rating', $i)
                          ->count();
            $distribution[$i] = $count;
        }

        return $distribution;
    }
}
