<?php

function test1($x)
{
    $a = "hi";
    if($x > 1){
	    $a = 3;
    }
        
    echo $a;
}


function test2($x)
{
    $a = "hi";
    if($x > 1){
	    $a = 3;
    } else {
	    $a = true;
    }
        
    echo $a;
}

function test3($x)
{
    $a = "hi";
    if($x > 1){
	    $a = 3;
    } else {
	    if(x < 10){
		    $a = true;
	    } else {
		    $a = "hi";
	    }
    }
    echo $a;
}

function test4($x)
{
    $a = "hi";
    $b = "foo";
    if($x > 1){
	    $a = 3;
	    $b = 4;
    } else {
	    if(x < 10){
		    $b = true;
	    } else {
		    $a = "hi";
		    $b = false;
	    }
    }
    echo $a, $b;
}