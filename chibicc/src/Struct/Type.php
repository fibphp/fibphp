<?php

namespace chibicc\Struct;


class Type
{
    private string $kind;
    private int $size;           // sizeof() value
    private int $align;          // alignment
    private bool $is_unsigned;   // unsigned or signed
    private bool $is_atomic;     // true if _Atomic
    private Type $origin;       // for type compatibility check

    // Pointer-to or array-of type. We intentionally use the same member
    // to represent pointer/array duality in C.
    //
    // In many contexts in which a pointer is expected, we examine this
    // member instead of "kind" member to determine whether a type is a
    // pointer or not. That means in many contexts "array of T" is
    // naturally handled as if it were "pointer to T", as required by
    // the C spec.
    private Type $base;

    // Declaration
    private Token $name;
    private Token $name_pos;

    // Array
    private int $array_len;

    // Variable-length array
    private Node $vla_len; // # of elements
    private Obj $vla_size; // sizeof() value

    // Struct
    private Member $members;
    private bool $is_flexible;
    private bool $is_packed;

    // Function type
    private Type $return_ty;
    private Type $params;
    private bool $is_variadic;
    private Type $next;

    /**
     * Type constructor.
     * @param string $kind
     * @param int $size
     * @param int $align
     * @param bool $is_unsigned
     * @param bool $is_atomic
     * @param Type $origin
     * @param Type $base
     * @param Token $name
     * @param Token $name_pos
     * @param int $array_len
     * @param Node $vla_len
     * @param Obj $vla_size
     * @param Member $members
     * @param bool $is_flexible
     * @param bool $is_packed
     * @param Type $return_ty
     * @param Type $params
     * @param bool $is_variadic
     * @param Type $next
     */
    public function __construct(string $kind, int $size, int $align, bool $is_unsigned, bool $is_atomic, Type $origin, Type $base, Token $name, Token $name_pos, int $array_len, Node $vla_len, Obj $vla_size, Member $members, bool $is_flexible, bool $is_packed, Type $return_ty, Type $params, bool $is_variadic, Type $next)
    {
        $this->kind = $kind;
        $this->size = $size;
        $this->align = $align;
        $this->is_unsigned = $is_unsigned;
        $this->is_atomic = $is_atomic;
        $this->origin = $origin;
        $this->base = $base;
        $this->name = $name;
        $this->name_pos = $name_pos;
        $this->array_len = $array_len;
        $this->vla_len = $vla_len;
        $this->vla_size = $vla_size;
        $this->members = $members;
        $this->is_flexible = $is_flexible;
        $this->is_packed = $is_packed;
        $this->return_ty = $return_ty;
        $this->params = $params;
        $this->is_variadic = $is_variadic;
        $this->next = $next;
    }

}