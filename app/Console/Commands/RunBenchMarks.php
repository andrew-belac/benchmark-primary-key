<?php

namespace App\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Process\Pool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

class RunBenchMarks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-bench-marks {type} {test?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the benchmarks for a primary key type';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        $test = $this->argument('test');
        if ($test === 'orchestrate') {
            $stopWatch = new Stopwatch(true);
            $stopWatch->start($type);
            $pool = Process::pool(function (Pool $pool) use ($type) {
                $pool->as('insert1')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->as('insert2')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->as('insert3')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->as('insert4')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->as('insert5')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->as('insert6')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->as('insert7')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->as('insert8')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->as('insert9')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->as('insert10')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->as('count1')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' count')
                    ->timeout(1200);
                $pool->as('count2')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' count')
                    ->timeout(1200);
                $pool->as('range1')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' range')
                    ->timeout(1200);
                $pool->as('range2')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' range')
                    ->timeout(1200);
                $pool->as('range3')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' range')
                    ->timeout(1200);
                $pool->as('range4')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' range')
                    ->timeout(1200);
                $pool->as('lookup1')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' lookup')
                    ->timeout(1200);
                $pool->as('lookup2')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' lookup')
                    ->timeout(1200);
                $pool->as('lookup3')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' lookup')
                    ->timeout(1200);
                $pool->as('lookup4')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' lookup')
                    ->timeout(1200);
                $pool->as('count3')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' count')
                    ->timeout(1200);
                $pool->as('count4')->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' count')
                    ->timeout(1200);

            })->start(function (string $type, string $output, string $key) {
                // ...
            });
            $results = $pool->wait();
            print_r('Total finished ' . $results->collect()->count());
            $event = $stopWatch->stop($type);
            DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'total', {$event->getDuration()})", [$type]);
        } elseif ($test === 'insertLarge') {
            $stopWatch = new Stopwatch(true);
            $stopWatch->start($type);
            $pool = Process::pool(function (Pool $pool) use ($type) {
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
                $pool->path(getcwd())->command('php artisan app:run-bench-marks ' . $type . ' insert')
                    ->timeout(1200);
            })->start(function (string $type, string $output, string $key) {
                // ...
            });
            $results = $pool->wait();
            print_r('Total finished ' . $results->collect()->count());
            $event = $stopWatch->stop($type);
            //DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'total', {$event->getDuration()})", [$type]);
        } else {
            $this->exec($type, $test);
        }

    }


    private function exec(string $type, string $test): void
    {
        $table = match ($type) {
            'bigint' => 'bigint_version',
            'ulid' => 'uuid_ulid',
            'v4' => 'uuidv4',
            'v7' => 'uuid_v7',
            'v732' => 'uuid_v7_base32',
            'v758' => 'uuid_v7_base58',
            'v7binary' => 'uuid_v7_binary',
        };
        $lookupTable = match ($type) {
            'bigint' => 'lookup',
            'ulid' => 'lookup',
            'v4' => 'lookup',
            'v7' => 'lookup',
            'v732' => 'lookup',
            'v758' => 'lookup',
            'v7binary' => 'lookup_binary',
        };

        match ($test) {
            'insert' => $this->insert($type),
            'insertLarge' => $this->insertLarge($type),
            'lookup' => $this->lookup($type, $table, $lookupTable),
            'range' => $this->rangeSelect($type, $table, $lookupTable),
            'count' => $this->countTest($type, $table),
        };

    }


    private function countTest(string $type, string $table): void
    {
        $stopWatch = new Stopwatch(true);
        $stopWatch->start($type);
        //for ($i = 0; $i < 25; $i++) {
        $out = DB::statement("SELECT count(*) FROM $table");
        unset($out);
        // }
        $event = $stopWatch->stop($type);
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'count', {$event->getDuration()})", [$type]);
    }

    private function rangeSelect(string $type, string $table, string $lookupTable): void
    {
        $offset = rand(0, 94000);
        $stopWatch = new Stopwatch(true);
        $items = DB::select("SELECT b.order_date, a.id FROM $lookupTable a INNER JOIN $table b ON b.id = a.id WHERE a.type = ? LIMIT 5000 OFFSET $offset", [$type]);
        $stopWatch->start($type . 'range');
        foreach ($items as $item) {
            $selected = DB::select("SELECT * FROM $table where order_date >= ? ORDER BY order_date ASC LIMIT 200", [$item->order_date]);
            unset($selected);
        }
        $event = $stopWatch->stop($type . 'range');
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'range', {$event->getDuration()})", [$type]);
        unset($event);
        unset($stopWatch);
        unset($items);
    }


    private function lookup(string $type, string $table, string $lookupTable): void
    {
        $getId = function (string $type, string $id): string|int {
            if ($type === 'bigint') {
                return (int)$id;
            }
            return $id;
        };

        $stopWatch = new Stopwatch(true);
        //Do the lookups
        $items = [];
        $offset = rand(0, 70000);
        $results = DB::select("SELECT * FROM  $lookupTable WHERE type = ? LIMIT 30000 OFFSET {$offset}", [$type]);
        foreach ($results as $result) {
            $items[] = $getId($type, $result->id);
        }
        unset($results);
        $stopWatch->start($type . 'lookup');
        foreach ($items as $key => $item) {
            DB::statement("SELECT * FROM $table where id = ?", [$item]);
        }
        $event = $stopWatch->stop($type . 'lookup');
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'lookup', {$event->getDuration()})", [$type]);
        unset($items);
        unset($event);
    }


    private function runInsert(string $type, int $size): void
    {

        $createV4 = function (): string {
            return Uuid::v4();
        };

        $createV7 = function (): string {
            return Uuid::v7();
        };
        $createV7Binary = function (): string {
            return Uuid::v7()->toBinary();
        };

        $createV732 = function (): string {
            return Uuid::v7()->toBase32();
        };

        $createV758 = function (): string {
            return Uuid::v7()->toBase58();
        };
        $createUlid = function (): string {
            return Ulid::generate();
        };
        $typeTable = 'uuidv4';
        $idCreator = $createV4;
        if ($type === 'v7') {
            $idCreator = $createV7;
            $typeTable = 'uuid_v7';
        }
        if ($type === 'v732') {
            $idCreator = $createV732;
            $typeTable = 'uuid_v7_base32';
        }
        if ($type === 'v758') {
            $idCreator = $createV758;
            $typeTable = 'uuid_v7_base58';
        }
        if ($type === 'ulid') {
            $idCreator = $createUlid;
            $typeTable = 'uuid_ulid';
        }
        if ($type === 'v7binary') {
            $idCreator = $createV7Binary;
            $typeTable = 'uuid_v7_binary';
        }

        $fakeName = '1234567890ABCDEFGHIZKLMNOPQRSTUVWXYZ';
        $bigIntQuery = "INSERT INTO `bigint_version` VALUES (null, ?, ?)";


        //Do the big int version
        $stopWatch = new Stopwatch(true);
        $ids = [];
        $dts = [];
        $dt = CarbonImmutable::now();
        for ($i = 0; $i < $size; $i++) {
            if ($type !== 'bigint') {
                $ids[] = $idCreator();
            }
            $dts[] = $dt->addMicros(10)->format('Y-m-d H:i:s.u');
        }

        $stopWatch->start($type);
        for ($i = 0; $i < $size; $i++) {
            if ($type === 'bigint') {
                DB::insert($bigIntQuery, [$fakeName, $dts[$i]]);
            } else {
                DB::insert("INSERT INTO $typeTable VALUES (?, ?, ?)", [
                    $ids[$i],
                    $fakeName,
                    $dts[$i]
                ]);
            }
        }
        $event = $stopWatch->stop($type);
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'insert', $d)", [$type]);
        unset($d);
        unset($event);
        unset($stopWatch);
        unset($idCreator);
        unset($fakeName);
    }


    public function insert(string $type): void
    {
        $this->runInsert($type, 10000);
    }


}
