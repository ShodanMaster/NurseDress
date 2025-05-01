<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Grn extends Model
{
    use SoftDeletes;

    protected $guraded = [];

    public static function nextNumber(){
        $yr = 'GRN' . date("y");
        $len = strlen($yr);

        $grnno = DB::select("SELECT CONCAT('$yr',LPAD(max_grn_no+1,GREATEST(5,LENGTH(max_grn_no+1)),'0')) grn_number FROM
        (SELECT IFNULL( MAX(SUBSTRING(`grn_no`, $len+1)),'0') max_grn_no FROM grns WHERE grn_no LIKE '$yr%') p2");

        return $grnno[0]->grn_number;
    }

    public function grnSubs(){
        return $this->hasMany(GrnSub::class);
    }

    public function qcs(){
        return $this->hasMany(Qc::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function barcodes(){
        return $this->hasMany(Barcode::class);
    }
}
