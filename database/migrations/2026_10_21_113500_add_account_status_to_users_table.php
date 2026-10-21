<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds an approval status for accounts and migrates the legacy
     * 'employee' role to the new 'cashier' role.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // pending | approved | rejected
            $table->string('status')->default('pending')->after('role');
        });

        // Existing accounts were already trusted: approve them.
        DB::table('users')->update(['status' => 'approved']);

        // Legacy role -> new cashier role.
        DB::table('users')->where('role', 'employee')->update(['role' => 'cashier']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('cashier')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->whereIn('role', ['cashier', 'stock_clerk'])->update(['role' => 'employee']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('employee')->change();
            $table->dropColumn('status');
        });
    }
};
