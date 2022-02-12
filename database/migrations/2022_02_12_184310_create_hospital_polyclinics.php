<?php

use App\Models\Hospital;
use App\Models\Polyclinic;
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
        Schema::create('hospital_polyclinics', function (Blueprint $table) {
            $table->foreignIdFor(Hospital::class);
            $table->foreignIdFor(Polyclinic::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hospital_polyclinics');
    }
};
