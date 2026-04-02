<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // টাইটেল এবং কন্টেন্টের জার্মান ভার্সন যোগ করা হচ্ছে
            $table->string('title_de')->nullable()->after('slug');
            $table->longText('content_de')->nullable()->after('content');

            // যদি তোমার আগে থেকে 'type' কলাম থাকে এবং সেটা আর না লাগে তবে ড্রপ করতে পারো
            // $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['title_de', 'content_de']);
        });
    }
};
