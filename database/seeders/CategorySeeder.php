<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'id'         => 1,
                'name'       => 'Women Clothes',
                'slug'       => 'women-clothes',
                'image'      => '1779345386.png',
                'parent_id'  => null,
                'created_at' => '2026-05-06 13:14:53',
                'updated_at' => '2026-05-20 23:55:24',
            ],
            [
                'id'         => 2,
                'name'       => 'Women Tops',
                'slug'       => 'women-tops',
                'image'      => '1779346586.png',
                'parent_id'  => null,
                'created_at' => '2026-05-20 23:56:27',
                'updated_at' => '2026-05-20 23:56:27',
            ],
            [
                'id'         => 3,
                'name'       => 'Men Shirts',
                'slug'       => 'men-shirts',
                'image'      => '1779346627.png',
                'parent_id'  => null,
                'created_at' => '2026-05-20 23:57:07',
                'updated_at' => '2026-05-20 23:57:07',
            ],
            [
                'id'         => 4,
                'name'       => 'Women Dresses',
                'slug'       => 'women-dresses',
                'image'      => '1779346665.png',
                'parent_id'  => null,
                'created_at' => '2026-05-20 23:57:45',
                'updated_at' => '2026-05-20 23:57:45',
            ],
            [
                'id'         => 5,
                'name'       => 'Men Jeans',
                'slug'       => 'men-jeans',
                'image'      => '1779346697.png',
                'parent_id'  => null,
                'created_at' => '2026-05-20 23:58:17',
                'updated_at' => '2026-05-20 23:58:17',
            ],
            [
                'id'         => 6,
                'name'       => 'Women Pants',
                'slug'       => 'women-pants',
                'image'      => '1779346963.png',
                'parent_id'  => null,
                'created_at' => '2026-05-21 00:02:43',
                'updated_at' => '2026-05-21 00:02:43',
            ],
            [
                'id'         => 7,
                'name'       => 'Men Shoes',
                'slug'       => 'men-shoes',
                'image'      => '1779347079.png',
                'parent_id'  => null,
                'created_at' => '2026-05-21 00:04:39',
                'updated_at' => '2026-05-21 00:04:39',
            ],
        ]);
    }
}
