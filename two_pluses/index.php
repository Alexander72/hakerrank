<?php
function checkSquaresMovedFromCenter($grid, $i, $j, $k) {
    return
        isset($grid[$i+$k][$j]) && $grid[$i+$k][$j] == 'G' &&
        isset($grid[$i-$k][$j]) && $grid[$i-$k][$j] == 'G' &&
        isset($grid[$i][$j+$k]) && $grid[$i][$j+$k] == 'G' &&
        isset($grid[$i][$j-$k]) && $grid[$i][$j-$k] == 'G';

}

function getPlusMaxSizeWithCenterIn($grid, $i, $j): int {
    if($grid[$i][$j] == 'B')
    {
        return 0;
    }

    $k = 0;
    while(checkSquaresMovedFromCenter($grid, $i, $j, $k))
    {
        $k++;
    }

    return $k;
}

function getPlusArea($size): int {
    return $size ? ($size - 1) * 4 + 1 : 0;
}

function markPlusAs($grid, $centerX, $centerY, $blockedPlusSize, $mark): array {
    for($i = 0; $i < $blockedPlusSize; $i++)
    {
        $grid[$centerX + $i][$centerY] = $mark;
        $grid[$centerX - $i][$centerY] = $mark;
        $grid[$centerX][$centerY + $i] = $mark;
        $grid[$centerX][$centerY - $i] = $mark;
    }

    return $grid;
}

function blockPlus($grid, $centerX, $centerY, $blockedPlusSize): array {
    return markPlusAs($grid, $centerX, $centerY, $blockedPlusSize, 'B');
}

function unlockPlus($grid, $centerX, $centerY, $blockedPlusSize): array {
    return markPlusAs($grid, $centerX, $centerY, $blockedPlusSize, 'G');
}

function getMaxPlusSizeInGrid($grid): int {
    $result = 0;

    for($i = 0; $i < count($grid); $i++)
    {
        for($j = 0; $j < count($grid[0]); $j++)
        {
            $result = max($result, getPlusMaxSizeWithCenterIn($grid, $i, $j));
        }
    }

    return $result;
}

// Complete the twoPluses function below.
function twoPluses($grid) {
    $grid = array_map('str_split', $grid);
    $result = 0;
    for($i = 0; $i < count($grid); $i++)
    {
        for($j = 0; $j < count($grid[0]); $j++)
        {
            $plusMaxSizeWithCenterInIJ = getPlusMaxSizeWithCenterIn($grid, $i, $j);
            if(!$plusMaxSizeWithCenterInIJ)
            {
                continue;
            }
            for($sizeOfBlockedPlus = 1; $sizeOfBlockedPlus <= $plusMaxSizeWithCenterInIJ; $sizeOfBlockedPlus++)
            {
                $grid = blockPlus($grid, $i, $j, $sizeOfBlockedPlus);
                $maxPlusSizeOnOtherGrid = getMaxPlusSizeInGrid($grid);
                $grid = unlockPlus($grid, $i, $j, $sizeOfBlockedPlus);
                $result = max($result, getPlusArea($sizeOfBlockedPlus) * getPlusArea($maxPlusSizeOnOtherGrid));
            }
        }
    }

    return $result;
}

$fptr = fopen("php://stdout", "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%[^\n]", $nm_temp);
$nm = explode(' ', $nm_temp);

$n = intval($nm[0]);

$m = intval($nm[1]);

$grid = array();

for ($i = 0; $i < $n; $i++) {
    $grid_item = '';
    fscanf($stdin, "%[^\n]", $grid_item);
    $grid[] = $grid_item;
}

$result = twoPluses($grid);

fwrite($fptr, $result . "\n");

fclose($stdin);
fclose($fptr);
