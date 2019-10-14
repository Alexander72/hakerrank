<?php
function getDescIndexes(array $arr) {
    $prev = -1;
    $result = [];
    for($i = 0; $i < count($arr); $i++)
    {
        if($prev > $arr[$i])
        {
            $result[] = $i;
        }
        $prev = $arr[$i];
    }
    return $result;
}

function checkSwap($descIndexes, $arr) {
    if(count($descIndexes) > 2)
    {
        return null;
    }

    if(count($descIndexes) == 2)
    {
        $first = $descIndexes[0];
        $second = $descIndexes[2];
        $firstCheck = (isset($arr[$first - 2]) ? $arr[$first - 2] < $arr[$second] : true) && $arr[$second] < $arr[$first];
        $secondCheck = $arr[$second - 1] < $arr[$first - 1] && (isset($arr[$second + 1]) ? $arr[$first - 1] < $arr[$second + 1] : true);
        if($firstCheck && $secondCheck)
        {
            return [$first, $second];
        }
        else
        {
            return null;
        }
    }
    else
    {
        $checkSwapTwo =
            (isset($arr[$descIndexes[0] - 2]) ? $arr[$descIndexes[0] - 2] < $arr[$descIndexes[0]] : true) &&
            $arr[$descIndexes[0]] < $arr[$descIndexes[0] - 1] &&
            (isset($arr[$descIndexes[0] + 1]) ? $arr[$descIndexes[0] - 1] < $arr[$descIndexes[0] + 1] : true);
        if($checkSwapTwo)
        {
            return [$descIndexes[0] - 1, $descIndexes[0]];
        }
        else
        {
            return null;
        }
        // Other cases?
    }
}

function checkReverse($descIndexes, $arr) {
    $prev = null;
    for($i = 0; $i < count($descIndexes); $i++)
    {
        if($prev !== null && $prev + 1 != $descIndexes[$i])
        {
            return null;
        }
        $prev = $descIndexes[$i];
    }
    $first = $descIndexes[0];
    $last = $descIndexes[count($descIndexes) - 1];

    $checkStart =
        (isset($arr[$first - 2]) ? $arr[$first - 2] < $arr[$last] : true) &&
        (isset($arr[$last + 1]) ? $arr[$first - 1] < $arr[$last + 1] : true);
    if($checkStart)
    {
        return [$first - 1, $last];
    }
    else
    {
        return null;
    }
}

// Complete the almostSorted function below.
function almostSorted($arr) {
    $descIndexes = getDescIndexes($arr);
    if(empty($descIndexes))
    {
        echo "yes\n";
        return;
    }
    $swap = checkSwap($descIndexes, $arr);
    if($swap)
    {
        echo "yes\nswap ".($swap[0] + 1)." ".($swap[1] + 1)."\n";
        return;
    }

    $reverse = checkReverse($descIndexes, $arr);
    if($reverse)
    {
        echo "yes\nreverse ".($reverse[0] + 1)." ".($reverse[1] + 1)."\n";
        return;
    }

    echo "no\n";
    return;
}

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $n);

fscanf($stdin, "%[^\n]", $arr_temp);

$arr = array_map('intval', preg_split('/ /', $arr_temp, -1, PREG_SPLIT_NO_EMPTY));

almostSorted($arr);

fclose($stdin);
