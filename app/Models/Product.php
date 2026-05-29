<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'description', 'brand', 'category', 'type',
        'price', 'cost', 'puff_count', 'battery_capacity', 'charging_type',
        'liquid_capacity', 'nicotine_strength', 'adjustable_airflow',
        'smart_display', 'image', 'images', 'image_url', 'gdrive_file_id', 'is_active', 'expiration_date',
        'shelf_life_months','manufacturing_date'
    ];

    protected $casts = [
        'images' => 'array',
        'adjustable_airflow' => 'boolean',
        'smart_display' => 'boolean',
        'is_active' => 'boolean',
        'expiration_date' => 'date',
        'manufacturing_date' => 'date',
    ];

    public function flavors()
    {
        return $this->hasMany(ProductFlavor::class);
    }

    public function branchInventories()
    {
        return $this->hasMany(BranchInventory::class);
    }

    /**
     * Get the product image URL
     */
    public function getImageUrlAttribute()
    {
        // Check if image is a valid URL
        if (isset($this->image) && filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Check if image exists in storage
        if (isset($this->image) && $this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }

        // Check if image_url exists and is a valid URL
        if (isset($this->image_url) && $this->image_url && filter_var($this->image_url, FILTER_VALIDATE_URL)) {
            return $this->image_url;
        }

        // Return null if no image found
        return null;
    }

    /**
     * Get multiple images URLs
     */
    public function getImagesUrlsAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        $urls = [];
        foreach ($this->images as $image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                $urls[] = $image;
            } elseif ($image && Storage::disk('public')->exists($image)) {
                $urls[] = Storage::url($image);
            }
        }

        return $urls;
    }

    /**
     * Check if product has an image
     */
    public function hasImage()
    {
        return !is_null($this->getImageUrlAttribute());
    }

    /**
     * Get placeholder image URL
     */
    public function getPlaceholderImageAttribute()
    {
        return 'https://via.placeholder.com/300x300?text=No+Image';
    }
}
