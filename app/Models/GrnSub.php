<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrnSub extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function grn(){
        return $this->belongsTo(Grn::class);
    }
    public function item(){
        return $this->belongsTo(Item::class);
    }

    
}
