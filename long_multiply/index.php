<?php
/**
   234
    58
  ----
  1872
 1170
 -----
 13572
 */

function sum(array $numbers): string {
    $longestNumber = max(array_map('strlen', $numbers));
    $numbers = array_map('strrev', $numbers);
    $shift = 0;
    $result = "0";

    for($i = 0; $i < $longestNumber; $i++)
    {
        $digitResult = 0;
        foreach($numbers as $number)
        {
            $digitResult += $number[$i] ?? 0;
        }
        $result[$i] = ($digitResult + $shift) % 10;
        $shift = floor(($digitResult + $shift) / 10);
    }
    $result = ($shift ? $shift : '').strrev($result);

    return $result;
}

/**
 * @param $a
 * @param $b
 */
function multiply(string $a, string $b) {
    $a = strrev($a);
    $b = strrev($b);
    if(strlen($a) < strlen($b))
    {
        $t = $a;
        $a = $b;
        $b = $t;
    }

    $singleMults = [];

    for($i = 0; $i < strlen($b); $i++)
    {
        $shift = 0;
        $singleMult = "0";
        for($j = 0; $j < strlen($a); $j++)
        {
            $digitResult = $a[$j] * $b[$i];
            $singleMult[$j] = ($digitResult + $shift) % 10;
            $shift = floor(($digitResult + $shift) / 10);
        }
        $singleMult = ($shift ? $shift : '').strrev($singleMult).str_repeat('0', $i);
        $singleMults[] = $singleMult;
    }

    return sum($singleMults);
}

// Complete the extraLongFactorials function below.
function extraLongFactorials($n) {
    $result = "1";
    for($i = 1; $i <= $n; $i ++)
    {
        $result = multiply($result, $i);
    }

    echo $result."\n";
}

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $n);

extraLongFactorials($n);

fclose($stdin);
