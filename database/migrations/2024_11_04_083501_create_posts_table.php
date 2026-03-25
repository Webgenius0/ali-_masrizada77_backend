<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('team')->nullable();
            $table->string('location')->nullable();
            $table->longText('content'); // Post details
            $table->string('thumbnail')->nullable();
            $table->string('picture')->nullable();
            $table->string('linkedin_link')->nullable(); // LinkedIn link
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('type', ['en', 'de', 'others'])->default('en');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
