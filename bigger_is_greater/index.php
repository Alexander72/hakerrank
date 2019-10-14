<?php

function getSortedPermutation($word) {
    $letters = str_split($word);
    sort($letters);
    return implode('', $letters);
}
function getIndexOfLeastLetterGreaterThan($word, $letter) {
    $minIndex = null;
    $minLetter = chr(ord('z') + 1);
    for($i = 0; $i < strlen($word); $i++)
    {
        if($word[$i] > $letter && $word[$i] < $minLetter)
        {
            $minLetter = $word[$i];
            $minIndex = $i;
        }
    }
    return $minIndex;
}


// Complete the biggerIsGreater function below.
function biggerIsGreater($word) {
    $prevLetter = null;
    $i = strlen($word) - 1;
    while($i >= 0 && $word[$i] >= $prevLetter)
    {
        $prevLetter = $word[$i];
        $i--;
    }

    if($i < 0)
    {
        return 'no answer';
    }

    $letter = $word[$i];
    $suffix = substr($word, $i + 1);
    $index = getIndexOfLeastLetterGreaterThan($suffix, $letter);
    $word[$i] = $suffix[$index];
    $suffix[$index] = $letter;

    return substr($word, 0, $i + 1).getSortedPermutation($suffix);
}

$fptr = fopen('php://stdout', "w");

$stdin = fopen("php://stdin", "r");

fscanf($stdin, "%d\n", $T);

for ($T_itr = 0; $T_itr < $T; $T_itr++) {
    $w = '';
    fscanf($stdin, "%[^\n]", $w);

    $result = biggerIsGreater($w);

    fwrite($fptr, $result . "\n");
}

fclose($stdin);
fclose($fptr);
