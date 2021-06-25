<?php

namespace chibicc\Struct;


class Obj
{
    private Obj $next;
    private string $name;    // Variable name
    private Type $ty;      // Type
    private Token $tok;    // representative token
    private bool $is_local; // local or global/function
    private int $align;     // alignment

    // Local variable
    private int $offset;

    // Global variable or function
    private bool $is_function;
    private bool $is_definition;
    private bool $is_static;

    // Global variable
    private bool $is_tentative;
    private bool $is_tls;
    private string $init_data;
    private Relocation $rel;

    // Function
    private bool $is_inline;
    private Obj $params;
    private Node $body;
    private Obj $locals;
    private Obj $va_area;
    private Obj $alloca_bottom;
    private int $stack_size;

    // Static inline function
    private bool $is_live;
    private bool $is_root;
    private array $refs;

    /**
     * Obj constructor.
     * @param Obj $next
     * @param string $name
     * @param Type $ty
     * @param Token $tok
     * @param bool $is_local
     * @param int $align
     * @param int $offset
     * @param bool $is_function
     * @param bool $is_definition
     * @param bool $is_static
     * @param bool $is_tentative
     * @param bool $is_tls
     * @param string $init_data
     * @param Relocation $rel
     * @param bool $is_inline
     * @param Obj $params
     * @param Node $body
     * @param Obj $locals
     * @param Obj $va_area
     * @param Obj $alloca_bottom
     * @param int $stack_size
     * @param bool $is_live
     * @param bool $is_root
     * @param array $refs
     */
    public function __construct(Obj $next, string $name, Type $ty, Token $tok, bool $is_local, int $align, int $offset, bool $is_function, bool $is_definition, bool $is_static, bool $is_tentative, bool $is_tls, string $init_data, Relocation $rel, bool $is_inline, Obj $params, Node $body, Obj $locals, Obj $va_area, Obj $alloca_bottom, int $stack_size, bool $is_live, bool $is_root, array $refs)
    {
        $this->next = $next;
        $this->name = $name;
        $this->ty = $ty;
        $this->tok = $tok;
        $this->is_local = $is_local;
        $this->align = $align;
        $this->offset = $offset;
        $this->is_function = $is_function;
        $this->is_definition = $is_definition;
        $this->is_static = $is_static;
        $this->is_tentative = $is_tentative;
        $this->is_tls = $is_tls;
        $this->init_data = $init_data;
        $this->rel = $rel;
        $this->is_inline = $is_inline;
        $this->params = $params;
        $this->body = $body;
        $this->locals = $locals;
        $this->va_area = $va_area;
        $this->alloca_bottom = $alloca_bottom;
        $this->stack_size = $stack_size;
        $this->is_live = $is_live;
        $this->is_root = $is_root;
        $this->refs = $refs;
    }


}