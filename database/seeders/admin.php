<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class admin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_admin')->insert([
            'email'      => 'admin@gmail.com',
            'nama_admin' => 'admin',
            'password'   => Hash::make('admin'),
            'level'      => 1,
            'tgl_buat'   => date('Y-m-d H:i:s'),
        ]);
    }
}
