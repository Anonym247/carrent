<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Shamil Mardanzade',
            ],
            [
                'name' => 'Jonh Wick',
            ],
            [
                'name' => 'Christopher Nolan',
            ],
            [
                'name' => 'Eyyub Yaqubov'
            ],
        ];

        foreach ($data as $datum) {
            Client::query()->create($datum);
        }
    }
}
