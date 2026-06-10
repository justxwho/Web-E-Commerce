<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('brands')->insert([
            [
                'id'         => 2,
                'name'       => 'Cheap Monday',
                'slug'       => 'cheap-monday',
                'image'      => '1779347549.png',
                'created_at' => '2026-05-21 00:12:29',
                'updated_at' => '2026-05-21 00:12:29',
            ],
            [
                'id'         => 3,
                'name'       => 'Monki',
                'slug'       => 'monki',
                'image'      => '1779349628.png',
                'created_at' => '2026-05-21 00:47:08',
                'updated_at' => '2026-05-21 00:47:08',
            ],
            [
                'id'         => 4,
                'name'       => 'WEEKDAY',
                'slug'       => 'weekday',
                'image'      => '1779349693.jpg',
                'created_at' => '2026-05-21 00:48:13',
                'updated_at' => '2026-05-21 00:48:13',
            ],
            [
                'id'         => 5,
                'name'       => 'Adidas',
                'slug'       => 'adidas',
                'image'      => '1779781791.jpg',
                'created_at' => '2026-05-26 00:49:51',
                'updated_at' => '2026-05-26 00:49:51',
            ],
            [
                'id'         => 6,
                'name'       => 'Nike',
                'slug'       => 'nike',
                'image'      => '1779781861.png',
                'created_at' => '2026-05-26 00:51:02',
                'updated_at' => '2026-05-26 00:51:02',
            ],
            [
                'id'         => 7,
                'name'       => 'Converse',
                'slug'       => 'converse',
                'image'      => '1779781895.png',
                'created_at' => '2026-05-26 00:51:35',
                'updated_at' => '2026-05-26 00:51:35',
            ],
        ]);
    }
}
