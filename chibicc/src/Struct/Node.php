<?php


namespace chibicc\Struct;


class Node
{
    private string $kind; // Node kind
    private Node $next;    // Next node
    private Type $ty;      // Type, e.g. int or pointer to int
    private Token $tok;    // Representative token

    private Node $lhs;     // Left-hand side
    private Node $rhs;     // Right-hand side

    // "if" or "for" statement
    private Node $cond;
    private Node $then;
    private Node $els;
    private Node $init;
    private Node $inc;

    // "break" and "continue" labels
    private string $brk_label;
    private string $cont_label;

    // Block or statement expression
    private Node $body;

    // Struct member access
    private Member $member;

    // Function call
    private Type $func_ty;
    private Node $args;
    private bool $pass_by_stack;
    private Obj $ret_buffer;

    // Goto or labeled statement, or labels-as-values
    private string $label;
    private string $unique_label;
    private Node $goto_next;

    // Switch
    private Node $case_next;
    private Node $default_case;

    // Case
    private int $begin;
    private int $end;

    // "asm" string literal
    private string $asm_str;

    // Atomic compare-and-swap
    private Node $cas_addr;
    private Node $cas_old;
    private Node $cas_new;

    // Atomic op= operators
    private Obj $atomic_addr;
    private Node $atomic_expr;

    // Variable
    private Obj $var;

    // Numeric literal
    private int $val;
    private float $fval;

    /**
     * Node constructor.
     * @param string $kind
     * @param Node $next
     * @param Type $ty
     * @param Token $tok
     * @param Node $lhs
     * @param Node $rhs
     * @param Node $cond
     * @param Node $then
     * @param Node $els
     * @param Node $init
     * @param Node $inc
     * @param string $brk_label
     * @param string $cont_label
     * @param Node $body
     * @param Member $member
     * @param Type $func_ty
     * @param Node $args
     * @param bool $pass_by_stack
     * @param Obj $ret_buffer
     * @param string $label
     * @param string $unique_label
     * @param Node $goto_next
     * @param Node $case_next
     * @param Node $default_case
     * @param int $begin
     * @param int $end
     * @param string $asm_str
     * @param Node $cas_addr
     * @param Node $cas_old
     * @param Node $cas_new
     * @param Obj $atomic_addr
     * @param Node $atomic_expr
     * @param Obj $var
     * @param int $val
     * @param float $fval
     */
    public function __construct(string $kind, Node $next, Type $ty, Token $tok, Node $lhs, Node $rhs, Node $cond, Node $then, Node $els, Node $init, Node $inc, string $brk_label, string $cont_label, Node $body, Member $member, Type $func_ty, Node $args, bool $pass_by_stack, Obj $ret_buffer, string $label, string $unique_label, Node $goto_next, Node $case_next, Node $default_case, int $begin, int $end, string $asm_str, Node $cas_addr, Node $cas_old, Node $cas_new, Obj $atomic_addr, Node $atomic_expr, Obj $var, int $val, float $fval)
    {
        $this->kind = $kind;
        $this->next = $next;
        $this->ty = $ty;
        $this->tok = $tok;
        $this->lhs = $lhs;
        $this->rhs = $rhs;
        $this->cond = $cond;
        $this->then = $then;
        $this->els = $els;
        $this->init = $init;
        $this->inc = $inc;
        $this->brk_label = $brk_label;
        $this->cont_label = $cont_label;
        $this->body = $body;
        $this->member = $member;
        $this->func_ty = $func_ty;
        $this->args = $args;
        $this->pass_by_stack = $pass_by_stack;
        $this->ret_buffer = $ret_buffer;
        $this->label = $label;
        $this->unique_label = $unique_label;
        $this->goto_next = $goto_next;
        $this->case_next = $case_next;
        $this->default_case = $default_case;
        $this->begin = $begin;
        $this->end = $end;
        $this->asm_str = $asm_str;
        $this->cas_addr = $cas_addr;
        $this->cas_old = $cas_old;
        $this->cas_new = $cas_new;
        $this->atomic_addr = $atomic_addr;
        $this->atomic_expr = $atomic_expr;
        $this->var = $var;
        $this->val = $val;
        $this->fval = $fval;
    }

}