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
        Schema::table('blogs', function (Blueprint $table) {
            // group_key কলাম যোগ করা হচ্ছে, এটি nullable রাখা হয়েছে
            // এবং ইনডেক্স করা হয়েছে দ্রুত খোঁজার জন্য।
            $table->string('group_key')->nullable()->after('type')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('group_key');
        });
    }
};
