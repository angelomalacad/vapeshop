
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First make slug nullable
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->change();
        });
        
        // Generate slugs for existing categories
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            $slug = Str::slug($category->name);
            $counter = 1;
            
            // Check if slug already exists
            while (DB::table('categories')->where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = Str::slug($category->name) . '-' . $counter;
                $counter++;
            }
            
            DB::table('categories')
                ->where('id', $category->id)
                ->update(['slug' => $slug]);
        }
        
        // Make slug not nullable and add unique
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->string('slug')->nullable()->change();
        });
    }
};