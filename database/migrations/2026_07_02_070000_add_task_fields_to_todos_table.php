<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            if (! Schema::hasColumn('todos', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('todos', 'description')) {
                $table->text('description')->nullable()->after('title');
            }

            if (! Schema::hasColumn('todos', 'status')) {
                $table->string('status')->default('not_started')->after('description');
            }

            if (! Schema::hasColumn('todos', 'due_date')) {
                $table->date('due_date')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            if (Schema::hasColumn('todos', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            if (Schema::hasColumn('todos', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('todos', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('todos', 'due_date')) {
                $table->dropColumn('due_date');
            }
        });
    }
};
