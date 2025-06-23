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
        Schema::table('chat_participants', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_participants', 'left_at')) {
                $table->timestamp('left_at')->nullable()->after('joined_at');
            }
            if (!Schema::hasColumn('chat_participants', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('left_at');
            }
            if (!Schema::hasColumn('chat_participants', 'is_muted')) {
                $table->boolean('is_muted')->default(false)->after('is_admin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_participants', function (Blueprint $table) {
            $table->dropColumn(['left_at', 'is_admin', 'is_muted']);
        });
    }
};
