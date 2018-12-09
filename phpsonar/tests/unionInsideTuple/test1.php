<?php

function x($q)
{
    if ($q == 0) {
        return [2, True];
    } else {
        return ["hi", False];
    }
}

list($y, $z) = x(3);
