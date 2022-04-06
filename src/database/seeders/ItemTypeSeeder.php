<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemType;

class ItemTypeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('item_types')->truncate();
        $data = [];

        $data[] = [
            'name' => 'job',
        ];
        $data[] = [
            'name' => 'story',
        ];
        $data[] = [
            'name' => 'comment',
        ];
        $data[] = [
            'name' => 'poll',
        ];
        $data[] = [
            'name' => 'pollopt',
        ];

        ItemType::insert($data);
    }
}
