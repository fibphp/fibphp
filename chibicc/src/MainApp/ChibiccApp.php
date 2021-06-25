<?php
/**
 * Created by PhpStorm.
 * User: kongl
 * Date: 2018/7/1
 * Time: 16:28
 */

namespace chibicc\MainApp;

use chibicc\Env;
use chibicc\Program;
use chibicc\Token;
use Tiny\Abstracts\AbstractClass;

class ChibiccApp extends AbstractClass
{
    use CodegenTrait, ParseTrait, PreprocessTrait, TokenizeTrait, TypeTrait;

    public array $options = [];
    public ?Env $env;

    const FILE_NONE = 'FILE_NONE';
    const FILE_C = 'FILE_C';
    const FILE_ASM = 'FILE_ASM';
    const FILE_OBJ = 'FILE_OBJ';
    const FILE_AR = 'FILE_AR';
    const FILE_DSO = 'FILE_DSO';

    const TK_IDENT = 'TK_IDENT';   // Identifiers
    const TK_PUNCT = 'TK_PUNCT';   // Punctuators
    const TK_KEYWORD = 'TK_KEYWORD'; // Keywords
    const TK_STR = 'TK_STR';     // String literals
    const TK_NUM = 'TK_NUM';     // Numeric literals
    const TK_PP_NUM = 'TK_PP_NUM';  // Preprocessing numbers
    const TK_EOF = 'TK_EOF';     // End-of-file markers

    const TY_VOID = 'TY_VOID';
    const TY_BOOL = 'TY_BOOL';
    const TY_CHAR = 'TY_CHAR';
    const TY_SHORT = 'TY_SHORT';
    const TY_INT = 'TY_INT';
    const TY_LONG = 'TY_LONG';
    const TY_FLOAT = 'TY_FLOAT';
    const TY_DOUBLE = 'TY_DOUBLE';
    const TY_LDOUBLE = 'TY_LDOUBLE';
    const TY_ENUM = 'TY_ENUM';
    const TY_PTR = 'TY_PTR';
    const TY_FUNC = 'TY_FUNC';
    const TY_ARRAY = 'TY_ARRAY';
    const TY_VLA = 'TY_VLA'; // variable-length array
    const TY_STRUCT = 'TY_STRUCT';
    const TY_UNION = 'TY_UNION';

    const ND_NULL_EXPR = 'ND_NULL_EXPR'; // Do nothing
    const ND_ADD = 'ND_ADD';       // +
    const ND_SUB = 'ND_SUB';       // -
    const ND_MUL = 'ND_MUL';       // *
    const ND_DIV = 'ND_DIV';       // /
    const ND_NEG = 'ND_NEG';       // unary -
    const ND_MOD = 'ND_MOD';       // %
    const ND_BITAND = 'ND_BITAND';    // &
    const ND_BITOR = 'ND_BITOR';     // |
    const ND_BITXOR = 'ND_BITXOR';    // ^
    const ND_SHL = 'ND_SHL';       // <<
    const ND_SHR = 'ND_SHR';       // >>
    const ND_EQ = 'ND_EQ';        // ==
    const ND_NE = 'ND_NE';        // !=
    const ND_LT = 'ND_LT';        // <
    const ND_LE = 'ND_LE';        // <=
    const ND_ASSIGN = 'ND_ASSIGN';    // =
    const ND_COND = 'ND_COND';      // ?:
    const ND_COMMA = 'ND_COMMA';     // ,
    const ND_MEMBER = 'ND_MEMBER';    // . (struct member access)
    const ND_ADDR = 'ND_ADDR';      // unary &
    const ND_DEREF = 'ND_DEREF';     // unary *
    const ND_NOT = 'ND_NOT';       // !
    const ND_BITNOT = 'ND_BITNOT';    // ~
    const ND_LOGAND = 'ND_LOGAND';    // &&
    const ND_LOGOR = 'ND_LOGOR';     // ||
    const ND_RETURN = 'ND_RETURN';    // "return"
    const ND_IF = 'ND_IF';        // "if"
    const ND_FOR = 'ND_FOR';       // "for" or "while"
    const ND_DO = 'ND_DO';        // "do"
    const ND_SWITCH = 'ND_SWITCH';    // "switch"
    const ND_CASE = 'ND_CASE';      // "case"
    const ND_BLOCK = 'ND_BLOCK';     // { ... }
    const ND_GOTO = 'ND_GOTO';      // "goto"
    const ND_GOTO_EXPR = 'ND_GOTO_EXPR'; // "goto" labels-as-values
    const ND_LABEL = 'ND_LABEL';     // Labeled statement
    const ND_LABEL_VAL = 'ND_LABEL_VAL'; // [GNU] Labels-as-values
    const ND_FUNCALL = 'ND_FUNCALL';   // Function call
    const ND_EXPR_STMT = 'ND_EXPR_STMT'; // Expression statement
    const ND_STMT_EXPR = 'ND_STMT_EXPR'; // Statement expression
    const ND_VAR = 'ND_VAR';       // Variable
    const ND_VLA_PTR = 'ND_VLA_PTR';   // VLA designator
    const ND_NUM = 'ND_NUM';       // Integer
    const ND_CAST = 'ND_CAST';      // Type cast
    const ND_MEMZERO = 'ND_MEMZERO';   // Zero-clear a stack variable
    const ND_ASM = 'ND_ASM';       // "asm"
    const ND_CAS = 'ND_CAS';       // Atomic compare-and-swap
    const ND_EXCH = 'ND_EXCH';      // Atomic exchange

    const TAKE_ARG = [
        "-o", "-I", "-idirafter", "-include", "-x", "-MF", "-MT", "-Xlinker",
    ];

    public function __construct(array $options = [])
    {
        $this->options = $options;

        self::init_types();
    }

    public static function parse_opt_x(string $s): string
    {
        if (!strcmp($s, "c")) {
            return self::FILE_C;
        }
        if (!strcmp($s, "assembler")) {
            return self::FILE_ASM;
        }
        if (!strcmp($s, "none")) {
            return self::FILE_NONE;
        }

        self::echo_error("< command line >: unknown argument for -x: {$s}");
        return '';
    }

    public static function quote_makefile(string $s): string
    {
        $buf = '';
        for ($i = 0; $i < strlen($s); $i++) {
            $c = $s[$i];
            switch ($c) {
                case '$':
                    $buf .= '$';
                    $buf .= '$';
                    break;
                case '#':
                    $buf .= '\\';
                    $buf .= '#';
                    break;
                case ' ':
                case '\t':
                    for ($k = $i - 1; $k >= 0 && $s[$k] == '\\'; $k--) {
                        $buf .= '\\';
                    }
                    $buf .= '\\';
                    $buf .= $c;
                    break;
                default:
                    $buf .= $c;
                    break;
            }
        }
        return $buf;
    }

    public static function parser_args(array $argv = []): array
    {
        $options = [
            'opt_fcommon' => true,
            'opt_fpic' => false,
            'opt_E' => false,
            'opt_M' => false,
            'opt_MD' => false,
            'opt_MMD' => false,
            'opt_MP' => false,
            'opt_S' => false,
            'opt_c' => false,
            'opt_cc1' => false,
            'opt_hash_hash_hash' => false,
            'opt_static' => false,
            'opt_shared' => false,
            'opt_x' => self::FILE_NONE,
            'opt_MF' => '',
            'opt_MT' => '',
            'opt_o' => '',
            'base_file' => '',
            'output_file' => '',
            'include_paths' => [],
            'opt_include' => [],
            'ld_extra_args' => [],
            'std_include_paths' => [],
            'input_paths' => [],
            'tmpfiles' => [],
            'idirafter' => [],
        ];

        for ($idx = 0; $idx < count($argv); $idx++) {
            $arg = $argv[$idx];
            if (in_array($arg, self::TAKE_ARG) && empty($argv[$idx + 1])) {
                self::echo_usage(1);
                break;
            }

            if (!strcmp($arg, "-###")) {
                $options['opt_hash_hash_hash'] = true;
                continue;
            }

            if (!strcmp($arg, "-cc1")) {
                $options['opt_cc1'] = true;
                continue;
            }

            if (!strcmp($arg, "--help")) {
                self::echo_usage(0);
                break;
            }

            if (!strcmp($arg, "-o")) {
                $idx += 1;
                $options['opt_o'] = $argv[$idx];
                continue;
            }

            if (!strncmp($arg, "-o", 2)) {
                $options['opt_o'] = substr($arg, 2);
                continue;
            }

            if (!strcmp($arg, "-S")) {
                $options['opt_S'] = true;
                continue;
            }

            if (!strcmp($arg, "-fcommon")) {
                $options['opt_fcommon'] = true;
                continue;
            }

            if (!strcmp($arg, "-fno-common")) {
                $options['opt_fcommon'] = false;
                continue;
            }

            if (!strcmp($arg, "-c")) {
                $options['opt_c'] = true;
                continue;
            }

            if (!strcmp($arg, "-E")) {
                $options['opt_E'] = true;
                continue;
            }

            if (!strncmp($arg, "-I", 2)) {
                $options['include_paths'][] = substr($arg, 2);
                continue;
            }

            if (!strcmp($arg, "-D")) {
                $idx += 1;
                self::define($argv[$idx]);
                continue;
            }

            if (!strncmp($arg, "-D", 2)) {
                self::define(substr($arg, 2));
                continue;
            }

            if (!strcmp($arg, "-U")) {
                $idx += 1;
                self::undef_macro($argv[$idx]);
                continue;
            }

            if (!strncmp($arg, "-U", 2)) {
                self::undef_macro(substr($arg, 2));
                continue;
            }

            if (!strcmp($arg, "-include")) {
                $idx += 1;
                $options['opt_include'][] = $argv[$idx];
                continue;
            }

            if (!strcmp($arg, "-x")) {
                $idx += 1;
                $options['opt_x'] = self::parse_opt_x($argv[$idx]);
                continue;
            }

            if (!strncmp($arg, "-x", 2)) {
                $options['opt_x'] = self::parse_opt_x(substr($arg, 2));
                continue;
            }

            if (!strncmp($arg, "-l", 2) || !strncmp($arg, "-Wl,", 4)) {
                $options['input_paths'][] = $arg;
                continue;
            }

            if (!strcmp($arg, "-Xlinker")) {
                $idx += 1;
                $options['ld_extra_args'][] = $argv[$idx];
                continue;
            }

            if (!strcmp($arg, "-s")) {
                $options['ld_extra_args'][] = "-s";
                continue;
            }

            if (!strcmp($arg, "-M")) {
                $options['opt_M'] = true;
                continue;
            }

            if (!strcmp($arg, "-MF")) {
                $idx += 1;
                $options['opt_MF'] = $argv[$idx];
                continue;
            }

            if (!strcmp($arg, "-MP")) {
                $options['opt_MP'] = true;
                continue;
            }

            if (!strcmp($arg, "-MT")) {
                $idx += 1;
                $options['opt_MT'] = !empty($options['opt_MT']) ? "{$options['opt_MT']} {$argv[$idx]}" : $argv[$idx];
                continue;
            }

            if (!strcmp($arg, "-MD")) {
                $options['opt_MD'] = true;
                continue;
            }

            if (!strcmp($arg, "-MQ")) {
                $idx += 1;
                $v = self::quote_makefile($argv[$idx]);
                $options['opt_MT'] = !empty($options['opt_MT']) ? "{$options['opt_MT']} {$v}" : $v;
                continue;
            }

            if (!strcmp($arg, "-MMD")) {
                $options['opt_MD'] = true;
                $options['opt_MMD'] = true;
                continue;
            }

            if (!strcmp($arg, "-fpic") || !strcmp($arg, "-fPIC")) {
                $options['opt_fpic'] = true;
                continue;
            }

            if (!strcmp($arg, "-cc1-input")) {
                $idx += 1;
                $options['base_file'] = $argv[$idx];
                continue;
            }

            if (!strcmp($arg, "-cc1-output")) {
                $idx += 1;
                $options['output_file'] = $argv[$idx];
                continue;
            }

            if (!strcmp($arg, "-idirafter")) {
                $idx += 1;
                $options['idirafter'][] = $argv[$idx];
                continue;
            }

            if (!strcmp($arg, "-static")) {
                $options['opt_static'] = true;
                $options['ld_extra_args'][] = "-static";
                continue;
            }

            if (!strcmp($arg, "-shared")) {
                $options['opt_shared'] = true;
                $options['ld_extra_args'][] = "-shared";
                continue;
            }

            if (!strcmp($arg, "-L")) {
                $options['ld_extra_args'][] = "-L";
                $idx += 1;
                $options['ld_extra_args'][] = $argv[$idx];
                continue;
            }

            if (!strncmp($arg, "-L", 2)) {
                $options['ld_extra_args'][] = "-L";
                $options['ld_extra_args'][] = substr($arg, 2);
                continue;
            }

            // These options are ignored for now.
            if (!strncmp($arg, "-O", 2) ||
                !strncmp($arg, "-W", 2) ||
                !strncmp($arg, "-g", 2) ||
                !strncmp($arg, "-std=", 5) ||
                !strcmp($arg, "-ffreestanding") ||
                !strcmp($arg, "-fno-builtin") ||
                !strcmp($arg, "-fno-omit-frame-pointer") ||
                !strcmp($arg, "-fno-stack-protector") ||
                !strcmp($arg, "-fno-strict-aliasing") ||
                !strcmp($arg, "-m64") ||
                !strcmp($arg, "-mno-red-zone") ||
                !strcmp($arg, "-w"))
                continue;

            if ($arg[0] == '-' && $arg[1] != '\0') {
                self::echo_error("unknown argument: %s", $arg);
            }

            $options['input_paths'][] = $arg;
        }

        return $options;
    }

    public static function echo_error(string $str, int $status = 0): void
    {
        echo $str;
        exit($status);
    }

    public static function echo_usage(int $status = 0): void
    {
        echo <<<EOT
Usage:  php chibicc.php [ -o < path > ] < file >
EOT;
        exit($status);
    }

    public static function endswith(string $p, string $q): bool
    {
        $len1 = strlen($p);
        $len2 = strlen($q);
        return ($len1 >= $len2) && !strcmp(substr($p, -$len2), $q);
    }

    public static function replace_extn(string $tmpl, string $extn)
    {
        $filename = basename($tmpl);
        $dirname = dirname($tmpl);
        $pos = strrpos($filename, '.');
        if ($pos !== false) {
            $filename = substr($filename, 0, $pos);
        }
        return $dirname . DIRECTORY_SEPARATOR . "{$filename}{$extn}";
    }

    public static function append_tokens(Token $tok1, Token $tok2): Token
    {
        if (!$tok1 || $tok1->kind == self::TK_EOF)
            return $tok2;

        $t = $tok1;
        while ($t->next->kind != self::TK_EOF) {
            $t = $t->next;
        }

        $t->next = $tok2;
        return $tok1;
    }

    ################################################################################################


    public function must_tokenize_file(string $path): Token
    {
        $tok = null;
        try {
            $tok = $this->tokenize_file($path);
        } catch (\Throwable $ex) {
            self::echo_error("{$path}: " . $ex->getMessage(), 1);
        }
        if (!$tok) {
            self::echo_error("{$path}: empty token", 1);
        }
        return $tok;
    }

    public function cc1(): void
    {
        $tok = null;

        // Process -include option
        foreach ($this->options['opt_include'] as $incl) {
            $path = file_exists($incl) ? $incl : $this->search_include_paths($incl);
            if (!$path) {
                throw new \RuntimeException("-include: {$incl}: not found");
            }


            $tmp = $this->must_tokenize_file($path);
            $tok = self::append_tokens($tok, $tmp);
        }

        // Tokenize and parse.
        $tok2 = $this->must_tokenize_file($this->options['base_file']);
        $tok = self::append_tokens($tok, $tok2);
        $tok = $this->preprocess($tok);

        // If -M or -MD are given, print file dependencies.
        if ($this->options['opt_M'] || $this->options['opt_MD']) {
            $this->print_dependencies();
            if ($this->options['opt_M'])
                return;
        }

        // If -E is given, print out preprocessed C code as a result.
        if ($this->options['opt_E']) {
            $this->print_tokens($tok);
            return;
        }

        $prog = $this->parse($tok);

        // Open a temporary output buffer.
        $output_buf = fopen('php://temp', 'w');

        // Traverse the AST to emit assembly.
        $this->codegen($prog, $output_buf);
        $buf = file_get_contents('php://temp');
        fclose($output_buf);

        // Write the asembly text to a file.
        $output_file = !empty($this->options['output_file']) ? $this->options['output_file'] : '';
        $out = fopen($output_file, 'w');
        fwrite($out, $buf);
        fclose($out);
    }

    public function in_std_include_path(string $path): bool
    {
        $std_include_paths = $this->options['std_include_paths'];
        foreach ($std_include_paths as $dir) {
            $len = strlen($dir);
            if (strncmp($dir, $path, $len) == 0 && $path[$len] == '/') {
                return true;
            }
        }
        return false;
    }

    /* =====================================================================================
    ========================================================================================
    ===================================================================================== */

    // Print tokens to stdout. Used for -E.
    public function print_tokens(Token $tok): void
    {
        $opt_o = !empty($this->options['opt_o']) ? $this->options['opt_o'] : 'php://stdout';
        $out = fopen($opt_o, 'w');

        $line = 1;
        for (; $tok->kind != self::TK_EOF; $tok = $tok->next) {
            if ($line > 1 && $tok->at_bol) {
                fprintf($out, "\n");
            }

            if ($tok->has_space && !$tok->at_bol) {
                fprintf($out, " ");
            }

            fprintf($out, "%.*s", $tok->len, $tok->loc);
            $line++;
        }
        fprintf($out, "\n");
    }

    // If -M options is given, the compiler write a list of input files to
// stdout in a format that "make" command can read. This feature is
// used to automate file dependency management.
    public function print_dependencies(): void
    {
        if ($this->options['opt_MF']) {
            $path = $this->options['opt_MF'];
        } else if ($this->options['opt_MD']) {
            $f = $this->options['opt_o'] ? $this->options['opt_o'] : $this->options['base_file'];
            $path = self::replace_extn($f, ".d");
        } else if ($this->options['opt_o']) {
            $path = $this->options['opt_o'];
        } else {
            $path = 'php://stdout';
        }


        $out = fopen($path, 'w');
        if ($this->options['opt_MT']) {
            fprintf($out, "%s:", $this->options['opt_MT']);
        } else {
            $tmp = self::replace_extn($this->options['base_file'], ".o");
            fprintf($out, "%s:", self::quote_makefile($tmp));
        }


        $files = $this->get_input_files();
        foreach ($files as $file) {
            if ($this->options['opt_MMD'] && $this->in_std_include_path($file->name)) {
                continue;
            }

            fprintf($out, " \\\n  %s", $file->name);
        }

        fprintf($out, "\n\n");

        if ($this->options['opt_MP']) {
            foreach ($files as $file) {
                if ($this->options['opt_MMD'] && $this->in_std_include_path($file->name)) {
                    continue;
                }

                fprintf($out, "%s:\n\n", self::quote_makefile($file->name));
            }
        }
    }


    public function parse(Token $tok): Program
    {
        $env = $this->env;
        $env->tokens = $tok;

        $prog = new Program();
        $env->prog = $prog;

        while (!$env->is_eof()) {
            $env->toplevel();
        }

        return $prog;
    }

    private function tokenize(string $inputFile, bool $add_eof): array
    {
        $buf = file_get_contents($inputFile);
        $buf = self::replace_crlf($buf);
        $buf = self::remove_backslash_newline($buf);

        $env = new Env($this->env, $inputFile, $buf);
        $file = $env->path;

        /******************************************************************************************/
        /******************************************************************************************/
        /******************************************************************************************/

        $start = microtime(true);


        $used = intval((microtime(true) - $start) * 1000);
        $tl = count($env->tokens);
        echo "scan file {$file}, count:{$tl} use:{$used}ms\n";

        /******************************************************************************************/
        /******************************************************************************************/
        /******************************************************************************************/

        $start = microtime(true);

        $tokens = $env->tokens;
        $tokens = $this->preprocess($tokens);
        $tokens = self::strip_newline_tokens($tokens);
        $tokens = self::join_string_literals($tokens);

        $used = intval((microtime(true) - $start) * 1000);
        $tl = count($tokens);
        echo "preprocess file {$file}, count:{$tl} use:{$used}ms\n";

        return $tokens;
    }

    public function preprocess(Token $tok): Token
    {
        return $tok;
    }

    ###########################################################################################

    private static function join_string_literals(array $tokens): array
    {
        $tokens_ = [];
        /** @var Token $last */
        $last = null;

        foreach ($tokens as $token) {
            /** @var Token $token */
            if (!empty($last) && $last->ty == self::TK_STR && $token->ty == self::TK_STR) {
                $last->str .= $token->str;
                $last->len = strlen($last->str);
                continue;
            }
            $last = $token;
            $tokens_[] = $token;
        }
        return $tokens_;
    }

    private static function strip_newline_tokens(array $tokens): array
    {
        $tokens_ = [];
        foreach ($tokens as $token) {
            /** @var Token $token */
            if ($token->ty == self::ORD_N) {
                continue;
            }
            $tokens_[] = $token;
        }
        return $tokens_;
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