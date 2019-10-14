<?php

// Complete the organizingContainers function below.
function organizingContainers($containers) {
    $containerVolumes = array_map(function($container){return array_sum($container);}, $containers);
    $colorsCount = [];
    for($i = 0; $i < count($containers[0]); $i++)
    {
        $colorsCount[] = array_sum(array_column($containers, $i));
    }

    return empty(array_diff($containerVolumes, $colorsCount)) && empty(array_diff($colorsCount, $containerVolumes));
}

$fptr = fopen('php://stdout', "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $q);

for ($q_itr = 0; $q_itr < $q; $q_itr++) {
    fscanf($stdin, "%d\n", $n);

    $container = array();

    for ($i = 0; $i < $n; $i++) {
        fscanf($stdin, "%[^\n]", $container_temp);
        $container[] = array_map('intval', preg_split('/ /', $container_temp, -1, PREG_SPLIT_NO_EMPTY));
    }

    $result = organizingContainers($container) ? 'Possible' : 'Impossible';

    fwrite($fptr, $result . "\n");
}

fclose($stdin);
fclose($fptr);
