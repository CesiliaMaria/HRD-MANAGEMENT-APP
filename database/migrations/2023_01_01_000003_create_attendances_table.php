<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Migration untuk tabel attendances (absensi)
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // relasi ke user
            $table->date('date'); // tanggal absensi
            $table->time('check_in')->nullable(); // waktu check in
            $table->time('check_out')->nullable(); // waktu check out
            $table->decimal('latitude', 10, 8)->nullable(); // latitude lokasi
            $table->decimal('longitude', 10, 8)->nullable(); // longitude lokasi
            $table->string('location_address')->nullable(); // alamat lokasi
            $table->enum('status', ['present', 'late', 'absent'])->default('present'); // status kehadiran
            $table->text('notes')->nullable(); // catatan tambahan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}