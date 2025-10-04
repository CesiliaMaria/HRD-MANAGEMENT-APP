<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    /**
     * Migration untuk tabel salaries (penggajian)
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // relasi ke user
            $table->decimal('basic_salary', 10, 2); // gaji pokok
            $table->decimal('allowance', 10, 2)->default(0); // tunjangan
            $table->decimal('overtime_pay', 10, 2)->default(0); // bayaran lembur
            $table->decimal('tax', 10, 2)->default(0); // pajak
            $table->decimal('total_salary', 10, 2); // total gaji
            $table->string('payment_status')->default('pending'); // status pembayaran
            $table->string('payment_method')->nullable(); // metode pembayaran
            $table->string('transaction_id')->nullable(); // ID transaksi payment gateway
            $table->date('payment_date')->nullable(); // tanggal pembayaran
            $table->text('notes')->nullable(); // catatan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}