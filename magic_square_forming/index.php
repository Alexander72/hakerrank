<?php

function isMagiq($square)
{
    $magicConst = array_sum($square[0]);
    return $magicConst == array_sum($square[1]) &&
        $magicConst == array_sum($square[2]) &&
        $magicConst == array_sum(array_column($square, 0)) &&
        $magicConst == array_sum(array_column($square, 1)) &&
        $magicConst == array_sum(array_column($square, 2)) &&
        $magicConst == ($square[0][0] + $square[1][1] + $square[2][2]) &&
        $magicConst == ($square[2][0] + $square[1][1] + $square[0][2]);
}

function dist($s1, $s2) {
    $dist = 0;
    for($i = 0; $i < 3; $i++)
    {
        for($j = 0; $j < 3; $j++)
        {
            $dist += abs($s1[$i][$j] - $s2[$i][$j]);
        }
    }

    return $dist;
}

function generateSquare($alphabet = [1, 2, 3, 4, 5, 6, 7, 8, 9], $index = 0, $square = []) {
    if(!$alphabet)
    {
        yield $square;
    }

    foreach($alphabet as $symbol)
    {
        $alphabetCopy = $alphabet;
        unset($alphabetCopy[array_search($symbol, $alphabetCopy)]);

        $square[$index / 3][$index % 3] = $symbol;

        yield from generateSquare($alphabetCopy, $index + 1, $square);
    }
}

// Complete the formingMagicSquare function below.
function formingMagicSquare($s) {
    if(isMagiq($s))
    {
        return 0;
    }
    $minDist = null;
    foreach(generateSquare() as $square)
    {
        if(!isMagiq($square))
        {
            continue;
        }
        $dist = dist($s, $square);
        if($minDist === null || $dist < $minDist)
        {
            $minDist = $dist;
        }
    }

    return $minDist ?? 0;
}

$fptr = fopen("php://stdout", "w");

$stdin = fopen("php://stdin", "r");

$s = array();

for ($i = 0; $i < 3; $i++) {
    fscanf($stdin, "%[^\n]", $s_temp);
    $s[] = array_map('intval', preg_split('/ /', $s_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$result = formingMagicSquare($s);

fwrite($fptr, $result . "\n");

fclose($stdin);
fclose($fptr);
