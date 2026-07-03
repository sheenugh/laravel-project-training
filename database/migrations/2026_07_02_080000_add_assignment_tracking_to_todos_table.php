<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            if (! Schema::hasColumn('todos', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('todos', 'assigned_by')) {
                $table->foreignId('assigned_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            if (Schema::hasColumn('todos', 'assigned_by')) {
                $table->dropConstrainedForeignId('assigned_by');
            }

            if (Schema::hasColumn('todos', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }
        });
    }
};
