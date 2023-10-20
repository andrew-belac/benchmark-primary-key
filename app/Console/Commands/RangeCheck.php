<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Uuid;

class RangeCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:range';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check range sort';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s.u', '2020-01-02 00:00:00.000000');
        for ($i = 0; $i < 10000; $i++){
            $key = Uuid::v7()->toBase32();
            $start = $start->addMinute();
            DB::insert("INSERT INTO range_check VALUES (?, ?, ?)",
                [
                    $key,
                    $start->format('Y-m-d H:i:s.u'),
                    CarbonImmutable::now()->format('Y-m-d H:i:s.u')
                ]);
            usleep(1000);
        }
    }





}
