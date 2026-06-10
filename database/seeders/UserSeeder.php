<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id'                => 1,
                'name'              => 'Admin',
                'email'             => 'admin@gmail.com',
                'mobile'            => '0987654322',
                'status'            => 1,
                'avatar'            => '1779680484.JPG',
                'email_verified_at' => null,
                'password'          => '$2y$12$L0vh8v6JT1QibSJf4chbsOCU4bSSST7IZHmf7lcCvzfkHYk3Tmzwa',
                'utype'             => 'ADM',
                'remember_token'    => null,
                'created_at'        => '2026-05-06 13:14:07',
                'updated_at'        => '2026-05-24 20:41:24',
            ],
            [
                'id'                => 2,
                'name'              => 'Hà Nhất Nam',
                'email'             => 'hanhatnam0912@gmail.com',
                'mobile'            => '0394560224',
                'status'            => 1,
                'avatar'            => '1779699379.JPG',
                'email_verified_at' => null,
                'password'          => '$2y$12$6xFcVY..JZcgqr9dDtV7Y.nrpWK3kpA8tmJaYTkZe8J.McCNxUh4G',
                'utype'             => 'USR',
                'remember_token'    => null,
                'created_at'        => '2026-05-08 11:15:13',
                'updated_at'        => '2026-05-25 01:59:28',
            ],
        ]);
    }
}
