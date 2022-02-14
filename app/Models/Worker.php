<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    public function profile() {
        return $this->belongsTo(ProfileWorkers::class, 'profile_workers_id', 'id');
    }
}
