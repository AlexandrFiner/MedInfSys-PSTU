<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Laboratory;
use App\Models\ProfileLaboratories;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratory_profiles', function (Blueprint $table) {
            $table->foreignIdFor(Laboratory::class);
            $table->foreignIdFor(ProfileLaboratories::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laboratory_profiles');
    }
};
