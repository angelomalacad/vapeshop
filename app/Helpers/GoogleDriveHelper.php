<?php

namespace App\Helpers;

class GoogleDriveHelper
{
    /**
     * Extract Google Drive file ID from various URL formats
     */
    public static function extractFileId($url)
    {
        if (empty($url)) {
            return null;
        }

        $patterns = [
            '/\/d\/([a-zA-Z0-9_-]+)/', // https://drive.google.com/file/d/FILE_ID/view
            '/id=([a-zA-Z0-9_-]+)/',    // https://drive.google.com/open?id=FILE_ID
            '/\/folders\/([a-zA-Z0-9_-]+)/', // Folder links
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        // If it's just the ID itself
        if (preg_match('/^[a-zA-Z0-9_-]{28,}$/', $url)) {
            return $url;
        }

        return null;
    }

    /**
     * Convert Google Drive URL to direct image URL
     */
    public static function getDirectImageUrl($url)
    {
        $fileId = self::extractFileId($url);
        
        if (!$fileId) {
            return $url; // Return original if not a Drive URL
        }

        // For images, we can use this format
        return "https://drive.google.com/uc?export=view&id={$fileId}";
    }

    /**
     * Get thumbnail URL
     */
    public static function getThumbnailUrl($url, $size = 200)
    {
        $fileId = self::extractFileId($url);
        
        if (!$fileId) {
            return $url;
        }

        return "https://drive.google.com/thumbnail?id={$fileId}&sz=s{$size}";
    }

    /**
     * Check if URL is from Google Drive
     */
    public static function isGoogleDriveUrl($url)
    {
        return strpos($url, 'drive.google.com') !== false || self::extractFileId($url) !== null;
    }
}