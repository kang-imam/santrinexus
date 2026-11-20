<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayah', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 13)->unique();
            $table->string('nama', 100);
            $table->string('parent_code', 13)->nullable()->index();
            $table->unsignedTinyInteger('level');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['parent_code', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayah');
    }
};
