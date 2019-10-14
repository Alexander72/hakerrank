<?php

function generateTest()
{
    for($k = 1; $k <= 100; $k++)
    {
        for($n = 1; $n <= 1000; $n++)
        {
            $numbers = [];
            for($i = 0; $i < $n; $i++)
            {
                $numbers[] = rand(0, 10000);
            }

            yield [$k, $numbers];
        }
    }
}

/*
 * Complete the 'nonDivisibleSubset' function below.
 *
 * The function is expected to return an INTEGER.
 * The function accepts following parameters:
 *  1. INTEGER k
 *  2. INTEGER_ARRAY s
 */

function nonDivisibleSubset($k, $s) {
    $set = array_map(function($item) use($k) {return $item % $k;}, $s);
    $numbers = array_pad([], $k, 0);
    foreach($s as $item)
    {
        $numbers[$item % $k]++;
    }

    ksort($numbers);
    $hasEvenlyDivisible = $numbers[0] ? 1 : 0;
    $result = 0;
    unset($numbers[0]);
    foreach($numbers as $item => $itemsCount)
    {
        if($item > floor($k / 2))
        {
            break;
        }
        if($item == $k - $item)
        {
            $result += $itemsCount ? 1 : 0;
        }
        else
        {
            $result += max($itemsCount, $numbers[$k - $item]);
        }
    }

    return $result ? $result + $hasEvenlyDivisible : 0;

}

function greedySolution($k, $s) {
    $result = [];
    for($i = 0; $i < count($s); $i++)
    {
        for($j = $i + 1; $j < count($s); $j++)
        {
            if(($s[$i] + $s[$j]) % $k)
            {
                $result[] = $s[$i];
                $result[] = $s[$j];
            }
        }
    }

    return count(array_unique($result));
}

/*foreach(generateTest() as $test)
{
    $nonDivisibleSubset = nonDivisibleSubset($test[0], $test[1]);
    $greedySolution = greedySolution($test[0], $test[1]);
    if($nonDivisibleSubset != $greedySolution)
    {
        print_r($test);
        echo "$greedySolution $nonDivisibleSubset\n";
        die();
    }
}*/

$fptr = fopen('php://STDOUT', "w");

$first_multiple_input = explode(' ', rtrim(fgets(STDIN)));

$n = intval($first_multiple_input[0]);

$k = intval($first_multiple_input[1]);

$s_temp = rtrim(fgets(STDIN));

$s = array_map('intval', preg_split('/ /', $s_temp, -1, PREG_SPLIT_NO_EMPTY));

$result = nonDivisibleSubset($k, $s);

fwrite($fptr, $result . "\n");

fclose($fptr);
