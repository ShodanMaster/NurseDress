<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Barcode extends Model
{

    protected $guarded = [];

    public static function nextNumber(){
        $prefix = date('ymd');
        $start = '0000001';
        $start = '0000001';

        $len = strlen($prefix);

        $barcode = DB::select("SELECT

        CONCAT( '$prefix',

        LPAD(max_barcode+1,GREATEST(6,LENGTH(max_barcode+1)),'0')

        ) barcode

        FROM

        (SELECT IFNULL(MAX(SUBSTRING(barcode,$len+1)),'0') max_barcode

            FROM barcodes WHERE  barcode LIKE '$prefix%') P2");

        return $barcode[0]->barcode;
    }

    public function item(){
        return $this->belongsTo(Item::class);
    }

}
