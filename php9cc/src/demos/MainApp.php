<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/7/1
 * Time: 16:28
 */

namespace php9cc\demos;

use php9cc\Env;
use php9cc\Exception\NeverRunHere;
use Tiny\Abstracts\AbstractClass;

class MainApp extends AbstractClass
{

    const TK_NUM = 256; // Number literal
    const TK_STR = 257;       // String literal
    const TK_IDENT = 258;     // Identifier
    const TK_ARROW = 259;     // ->
    const TK_EXTERN = 260;    // "extern"
    const TK_TYPEDEF = 261;   // "typedef"
    const TK_INT = 262;       // "int"
    const TK_CHAR = 263;      // "char"
    const TK_VOID = 264;      // "void"
    const TK_STRUCT = 265;    // "struct"
    const TK_BOOL = 266;      // "_Bool"
    const TK_IF = 267;        // "if"
    const TK_ELSE = 268;      // "else"
    const TK_FOR = 269;       // "for"
    const TK_DO = 270;        // "do"
    const TK_WHILE = 271;     // "while"
    const TK_SWITCH = 272;    // "switch"
    const TK_CASE = 273;      // "case"
    const TK_BREAK = 274;     // "break"
    const TK_CONTINUE = 275;  // "continue"
    const TK_EQ = 276;        // ==
    const TK_NE = 277;        // !=
    const TK_LE = 278;        // <=
    const TK_GE = 279;        // >=
    const TK_LOGOR = 280;     // ||
    const TK_LOGAND = 281;    // &&
    const TK_SHL = 282;       // <<
    const TK_SHR = 283;       // >>
    const TK_INC = 284;       // ++
    const TK_DEC = 285;       // --
    const TK_MUL_EQ = 286;    // *=
    const TK_DIV_EQ = 287;    // /=
    const TK_MOD_EQ = 288;    // %=
    const TK_ADD_EQ = 289;    // +=
    const TK_SUB_EQ = 290;    // -=
    const TK_SHL_EQ = 291;    // <<=
    const TK_SHR_EQ = 292;    // >>=
    const TK_AND_EQ = 293;    // &=
    const TK_XOR_EQ = 294;    // ^=
    const TK_OR_EQ = 295;     // |=
    const TK_RETURN = 296;    // "return"
    const TK_SIZEOF = 297;    // "sizeof"
    const TK_ALIGNOF = 298;   // "_Alignof"
    const TK_TYPEOF = 299;    // "typeof"
    const TK_PARAM = 300;     // Function-like macro parameter
    const TK_EOF = 301;       // End marker

    const keywords = [
        "_Alignof" => self::TK_ALIGNOF,
        "_Bool" => self::TK_BOOL,
        "break" => self::TK_BREAK,
        "case" => self::TK_CASE,
        "char" => self::TK_CHAR,
        "continue" => self::TK_CONTINUE,
        "do" => self::TK_DO,
        "else" => self::TK_ELSE,
        "extern" => self::TK_EXTERN,
        "for" => self::TK_FOR,
        "if" => self::TK_IF,
        "int" => self::TK_INT,
        "return" => self::TK_RETURN,
        "sizeof" => self::TK_SIZEOF,
        "struct" => self::TK_STRUCT,
        "switch" => self::TK_SWITCH,
        "typedef" => self::TK_TYPEDEF,
        "typeof" => self::TK_TYPEOF,
        "void" => self::TK_VOID,
        "while" => self::TK_WHILE,
    ];

    const  symbols_2 = [
        "!=" => self::TK_NE,
        "&&" => self::TK_LOGAND,
        "++" => self::TK_INC,
        "--" => self::TK_DEC,
        "->" => self::TK_ARROW,
        "<<" => self::TK_SHL,
        "<=" => self::TK_LE,
        "==" => self::TK_EQ,
        ">=" => self::TK_GE,
        ">>" => self::TK_SHR,
        "||" => self::TK_LOGOR,
        "*=" => self::TK_MUL_EQ,
        "/=" => self::TK_DIV_EQ,
        "%=" => self::TK_MOD_EQ,
        "+=" => self::TK_ADD_EQ,
        "-=" => self::TK_SUB_EQ,
        "&=" => self::TK_AND_EQ,
        "^=" => self::TK_XOR_EQ,
        "|=" => self::TK_OR_EQ,
    ];

    const  symbols_3 = [
        "<<=" => self::TK_SHL_EQ,
        ">>=" => self::TK_SHR_EQ,
    ];

    const symbols_c = "+-*/;=(),{}<>[]&.!?:|^%~#";

    const ord_0 = 48;

    private array $_options = [];

    public static Env $env;

    public static array $ord = [];
    public static array $isdigit = [];
    public static array $isalpha = [];
    public static array $isoctal = [];
    public static array $isxdigit = [];

    public function __construct(array $options = [])
    {
        $this->_options = $options;
        if (empty(self::$ord)) {
            for ($t = 0; $t <= 255; $t++) {
                $char = chr($t);
                self::$ord[$char] = $t;

                if (Env::isdigit($char)) {
                    self::$isdigit[$char] = $t - self::ord_0;
                }
                if (Env::isalpha($char)) {
                    self::$isalpha[$char] = $t;
                }
                if (Env::isoctal($char)) {
                    self::$isoctal[$char] = $t - self::ord_0;
                }
                if (Env::isxdigit($char)) {
                    self::$isxdigit[$char] = Env::hex($char);
                }
            }
        }
    }

    public static function parserArgs(array $args = []): array
    {
        return $args;
    }

    /* =====================================================================================
    ========================================================================================
    ===================================================================================== */

    public function process(string $inputFile, string $outputFile)
    {
        $tokens = self::tokenize($inputFile, true, $this->_options);
    }

    private static function tokenize(string $inputFile, bool $add_eof, array $opt): array
    {
        $start = microtime(true);

        $buf = file_get_contents($inputFile);
        $buf = self::replace_crlf($buf);
        $buf = self::remove_backslash_newline($buf);

        self::$env = new Env(null, $inputFile, $buf);
        $file = self::$env->path;

        try {
            self::$env->scan();
        } catch (NeverRunHere $e) {
            echo "ERR in scan {$file} err:" . $e->getMessage();
        }
        if ($add_eof) {
            self::$env->add(self::TK_EOF, -1);
        }
        $used = intval((microtime(true) - $start) * 1000);
        $tokens = self::$env->tokens;
        $tl = count($tokens);
        echo "done file {$file}, count:{$tl} use:{$used}ms\n";
        return $tokens;
    }

    private static function replace_crlf(string $buf): string
    {
        return str_replace("\r\n", "\n", $buf);
    }

    private static function remove_backslash_newline(string $buf): string
    {
        $lines = explode("\n", $buf);
        $ret = [];
        $backslash = false;
        foreach ($lines as $line) {
            $_line = rtrim($line);

            $_backslash = $backslash;
            if (!empty($_line) && $_line[strlen($_line) - 1] == '\\') {
                $line = substr($line, 0, -1) . " ";
                $backslash = true;
            } else {
                $backslash = false;
            }

            if ($_backslash) {
                $ret[count($ret) - 1] .= $line;
            } else {
                $ret[] = $line;
            }
        }
        return join("\n", $ret);
    }

}