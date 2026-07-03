<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            if (! Schema::hasColumn('todos', 'due_time')) {
                $table->time('due_time')->nullable()->after('due_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            if (Schema::hasColumn('todos', 'due_time')) {
                $table->dropColumn('due_time');
            }
        });
    }
};
