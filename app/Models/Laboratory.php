<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    use HasFactory;

    public function profiles() {
        return $this->belongsToMany(ProfileLaboratories::class, 'laboratory_profiles');
    }

}
