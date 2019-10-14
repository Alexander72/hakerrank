<?php
function getClosestObstacles($queenY, $queenX, $obstacles) {
    $result = [
        [[null, null], [null, null], [null, null]],
        [[null, null], [null, null], [null, null]],
        [[null, null], [null, null], [null, null]],
    ];
    foreach($obstacles as $obstacle)
    {
        $x = $obstacle[1];
        $y = $obstacle[0];

        if($x == $queenX)
        {
            $comparingFunction = $y > $queenY ? 'min' : 'max';
            $resultIndex = $y > $queenY ? 0 : 2;
            $result[$resultIndex][1][0] = $x;
            $result[$resultIndex][1][1] = $comparingFunction($result[$resultIndex][1][1], $y);

            $result[$resultIndex][1][1] = $result[$resultIndex][1][1] ?? $y;
        }

        if($y == $queenY)
        {
            $comparingFunction = $x > $queenX ? 'min' : 'max';
            $resultIndex =  $x > $queenX ? 2 : 0;
            $result[1][$resultIndex][0] = $comparingFunction($result[1][$resultIndex][0], $x);
            $result[1][$resultIndex][1] = $y;

            $result[1][$resultIndex][0] = $result[1][$resultIndex][0] ?? $x;
        }

        if($y - $x == $queenY - $queenX)
        {
            $comparingFunction = $x > $queenX ? 'min' : 'max';
            $resultIndexRow =  $x > $queenX ? 0 : 2;
            $resultIndexCol =  $x > $queenX ? 2 : 0;
            $result[$resultIndexRow][$resultIndexCol][0] = $comparingFunction($result[$resultIndexRow][$resultIndexCol][0], $x);
            $result[$resultIndexRow][$resultIndexCol][1] = $comparingFunction($result[$resultIndexRow][$resultIndexCol][1], $y);

            $result[$resultIndexRow][$resultIndexCol][0] = $result[$resultIndexRow][$resultIndexCol][0] ?? $x;
            $result[$resultIndexRow][$resultIndexCol][1] = $result[$resultIndexRow][$resultIndexCol][1] ?? $y;
        }

        if($y + $x == $queenX + $queenY)
        {
            $comparingFunctionX = $x > $queenX ? 'min' : 'max';
            $comparingFunctionY = $x > $queenX ? 'max' : 'min';
            $resultIndexRow = $resultIndexCol = $x > $queenX ? 2 : 0;

            $result[$resultIndexRow][$resultIndexCol][0] = $comparingFunctionX($result[$resultIndexRow][$resultIndexCol][0], $x);
            $result[$resultIndexRow][$resultIndexCol][1] = $comparingFunctionY($result[$resultIndexRow][$resultIndexCol][1], $y);

            $result[$resultIndexRow][$resultIndexCol][0] = $result[$resultIndexRow][$resultIndexCol][0] ?? $x;
            $result[$resultIndexRow][$resultIndexCol][1] = $result[$resultIndexRow][$resultIndexCol][1] ?? $y;
        }
    }

    return $result;
}

function getAttackLimits($n, $queenY, $queenX, $closestObstacles) {
    $result = [
        [
            [
                $closestObstacles[0][0][0] ?? $queenX - min($n - $queenY, $queenX - 1) - 1,
                $closestObstacles[0][0][1] ?? $queenY + min($n - $queenY, $queenX - 1) + 1,
            ],
            [
                $closestObstacles[0][1][0] ?? $queenX,
                $closestObstacles[0][1][1] ?? $n + 1,
            ],
            [
                $closestObstacles[0][2][0] ?? $queenX + min($n - $queenY, $n - $queenX) + 1,
                $closestObstacles[0][2][1] ?? $queenY + min($n - $queenY, $n - $queenX) + 1,
            ],
        ],
        [
            [
                $closestObstacles[1][0][0] ?? 0,
                $closestObstacles[1][0][1] ?? $queenY,
            ],
            [null, null],
            [
                $closestObstacles[1][2][0] ?? $n + 1,
                $closestObstacles[1][2][1] ?? $queenY,
            ],
        ],
        [
            [
                $closestObstacles[2][0][0] ?? $queenX - min($queenY - 1, $queenX - 1) - 1,
                $closestObstacles[2][0][1] ?? $queenY - min($queenY - 1, $queenX - 1) - 1,
            ],
            [
                $closestObstacles[2][1][0] ?? $queenX,
                $closestObstacles[2][1][1] ?? 0,
            ],
            [
                $closestObstacles[2][2][0] ?? $queenX + min($queenY - 1, $n - $queenX) + 1,
                $closestObstacles[2][2][1] ?? $queenY - min($queenY - 1, $n - $queenX) - 1,
            ],
        ],
    ];

    return $result;

}

function getSquaresUnderAttack($attackLimits){
    $squaresX = $attackLimits[0][1][1] - $attackLimits[2][1][1] - 2;
    $squaresY = $attackLimits[1][2][0] - $attackLimits[1][0][0] - 2;
    $squaresDiag = $attackLimits[2][2][0] - $attackLimits[0][0][0] - 2;
    $squaresSecDiag = $attackLimits[0][2][0] - $attackLimits[2][0][0] - 2;

    return $squaresX + $squaresY + $squaresDiag + $squaresSecDiag;
}

// Complete the queensAttack function below.
function queensAttack($n, $k, $queenY, $queenX, $obstacles) {
    $closestObstacles = getClosestObstacles($queenY, $queenX, $obstacles);
    $attackLimits = getAttackLimits($n, $queenY, $queenX, $closestObstacles);
    return getSquaresUnderAttack($attackLimits);
}

$fptr = fopen("php://stdout", "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%[^\n]", $nk_temp);
$nk = explode(' ', $nk_temp);

$n = intval($nk[0]);

$k = intval($nk[1]);

fscanf($stdin, "%[^\n]", $r_qC_q_temp);
$r_qC_q = explode(' ', $r_qC_q_temp);

$r_q = intval($r_qC_q[0]);

$c_q = intval($r_qC_q[1]);

$obstacles = array();

for ($i = 0; $i < $k; $i++) {
    fscanf($stdin, "%[^\n]", $obstacles_temp);
    $obstacles[] = array_map('intval', preg_split('/ /', $obstacles_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$result = queensAttack($n, $k, $r_q, $c_q, $obstacles);

fwrite($fptr, $result . "\n");

fclose($stdin);
fclose($fptr);
