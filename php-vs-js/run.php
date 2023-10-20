<?php
$numbers = range(1, 10_000_000);
$sum = 0;
$start = microtime(true);
foreach ($numbers as $number) {
    $sum += $number;
}
$end = microtime(true);
$tt = round((($end - $start) * 1000),0);
print "SUM IS $sum AND IT TOOK {$tt} milliseconds\n";
