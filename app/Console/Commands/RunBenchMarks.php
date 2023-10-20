<?php

namespace App\Console\Commands;

use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
    protected $signature = 'app:run-bench-marks {type}';

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
        $this->exec($this->argument('type'));
    }


    private function exec(string $type): void
    {
        $table = match ($type) {
            'bigint' => 'bigint_version',
            'ulid' => 'uuid_ulid',
            'v4' => 'uuidv4',
            'v7' => 'uuid_v7',
            'v732' => 'uuid_v7_base32',
            'v758' => 'uuid_v7_base58',
        };
        $lookupTable = match ($type) {
            'bigint' => '`bigint`',
            'ulid' => 'ulid',
            'v4' => 'v4',
            'v7' => 'v7',
            'v732' => 'v732',
            'v758' => 'v758',
        };

        //Truncate all the tables to make sure that nothing is in them
        DB::statement("TRUNCATE `bigint`");
        DB::statement("TRUNCATE table `v4`");
        DB::statement("TRUNCATE table `v7`");
        DB::statement("TRUNCATE table `v732`");
        DB::statement("TRUNCATE table `v758`");
        DB::statement("TRUNCATE table `ulid`");

        //Lets do the range test
        $this->rangeSelect($type, $table);

        //Do insert test
        $this->insert($type);

        //Test lookups
        $this->lookup($type, $table, $lookupTable);

        //Count test
        $this->countTest($type, $table);

    }


    private function countTest(string $type, string $table) : void
    {
        $stopWatch = new Stopwatch(true);
        $stopWatch->start($type);
        for ($i = 0; $i < 25; $i++) {
            $out = DB::statement("SELECT count(*) FROM $table");
            unset($out);
        }
        $event = $stopWatch->stop($type);
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'count', {$event->getDuration()})", [$type]);
    }

    private function rangeSelect(string $type, string $table): void
    {
        $stopWatch = new Stopwatch(true);
        $items = DB::select("SELECT b.order_date, a.id FROM lookup a INNER JOIN $table b ON b.id = a.id WHERE a.type = ?", [$type]);
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
        $items = DB::select('SELECT * FROM lookup WHERE type = ?', [$type]);
        $stopWatch->start($type . 'lookup');

        foreach ($items as $item) {
            DB::statement("SELECT * FROM $table where id = ?", [$getId($type, $item->id)]);
        }
        $event = $stopWatch->stop($type . 'lookup');
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'lookup', {$event->getDuration()})", [$type]);
        unset($items);
        unset($event);
    }


    private function insert(string $type): void
    {

        $createV4 = function (): string {
            return Uuid::v4();
        };

        $createV7 = function (): string {
            return Uuid::v7();
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

        $idCreator = $createV4;
        if ($type === 'v7') {
            $idCreator = $createV7;
        }
        if ($type === 'v732') {
            $idCreator = $createV732;
        }
        if ($type === 'v758') {
            $idCreator = $createV758;
        }
        if ($type === 'ulid') {
            $idCreator = $createUlid;
        }

        $fakeName = '1234567890ABCDEFGHIZKLMNOPQRSTUVWXYZ';
        $bigIntQuery = "INSERT INTO `bigint` VALUES (null, ?, ?)";


        //Do the big int version
        $stopWatch = new Stopwatch(true);
        $stopWatch->start($type);
        for ($i = 0; $i < 10000; $i++) {
            if ($type === 'bigint') {
                DB::insert($bigIntQuery, [$fakeName, CarbonImmutable::now()->format('Y-m-d H:i:s.u')]);
            } else {
                DB::insert("INSERT INTO $type VALUES (?, ?, ?)", [
                    $idCreator(),
                    $fakeName,
                    CarbonImmutable::now()->format('Y-m-d H:i:s.u')
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


}
