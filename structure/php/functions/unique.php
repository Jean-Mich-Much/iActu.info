<?php function unique($a)
{
    $b = '';
    $c = array_merge(range(0, 9), range('a', 'z'));
    for ($i = 0; $i < $a; ++$i) {
        $b .= $c[array_rand($c)];
    }

    return $b;
}
