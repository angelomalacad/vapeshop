<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageProcessor
{
    /**
     * Process and save product image
     */
    public static function processProductImage($imageFile, $deleteOldPath = null)
    {
        // Delete old image if exists
        if ($deleteOldPath && Storage::disk('public')->exists($deleteOldPath)) {
            Storage::disk('public')->delete($deleteOldPath);
            
            // Also delete thumbnail
            $thumbnailPath = str_replace('products/', 'products/thumbnails/', $deleteOldPath);
            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
        }
        
        // Generate filename
        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-]/', '_', $imageFile->getClientOriginalName());
        $path = 'products/' . $filename;
        
        // Store original image
        $imageFile->storeAs('products', $filename, 'public');
        
        // Create thumbnail if GD is available
        $thumbnailCreated = self::createThumbnail($imageFile, $filename);
        
        return $path;
    }
    
    /**
     * Create thumbnail using native PHP GD
     */
    private static function createThumbnail($imageFile, $filename)
    {
        // Check if GD extension is available
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            Log::warning('GD extension not available for thumbnail creation');
            return false;
        }
        
        try {
            $sourcePath = $imageFile->getRealPath();
            $thumbnailPath = 'products/thumbnails/' . $filename;
            
            // Get image info
            $imageInfo = getimagesize($sourcePath);
            if (!$imageInfo) {
                return false;
            }
            
            list($width, $height, $type) = $imageInfo;
            
            // Create image resource based on type
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $source = imagecreatefromjpeg($sourcePath);
                    break;
                case IMAGETYPE_PNG:
                    $source = imagecreatefrompng($sourcePath);
                    break;
                case IMAGETYPE_GIF:
                    $source = imagecreatefromgif($sourcePath);
                    break;
                default:
                    return false;
            }
            
            // Calculate thumbnail dimensions (max 300px)
            $maxSize = 300;
            if ($width > $height) {
                $newWidth = $maxSize;
                $newHeight = (int) ($height * $maxSize / $width);
            } else {
                $newHeight = $maxSize;
                $newWidth = (int) ($width * $maxSize / $height);
            }
            
            // Create thumbnail canvas
            $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG and GIF
            if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
                imagecolortransparent($thumbnail, imagecolorallocatealpha($thumbnail, 0, 0, 0, 127));
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
            }
            
            // Resize image
            imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, 
                              $newWidth, $newHeight, $width, $height);
            
            // Save thumbnail
            $fullThumbnailPath = storage_path('app/public/' . $thumbnailPath);
            
            switch ($type) {
                case IMAGETYPE_JPEG:
                    imagejpeg($thumbnail, $fullThumbnailPath, 85);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($thumbnail, $fullThumbnailPath, 8);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($thumbnail, $fullThumbnailPath);
                    break;
            }
            
            // Free memory
            imagedestroy($source);
            imagedestroy($thumbnail);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Thumbnail creation failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get thumbnail URL for a product image
     */
    public static function getThumbnailUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }
        
        $thumbnailPath = str_replace('products/', 'products/thumbnails/', $imagePath);
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return Storage::url($thumbnailPath);
        }
        
        // Fallback to original image
        return Storage::url($imagePath);
    }
}