<?php

function foo($x){
	return $x;
}


function bar($y){
	return foo($y);
}

function baz1(){
	return foo(1);
}

function baz2(){
	return foo('hi');
}

baz1();
baz2();
