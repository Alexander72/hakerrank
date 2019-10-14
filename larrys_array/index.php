<?php

function isSorted($A) {
    for($i = 0; $i < count($A); $i++)
    {
        if($A[$i] != $i + 1)
        {
            return false;
        }
    }

    return true;
}

function findThirdIndex($A, $i, $correctNumberIndex) {
    if(isset($A[$i + 1]) && $i + 1 != $correctNumberIndex)
    {
        return $i + 1;
    }
    elseif(isset($A[$i + 2]))
    {
        return $i + 2;
    }

    return null;
}

function doSwap(&$A, $i, $j, $k) {
    if($j > $k)
    {
        $tmp = $k;
        $k = $j;
        $j = $tmp;
    }

    $tmp = $A[$i];
    $A[$i] = $A[$j];
    $A[$j] = $A[$k];
    $A[$k] = $tmp;
}

// Complete the larrysArray function below.
function larrysArray($A, $i = 0) {
    if(isSorted($A))
    {
        return true;
    }

    while($A[$i] == $i + 1)
    {
        $i++;
    }

    $correctNumberIndex = array_search($i + 1, $A);
    $thirdIndex = findThirdIndex($A, $i, $correctNumberIndex);
    if($thirdIndex === null)
    {
        return false;
    }
    else
    {
        doSwap($A, $i, $thirdIndex, $correctNumberIndex);
        if($A[$i] != $i + 1)
        {
            doSwap($A, $i, $thirdIndex, $correctNumberIndex);
        }

        if($A[$i] != $i + 1)
        {
            return false;
        }
        else
        {
            return larrysArray($A, $i + 1);
        }
    }
}

$fptr = fopen('php://stdout', "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $t);

for ($t_itr = 0; $t_itr < $t; $t_itr++) {
    fscanf($stdin, "%d\n", $n);

    fscanf($stdin, "%[^\n]", $A_temp);

    $A = array_map('intval', preg_split('/ /', $A_temp, -1, PREG_SPLIT_NO_EMPTY));

    $result = larrysArray($A) ? 'YES' : 'NO';

    fwrite($fptr, $result . "\n");
}

fclose($stdin);
fclose($fptr);
