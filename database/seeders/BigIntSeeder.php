<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BigIntSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Generator $faker */
        $faker = app()->make(Generator::class);
        $orderDate = CarbonImmutable::createFromFormat('Y-m-d H:i:s.u', '1970-01-02 00:00:00.000000');
        for ($count = 0; $count < 10000; $count++) {
            $insert = [];
            for ($i = 0; $i < 1000; $i++){
                $insert[] = ['id' => (($count * 1000) + $i+1),'name' => $faker->name(), 'order_date' => $orderDate->format('Y-m-d H:i:s.u')];
                $orderDate = $orderDate->addMillis(10);
            }
            DB::table('bigint_version')->insert($insert);
            DB::table('lookup')->insert([
                'id' => (string) $insert[0]['id'],
                'type' => 'bigint'
            ]);
        }
    }
}
