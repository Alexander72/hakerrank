<?php


// Complete the gridSearch function below.
function gridSearch($G, $P) {
    $i = 0;
    $startIndexes = $foundMatchFirstStringIndex = null;
    for($j = 0; $j < count($G); $j++)
    {
        $row = $G[$j];
        preg_match_all('/(?='.$P[$i].')/', $row, $matches, PREG_OFFSET_CAPTURE);
        if(!empty($matches[0]))
        {
            $potentialStartIndexes = array_column($matches[0], 1);
            if($startIndexes !== null)
            {
                $startIndexes = array_intersect($potentialStartIndexes, $startIndexes);
                if(!$startIndexes)
                {
                    $i = 0;
                    $startIndexes = null;
                    $j = $foundMatchFirstStringIndex ?? $j;
                    $foundMatchFirstStringIndex = null;
                }
                else
                {
                    $i++;
                }
            }
            else
            {
                $foundMatchFirstStringIndex = $j;
                $startIndexes = $potentialStartIndexes;
                $i++;
            }
        }
        else
        {
            $i = 0;
            $startIndexes = null;
            $j = $foundMatchFirstStringIndex ?? $j;
            $foundMatchFirstStringIndex = null;
        }

        if($i == count($P))
        {
            return 'YES';
        }
    }

    return 'NO';
}

$fptr = fopen('php://stdout', "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $t);

for ($t_itr = 0; $t_itr < $t; $t_itr++) {
    fscanf($stdin, "%[^\n]", $RC_temp);
    $RC = explode(' ', $RC_temp);

    $R = intval($RC[0]);

    $C = intval($RC[1]);

    $G = array();

    for ($i = 0; $i < $R; $i++) {
        $G_item = '';
        fscanf($stdin, "%[^\n]", $G_item);
        $G[] = $G_item;
    }

    fscanf($stdin, "%[^\n]", $rc_temp);
    $rc = explode(' ', $rc_temp);

    $r = intval($rc[0]);

    $c = intval($rc[1]);

    $P = array();

    for ($i = 0; $i < $r; $i++) {
        $P_item = '';
        fscanf($stdin, "%[^\n]", $P_item);
        $P[] = $P_item;
    }

    $result = gridSearch($G, $P);

    fwrite($fptr, $result . "\n");
}

fclose($stdin);
fclose($fptr);
