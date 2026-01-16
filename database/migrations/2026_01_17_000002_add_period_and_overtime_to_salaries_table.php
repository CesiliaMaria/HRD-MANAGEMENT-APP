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
        Schema::table('salaries', function (Blueprint $table) {
            // Tambah periode payroll
            $table->integer('period_month')->after('user_id'); // 1-12
            $table->integer('period_year')->after('period_month'); // 2026, dst
            
            // Tambah detail overtime
            $table->decimal('overtime_hours', 5, 2)->default(0)->after('allowance'); // jam lembur
            $table->decimal('overtime_rate', 10, 2)->default(20000)->after('overtime_hours'); // tarif per jam
            
            // Rename overtime_pay menjadi overtime_amount untuk clarity
            // Field overtime_pay sudah ada, jadi kita gunakan itu sebagai overtime_amount
            // Tidak perlu rename, cukup gunakan overtime_pay sebagai overtime_amount
            
            // Index untuk query periode
            $table->index(['period_year', 'period_month']);
            $table->index(['user_id', 'period_year', 'period_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropIndex(['period_year', 'period_month']);
            $table->dropIndex(['user_id', 'period_year', 'period_month']);
            
            $table->dropColumn(['period_month', 'period_year', 'overtime_hours', 'overtime_rate']);
        });
    }
};
