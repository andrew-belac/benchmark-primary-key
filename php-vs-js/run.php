<?php

$numbers = range(1, 10_000_000);
$start = microtime(true);
$sum = 0;
foreach ($numbers as $number) {
    $sum += $number;
}
$end = microtime(true);
$tt = round((($end - $start) * 1000),0);
print "SUM IS $sum AND IT TOOK {$tt} milliseconds\n";
