<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
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
                'name' => 'BMW 525i E60 2008',
            ],
            [
                'name' => 'BMW 540i F10 2013',
            ],
            [
                'name' => 'Chevrolet Lacetti 2007',
            ],
            [
                'name' => 'Mercedes W220 2004',
            ],
            [
                'name' => 'Nissan GTR 2015'
            ],
            [
                'name' => 'Ford Mustang 1967'
            ],
        ];

        foreach ($data as $datum) {
            Car::query()->create($datum);
        }
    }
}
