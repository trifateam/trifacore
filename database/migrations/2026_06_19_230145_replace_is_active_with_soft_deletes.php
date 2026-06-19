<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Replace is_active boolean with Laravel SoftDeletes (deleted_at) for:
     * - kandang
     * - akun_kas
     * - pelanggan
     * - pengguna
     */
    public function up(): void
    {
        $tables = ['kandang', 'akun_kas', 'pelanggan', 'pengguna'];

        foreach ($tables as $table) {
            // 1. Add deleted_at column
            Schema::table($table, function (Blueprint $t) {
                $t->softDeletes();
            });

            // 2. Migrate data: is_active = false → set deleted_at = now()
            if (Schema::hasColumn($table, 'is_active')) {
                DB::table($table)
                    ->where('is_active', false)
                    ->update(['deleted_at' => now()]);

                // 3. Drop is_active column
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('is_active');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['kandang', 'akun_kas', 'pelanggan', 'pengguna'];

        foreach ($tables as $table) {
            // 1. Add is_active column back
            Schema::table($table, function (Blueprint $t) {
                $t->boolean('is_active')->default(true);
            });

            // 2. Migrate data: deleted_at != null → set is_active = false
            DB::table($table)
                ->whereNotNull('deleted_at')
                ->update(['is_active' => false]);

            // 3. Drop deleted_at column
            Schema::table($table, function (Blueprint $t) {
                $t->dropSoftDeletes();
            });
        }
    }
};
