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
    protected $signature = 'app:run-bench-marks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //TRUNCATE THE STATS Database
        DB::statement('TRUNCATE TABLE stats');

        //Do the BIG INT Version
        $this->exec('f', 'f');
        //Do the v4 version
    }


    private function exec(string $tableName, string $key) : void
    {
        DB::statement("TRUNCATE table `bigint`");
        DB::statement("TRUNCATE table `v4`");
        DB::statement("TRUNCATE table `v7`");
        DB::statement("TRUNCATE table `v732`");
        DB::statement("TRUNCATE table `v758`");
        DB::statement("TRUNCATE table `ulid`");

        //Lets insert 10000 rows
        $this->insert('ulid');
        $this->insert('bigint');
        $this->insert('v4');
        $this->insert('v7');
        $this->insert('v732');
        $this->insert('v758');

        //Lets do the range test
        $this->rangeSelect('ulid');
        $this->rangeSelect('bigint');
        $this->rangeSelect('v4');
        $this->rangeSelect('v7');
        $this->rangeSelect('v732');
        $this->rangeSelect('v758');


        $bigIntCode = 'bigint';
        $v4Code = 'v4';
        $v7Code = 'v7';
        $v732Code = 'v732';
        $v758Code = 'v758';
        $ulidCode = 'ulid';

        $bigIntTable = 'bigint_version';
        $v4Table = 'uuidv4';
        $v7Table = 'uuid_v7';
        $v732Table = 'uuid_v7_base32';
        $v758Table = 'uuid_v7_base58';
        $ulidTable = 'uuid_ulid';

        $stopWatch = new Stopwatch(true);

        //Do the lookups
        $items = DB::select('SELECT * FROM lookup WHERE type = ?', [$v758Code]);
        $stopWatch->start($v758Code . 'lookup');
        foreach ($items as $item){
            DB::statement("SELECT * FROM $v758Table where id = ?", [$item->id]);
        }
        $event = $stopWatch->stop($v758Code . 'lookup');
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'lookup', $d)", [$v758Code]);
        unset($items);

        $items = DB::select('SELECT * FROM lookup WHERE type = ?', [$bigIntCode]);
        $stopWatch->start($bigIntCode . 'lookup');
        foreach ($items as $item){
            DB::statement("SELECT * FROM $bigIntTable where id = ?", [(int) $item->id]);
        }
        $event = $stopWatch->stop($bigIntCode . 'lookup');
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'lookup', $d)", [$bigIntCode]);
        unset($items);

        $items = DB::select('SELECT * FROM lookup WHERE type = ?', [$v4Code]);
        $stopWatch->start($v4Code . 'lookup');
        foreach ($items as $item){
            DB::statement("SELECT * FROM $v4Table where id = ?", [$item->id]);
        }
        $event = $stopWatch->stop($v4Code . 'lookup');
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'lookup', $d)", [$v4Code]);
        unset($items);

        $items = DB::select('SELECT * FROM lookup WHERE type = ?', [$v7Code]);
        $stopWatch->start($v7Code . 'lookup');
        foreach ($items as $item){
            DB::statement("SELECT * FROM $v7Table where id = ?", [$item->id]);
        }
        $event = $stopWatch->stop($v7Code . 'lookup');
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'lookup', $d)", [$v7Code]);
        unset($items);

        $items = DB::select('SELECT * FROM lookup WHERE type = ?', [$v732Code]);
        $stopWatch->start($v732Code . 'lookup');
        foreach ($items as $item){
            DB::statement("SELECT * FROM $v732Table where id = ?", [$item->id]);
        }
        $event = $stopWatch->stop($v732Code . 'lookup');
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'lookup', $d)", [$v732Code]);
        unset($items);

        $items = DB::select('SELECT * FROM lookup WHERE type = ?', [$ulidCode]);
        $stopWatch->start($ulidCode . 'lookup');
        foreach ($items as $item){
            DB::statement("SELECT * FROM $ulidTable where id = ?", [$item->id]);
        }
        $event = $stopWatch->stop($ulidCode . 'lookup');
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'lookup', $d)", [$ulidCode]);
        unset($items);


        //Run the count(*)
        $countTimes = 25;

        $stopWatch->start($ulidCode);
        for ($i = 0; $i < $countTimes; $i++){
            $out = DB::statement("SELECT count(*) FROM $ulidTable");
            unset($out);
        }
        $event = $stopWatch->stop($ulidCode);
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'count', $d)", [$ulidCode]);

        $stopWatch->start($bigIntCode);
        for ($i = 0; $i < $countTimes; $i++){
            $out = DB::statement("SELECT count(*) FROM $bigIntTable");
            unset($out);
        }
        $event = $stopWatch->stop($bigIntCode);
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'count', $d)", [$bigIntCode]);

        $stopWatch->start($v7Code);
        for ($i = 0; $i < $countTimes; $i++){
            $out = DB::statement("SELECT count(*) FROM $v7Table");
            unset($out);
        }
        $event = $stopWatch->stop($v7Code);
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'count', $d)", [$v7Code]);

        $stopWatch->start($v4Code);
        for ($i = 0; $i < $countTimes; $i++){
            $out = DB::statement("SELECT count(*) FROM $v4Table");
            unset($out);
        }
        $event = $stopWatch->stop($v4Code);
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'count', $d)", [$v4Code]);

        $stopWatch->start($v732Code);
        for ($i = 0; $i < $countTimes; $i++){
            $out = DB::statement("SELECT count(*) FROM $v732Table");
            unset($out);
        }
        $event = $stopWatch->stop($v732Code);
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'count', $d)", [$v732Code]);

        $stopWatch->start($v758Code);
        for ($i = 0; $i < $countTimes; $i++){
            $out = DB::statement("SELECT count(*) FROM $v758Table");
            unset($out);
        }
        $event = $stopWatch->stop($v758Code);
        $d = $event->getDuration();
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'count', $d)", [$v758Code]);

    }


    private function rangeSelect(string $type) : void {
        $table = '`bigint_version`';
        if ($type === 'v4'){
            $table = 'uuidv4';
        }
        if ($type === 'v7'){
            $table = 'uuid_v7';
        }
        if ($type === 'v732'){
            $table =  'uuid_v7_base32';
        }
        if ($type === 'v758'){
            $table =  'uuid_v7_base58';
        }
        if ($type === 'ulid'){
            $table =  'uuid_ulid';
        }
        $stopWatch = new Stopwatch(true);
        $items = DB::select("SELECT b.order_date, a.id FROM lookup a INNER JOIN $table b ON b.id = a.id WHERE a.type = ?", [$type]);
        $stopWatch->start($type . 'range');
        foreach ($items as $item){
            $selected = DB::select ("SELECT * FROM $table where order_date >= ? LIMIT 1000", [$item->order_date]);
            unset($selected);
        }
        $event = $stopWatch->stop($type . 'range');
        DB::connection()->insert("INSERT INTO stats VALUES (null, ?, 'range', {$event->getDuration()})", [$type]);
        unset($event);
        unset($stopWatch);
        unset($items);

    }

    private function insert(string $type) : void
    {

        $createV4 = function() : string
        {
          return Uuid::v4();
        };

        $createV7 = function() : string
        {
            return Uuid::v7();
        };

        $createV732 = function() : string
        {
            return Uuid::v7()->toBase32();
        };

        $createV758 = function() : string
        {
            return Uuid::v7()->toBase58();
        };
        $createUlid = function() : string
        {
            return Ulid::generate();
        };

        $idCreator = $createV4;
        if ($type === 'v7'){
            $idCreator = $createV7;
        }
        if ($type === 'v732'){
            $idCreator = $createV732;
        }
        if ($type === 'v758'){
            $idCreator = $createV758;
        }
        if ($type === 'ulid'){
            $idCreator = $createUlid;
        }

        $fakeName = '1234567890ABCDEFGHIZKLMNOPQRSTUVWXYZ';
        $bigIntQuery = "INSERT INTO `bigint` VALUES (null, ?, ?)";



        //Do the big int version
        $stopWatch = new Stopwatch(true);
        $stopWatch->start($type);
        for ($i = 0; $i < 10000; $i++){
            if ($type === 'bigint'){
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
