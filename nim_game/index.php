<?php

function getCacheKey($pile){
    return implode('_', $pile);
}

function canMakeTurn($pile) {
    foreach($pile as $heap)
    {
        if($heap < 0) return false;
    }
    return true;
}

// Complete the nimGame function below.
function nimGame($pile) {
    static $cache = [];
    $cacheKey = getCacheKey($pile);

    if(isset($cache[$cacheKey]))
    {
        return $cache[$cacheKey];
    }

    $result = false;
    foreach($pile as $heapIndex => $heapSize)
    {
        for($i = 1; $i <= $heapSize; $i++)
        {
            $newPile = $pile;
            $newPile[$heapIndex] -= $i;

            if(canMakeTurn($newPile) && !nimGame($newPile))
            {
                $result = true;
                break 2;
            }
        }
    }

    $cache[$cacheKey] = $result;

    return $cache[$cacheKey];
}

$fptr = fopen(getenv("OUTPUT_PATH"), "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $g);

for ($g_itr = 0; $g_itr < $g; $g_itr++) {
    fscanf($stdin, "%d\n", $n);

    fscanf($stdin, "%[^\n]", $pile_temp);

    $pile = array_map('intval', preg_split('/ /', $pile_temp, -1, PREG_SPLIT_NO_EMPTY));

    $result = nimGame($pile) ? 'First' : 'Second';

    fwrite($fptr, $result . "\n");
}

fclose($stdin);
fclose($fptr);
