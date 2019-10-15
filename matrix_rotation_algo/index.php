<?php
function getLayerLength($matrix, $layer) {
    $rows = count($matrix);
    $columns = count($matrix[0]);

    return 2 * ($columns + $rows - 2) - 8 * $layer;
}

function getLayerAsArray($matrix, $layer) {
    $rows = count($matrix);
    $columns = count($matrix[0]);
    $result = [];
    foreach(getLayerCoordIterator($columns, $rows, $layer) as $coords)
    {
        $result[] = $matrix[$coords[0]][$coords[1]];
    }

    return $result;
}

function rotateArray($arr, $steps) {
    return array_merge(array_slice($arr, -1 * $steps), array_slice($arr, 0, count($arr) - $steps));
}
function getLayerCoordIterator($columns, $rows, $layer): Generator {
    $result = [];
    for($i = $layer; $i < $rows - $layer - 1; $i++)
    {
        $result[] = [$i, $layer];
    }

    for($j = $layer; $j < $columns - $layer - 1; $j++)
    {
        $result[] = [$rows - $layer - 1, $j];
    }

    for($i = $rows - $layer - 1; $i > $layer; $i--)
    {
        $result[] = [$i, $columns - $layer - 1];
    }

    for($j = $columns - $layer - 1; $j > $layer; $j--)
    {
        $result[] = [$layer, $j];
    }

    yield from $result;
}
function rotateLayer($matrix, $layer, $steps) {
    $rows = count($matrix);
    $columns = count($matrix[0]);

    $layerArray = getLayerAsArray($matrix, $layer);
    $layerArray = rotateArray($layerArray, $steps);
    $i = 0;
    foreach(getLayerCoordIterator($columns, $rows, $layer) as $coords)
    {
        $matrix[$coords[0]][$coords[1]] = $layerArray[$i++];
    }

    return $matrix;
}

// Complete the matrixRotation function below.
function matrixRotation($matrix, $r) {
    for($i = 0; $i < (min(count($matrix), count($matrix[0])) / 2); $i++)
    {
        $layerLength = getLayerLength($matrix, $i);
        $matrix = rotateLayer($matrix, $i, $r % $layerLength);
    }

    return $matrix;
}

$mnr = explode(' ', rtrim(fgets(STDIN)));

$m = intval($mnr[0]);

$n = intval($mnr[1]);

$r = intval($mnr[2]);

$matrix = array();

for ($i = 0; $i < $m; $i++) {
    $matrix_temp = rtrim(fgets(STDIN));

    $matrix[] = array_map('intval', preg_split('/ /', $matrix_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$matrix = matrixRotation($matrix, $r);

for($i = 0; $i < $m; $i++)
{
    echo implode(" ", $matrix[$i])."\n";
}
