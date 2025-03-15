<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Xóa unique cũ
            $table->dropUnique('categories_name_unique');
            // Tạo unique mới theo user_id + name
            $table->unique(['user_id', 'name'], 'categories_user_id_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Xóa unique user_id + name
            $table->dropUnique('categories_user_id_name_unique');
            // Tạo lại unique cũ theo name
            $table->unique('name', 'categories_name_unique');
        });
    }
};
