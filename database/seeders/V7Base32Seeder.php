<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Uuid;

class V7Base32Seeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws BindingResolutionException
     */
    public function run(): void
    {
        //** @var Generator $faker */
        $faker = app()->make(Generator::class);
        $orderDate = Carbon::createFromFormat('Y-m-d H:i:s.u', '1970-01-02 00:00:00.000000');
        for ($count = 0; $count < 100000; $count++) {
            $insert = [];
            for ($i = 0; $i < 1000; $i++) {
                $insert[] = ['name' => $faker->name(), 'id' => Uuid::v7()->toBase32(), 'order_date' =>
                    $orderDate->addMillis(1)->format('Y-m-d H:i:s.u')];
            }
            try {
                DB::table('uuid_v7_base32')->insert($insert);
                DB::table('lookup')->insert([
                    'id' => $insert[0]['id'],
                    'type' => 'v732'
                ]);
                unset($insert);
                usleep(10000);
            } catch (\Throwable $e) {
                echo $e->getMessage();
                $count--;
                sleep(1);
            } finally {
                unset($insert);
            }
        }
    }
}
