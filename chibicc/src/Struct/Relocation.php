<?php


namespace chibicc\Struct;


class Relocation
{
    private Relocation $next;
    private int $offset;
    private string $label;
    private int $addend;

    /**
     * Relocation constructor.
     * @param Relocation $next
     * @param int $offset
     * @param string $label
     * @param int $addend
     */
    public function __construct(Relocation $next, int $offset, string $label, int $addend)
    {
        $this->next = $next;
        $this->offset = $offset;
        $this->label = $label;
        $this->addend = $addend;
    }
}