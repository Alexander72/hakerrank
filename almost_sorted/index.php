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
        $second = $descIndexes[1];
        $firstCheck = (isset($arr[$first - 2]) ? $arr[$first - 2] < $arr[$second] : true) && $arr[$second] < $arr[$first];
        $secondCheck = $arr[$second - 1] < $arr[$first - 1] && (isset($arr[$second + 1]) ? $arr[$first - 1] < $arr[$second + 1] : true);
        if($firstCheck && $secondCheck)
        {
            return [$first - 1, $second];
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

function generateTest() {
    for($i = 0; $i < rand(10, 100); $i++)
    {
        $arr[] = rand(10, 100);
    }
    $arr = array_unique($arr);

    $case = rand(0, 2);
    if($case == 0)// impossible
    {
        $result = [$arr, "no\n"];
    }
    elseif($case == 1)// swap
    {
        sort($arr);
        $start = rand(0, count($arr) - 1);
        $finish = $start;
        while($finish == $start) $finish = rand(0, count($arr) - 1);

        $tmp = $arr[$start];
        $arr[$start] = $arr[$finish];
        $arr[$finish] = $tmp;

        $result = [$arr, "yes\nswap ".(min($start, $finish)+1)." ".(max($start, $finish)+1)."\n"];
    }
    else// reverse
    {
        sort($arr);
        $start = rand(0, count($arr) - 1);
        $finish = $start;
        while($finish == $start) $finish = rand(0, count($arr) - 1);

        $tmp = $start;
        $start = min($start, $finish);
        $finish = max($finish, $tmp);

        $firstPart = array_slice($arr, 0, $start);
        $lastPart = array_slice($arr, $finish + 1);
        $reversedPart = array_reverse(array_slice($arr, $start, $finish - $start + 1));
        $arr = array_merge($firstPart, $reversedPart, $lastPart);

        $result = [$arr, "yes\nreverse ".(min($start, $finish)+1)." ".(max($start, $finish)+1)."\n"];
    }

    yield $result;
}

// Complete the almostSorted function below.
function almostSorted($arr) {
    $descIndexes = getDescIndexes($arr);
    if(empty($descIndexes))
    {
        return "yes\n";
    }
    $swap = checkSwap($descIndexes, $arr);
    if($swap)
    {
        return "yes\nswap ".($swap[0] + 1)." ".($swap[1] + 1)."\n";
    }

    $reverse = checkReverse($descIndexes, $arr);
    if($reverse)
    {
        return "yes\nreverse ".($reverse[0] + 1)." ".($reverse[1] + 1)."\n";
    }

    return "no\n";
}

//foreach(generateTest() as $testCase)
//{
//    $result = almostSorted($testCase[0]);
//    if($result != $testCase[1])
//    {
//        echo "TEST CASE\n";
//        echo implode(' ', $testCase[0])."\n";
//        echo "RESULT\n";
//        echo $result;
//        echo "EXPECTED\n";
//        echo $testCase[1]."\n";
//        die();
//    }
//}

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $n);

fscanf($stdin, "%[^\n]", $arr_temp);

$arr = array_map('intval', preg_split('/ /', $arr_temp, -1, PREG_SPLIT_NO_EMPTY));

echo almostSorted($arr);

fclose($stdin);
