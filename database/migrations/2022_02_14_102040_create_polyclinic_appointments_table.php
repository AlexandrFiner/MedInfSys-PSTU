<?php

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\ProfileDoctors;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polyclinic_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Patient::class);
            $table->foreignIdFor(Polyclinic::class);
            $table->foreignIdFor(Doctor::class);
            $table->foreignIdFor(ProfileDoctors::class);
            $table->enum('status', [
                'waiting',      // Запись принята
                'error',        // Пациент не пришел
                'success',      // Пациент принят
            ])->default('waiting');
            $table->dateTime('visit_time');   // время записи
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polyclinic_appointments');
    }
};
