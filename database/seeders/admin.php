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
            'email'      => 'superadmin@gmail.com',
            'nama_admin' => 'super admin',
            'password'   => Hash::make('superadmin'),
            'level'      => 'Super Admin',
            'tgl_buat'   => date('Y-m-d H:i:s'),
        ]);
    }
}
