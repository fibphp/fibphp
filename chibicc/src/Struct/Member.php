<?php

namespace chibicc\Struct;


class Member
{
    private Member $next;
    private Type $ty;
    private Token $tok; // for error message
    private Token $name;
    private int $idx;
    private int $align;
    private int $offset;

    // Bitfield
    private bool $is_bitfield;
    private int $bit_offset;
    private int $bit_width;

    /**
     * Member constructor.
     * @param Member $next
     * @param Type $ty
     * @param Token $tok
     * @param Token $name
     * @param int $idx
     * @param int $align
     * @param int $offset
     * @param bool $is_bitfield
     * @param int $bit_offset
     * @param int $bit_width
     */
    public function __construct(Member $next, Type $ty, Token $tok, Token $name, int $idx, int $align, int $offset, bool $is_bitfield, int $bit_offset, int $bit_width)
    {
        $this->next = $next;
        $this->ty = $ty;
        $this->tok = $tok;
        $this->name = $name;
        $this->idx = $idx;
        $this->align = $align;
        $this->offset = $offset;
        $this->is_bitfield = $is_bitfield;
        $this->bit_offset = $bit_offset;
        $this->bit_width = $bit_width;
    }

}