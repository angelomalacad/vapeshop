<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'image_url')) {
                $table->text('image_url')->nullable()->after('image');
            }
            if (!Schema::hasColumn('products', 'gdrive_file_id')) {
                $table->string('gdrive_file_id')->nullable()->after('image_url');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'gdrive_file_id']);
        });
    }
};