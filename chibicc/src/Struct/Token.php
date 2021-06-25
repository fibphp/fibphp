<?php


namespace chibicc\Struct;


class Token
{

    private string $kind;   // Token kind
    private Token $next;      // Next token
    private int $val;      // If kind is TK_NUM, its value
    private float $fval; // If kind is TK_NUM, its value
    private string $loc;        // Token location
    private int $len;          // Token length
    private Type $ty;         // Used if TK_NUM or TK_STR
    private string $str;        // String literal contents including terminating '\0'

    private File $file;       // Source location
    private string $filename;   // Filename
    private int $line_no;      // Line number
    private int $line_delta;   // Line number
    private bool $at_bol;      // True if this token is at beginning of line
    private bool $has_space;   // True if this token follows a space character
    private Hideset $hideset; // For macro expansion
    private Token $origin;    // If this is expanded from a macro, the original token

    /**
     * Token constructor.
     * @param string $kind
     * @param Token $next
     * @param int $val
     * @param float $fval
     * @param string $loc
     * @param int $len
     * @param Type $ty
     * @param string $str
     * @param File $file
     * @param string $filename
     * @param int $line_no
     * @param int $line_delta
     * @param bool $at_bol
     * @param bool $has_space
     * @param Hideset $hideset
     * @param Token $origin
     */
    public function __construct(string $kind, Token $next, int $val, float $fval, string $loc, int $len, Type $ty, string $str, File $file, string $filename, int $line_no, int $line_delta, bool $at_bol, bool $has_space, Hideset $hideset, Token $origin)
    {
        $this->kind = $kind;
        $this->next = $next;
        $this->val = $val;
        $this->fval = $fval;
        $this->loc = $loc;
        $this->len = $len;
        $this->ty = $ty;
        $this->str = $str;
        $this->file = $file;
        $this->filename = $filename;
        $this->line_no = $line_no;
        $this->line_delta = $line_delta;
        $this->at_bol = $at_bol;
        $this->has_space = $has_space;
        $this->hideset = $hideset;
        $this->origin = $origin;
    }


}