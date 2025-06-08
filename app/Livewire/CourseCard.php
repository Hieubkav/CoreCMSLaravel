<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class CourseCard extends Component
{
    public Course $course;
    public bool $showFullDescription = false;
    public string $cardSize = 'normal'; // normal, small, large

    public function mount(Course $course, string $cardSize = 'normal')
    {
        $this->course = $course;
        $this->cardSize = $cardSize;
    }

    public function toggleDescription()
    {
        $this->showFullDescription = !$this->showFullDescription;
    }

    public function getMainImage()
    {
        $mainImage = $this->course->images()
            ->where('status', 'active')
            ->orderBy('is_main', 'desc')
            ->orderBy('order')
            ->first();

        if ($mainImage && Storage::disk('public')->exists($mainImage->image_link)) {
            return asset('storage/' . $mainImage->image_link);
        }

        if ($this->course->thumbnail && Storage::disk('public')->exists($this->course->thumbnail)) {
            return asset('storage/' . $this->course->thumbnail);
        }

        return asset('images/placeholder-course.jpg');
    }

    public function getShortDescription()
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->course->description), 120);
    }

    public function getLevelBadgeColor()
    {
        return match($this->course->level) {
            'beginner' => 'bg-green-100 text-green-800',
            'intermediate' => 'bg-yellow-100 text-yellow-800',
            'advanced' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getLevelText()
    {
        return match($this->course->level) {
            'beginner' => 'Cơ bản',
            'intermediate' => 'Trung cấp',
            'advanced' => 'Nâng cao',
            default => 'Không xác định'
        };
    }

    public function getDiscountPercentage()
    {
        if ($this->course->compare_price && $this->course->compare_price > $this->course->price) {
            return round((($this->course->compare_price - $this->course->price) / $this->course->compare_price) * 100);
        }
        return 0;
    }

    public function render()
    {
        return view('livewire.course-card', [
            'mainImage' => $this->getMainImage(),
            'shortDescription' => $this->getShortDescription(),
            'levelBadgeColor' => $this->getLevelBadgeColor(),
            'levelText' => $this->getLevelText(),
            'discountPercentage' => $this->getDiscountPercentage(),
        ]);
    }
}
