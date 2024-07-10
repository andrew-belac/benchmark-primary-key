<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Ulid;

class UlidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Generator $faker */
        $faker = app()->make(Generator::class);
        $orderDate = Carbon::createFromFormat('Y-m-d H:i:s.u', '1970-01-02 00:00:00.000000');
        for ($count = 0; $count < 100000; $count++) {
            $insert = [];
            for ($i = 0; $i < 1000; $i++){
                $insert[] = ['name' => $faker->name(), 'id'=> Ulid::generate(), 'order_date' =>
                    $orderDate->addMillis(1)->format('Y-m-d H:i:s.u')];
            }
            DB::table('uuid_ulid')->insert($insert);
            DB::table('lookup')->insert([
                'id' => $insert[0]['id'],
                'type' => 'ulid'
            ]);
            unset($insert);
            usleep(200000);
        }
    }
}
