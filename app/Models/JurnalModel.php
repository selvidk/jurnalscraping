<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class JurnalModel extends Model
{
    use Searchable;
    // public function data()
    // {
    //     return DB::table('t_jurnal');
    // }
}
