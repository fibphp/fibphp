<?php
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor/autoload.php');

use phpsonarTests\import\multiLevelTest\kitchen\Pizza;

$pizza = new Pizza(['mushroom', 'sauage', 'cheeze']);
var_dump($pizza->get_toppings());
