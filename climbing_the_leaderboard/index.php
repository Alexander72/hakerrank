<?php

// Complete the climbingLeaderboard function below.
function climbingLeaderboard($scores, $aliceResults): array {
    $scores = array_values(array_unique($scores));
    $i = count($scores) ;
    $score[-1] = max($scores[0], $aliceResults[count($aliceResults) - 1]);

    $result = [];
    foreach($aliceResults as $aliceResult)
    {
        while($i > 0 && $scores[$i - 1] <= $aliceResult)
        {
            $i--;
        }

        $result[] = $i + 1;
    }

    return $result;
}

$fptr = fopen('php://stdout', "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $scores_count);

fscanf($stdin, "%[^\n]", $scores_temp);

$scores = array_map('intval', preg_split('/ /', $scores_temp, -1, PREG_SPLIT_NO_EMPTY));

fscanf($stdin, "%d\n", $alice_count);

fscanf($stdin, "%[^\n]", $alice_temp);

$alice = array_map('intval', preg_split('/ /', $alice_temp, -1, PREG_SPLIT_NO_EMPTY));

$result = climbingLeaderboard($scores, $alice);

fwrite($fptr, implode("\n", $result) . "\n");

fclose($stdin);
fclose($fptr);
