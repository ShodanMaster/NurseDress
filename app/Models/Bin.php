<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bin extends Model
{
    // use SoftDeletes;

    protected$guarded = [];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
