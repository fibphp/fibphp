<?php

namespace phpsonarTests\import\multiLevelTest\kitchen;

class Pizza
{
    private $toppings = null;

    public function __construct($toppings)
    {
        $this->toppings = $toppings;
    }

    public function get_toppings()
    {
        return $this->toppings;
    }
}
  