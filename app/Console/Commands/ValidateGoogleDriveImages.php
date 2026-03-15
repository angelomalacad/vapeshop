<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Helpers\GoogleDriveHelper;
use Illuminate\Console\Command;

class ValidateGoogleDriveImages extends Command
{
    protected $signature = 'products:validate-images';
    protected $description = 'Validate Google Drive image links';

    public function handle()
    {
        $products = Product::whereNotNull('image_url')->get();
        
        foreach ($products as $product) {
            $fileId = GoogleDriveHelper::extractFileId($product->image_url);
            
            if ($fileId) {
                $this->info("✓ {$product->name}: Valid Drive ID");
                $product->update(['gdrive_file_id' => $fileId]);
            } else {
                $this->error("✗ {$product->name}: Invalid Drive URL");
            }
        }
    }
}