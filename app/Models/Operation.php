<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    public function getOrganizationAttribute() {
        return app($this->organization_type)::find($this->organization_id);
    }

    public function getOrganizationIsAttribute() {
        return $this->organization_type == Hospital::class ? 'Больница' : 'Поликлиника';
    }

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }

    public function operable() {
        return $this->morphTo();
    }
}
