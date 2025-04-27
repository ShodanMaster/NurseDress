<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function size(){
        return  $this->belongsTo(Size::class);
    }

    public function color(){
        return  $this->belongsTo(Color::class);
    }

    public function design(){
        return  $this->belongsTo(design::class);
    }
}
