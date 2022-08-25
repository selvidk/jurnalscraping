<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DataScrapingM extends Model
{
    use HasFactory;

    public function dataPT()
    {
        return DB::table('t_pt');
    }
    
    public function dataKategori()
    {
        return DB::table('t_kategori');
    }

    public function dataJurnal()
    {
        return DB::table('t_jurnal');
    }
}
