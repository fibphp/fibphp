<?php


namespace php9cc;


class Token
{
    public int $ty = -1;  // Token type

    public int $val = 0;  // Number literal
    public string $name = '';  // Identifier

    public string $str = "";
    public int $len = 0;

    public bool $stringize = false;

    public int $start = -1;
    public int $end = -1;

    public Env $env;

    public function __construct(int $ty, int $start, Env $env)
    {
        $this->ty = $ty;
        $this->start = $start;
        $this->env = $env;
    }
}