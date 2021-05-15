<?php


namespace php9cc;


use php9cc\demos\MainApp;
use php9cc\Exception\NeverRunHere;

class Env
{

    public ?Env $prev;
    public string $path = '';
    public string $buf = '';
    public array $tokens = [];

    public ?Program $prog;
    public int $pos = 0;

    const escaped = [
        'a' => "\007",
        'b' => "\010",
        'f' => "\f",
        'n' => "\n",
        'r' => "\r",
        't' => "\t",
        'v' => "\v",
        'e' => "\033",
        'E' => "\033",
    ];

    public function __construct($prev, string $path, string $buf)
    {
        $this->prev = $prev;
        $this->path = $path;
        $this->buf = $buf;
        $this->tokens = [];
    }

    public function add(int $ty, int $start): Token
    {
        $t = new Token($ty, $start, $this);
        $this->tokens[] = $t;
        return $t;
    }

    public static function startswith(string $str, int $idx, string $buf): bool
    {
        $tmp = substr($buf, $idx, strlen($str));
        return $tmp === $str;
    }

    public static function isxdigit(string $char)
    {
        $t = MainApp::$ord[$char];
        return ($t >= 48 && $t <= 57) || ($t >= 65 && $t <= 70) || ($t >= 97 && $t <= 102);
    }

    public static function hex(string $char)
    {
        $t = MainApp::$ord[$char];
        if ($t >= 48 && $t <= 57) {
            return $t - 48;
        }
        if ($t >= 65 && $t <= 70) {
            return $t - 65 + 10;
        }
        // $t >= 97 && $t <= 102
        return $t - 97 + 10;
    }

    public static function isoctal(string $char)
    {
        $t = MainApp::$ord[$char];
        return $t >= 48 && $t <= 55;
    }

    public static function isalpha(string $char)
    {
        $t = MainApp::$ord[$char];
        return ($t >= 65 && $t <= 90) || ($t >= 97 && $t <= 122);
    }

    public static function isdigit(string $char)
    {
        $t = MainApp::$ord[$char];
        return $t >= 48 && $t <= 57;
    }

    /**
     * @param $idx
     * @return int
     * @throws NeverRunHere
     */
    public function block_comment(int $idx): int
    {
        $ll = strlen($this->buf);
        $idx += 2;
        for (; $idx < $ll; $idx++) {
            if (self::startswith("*/", $idx, $this->buf)) {
                return $idx + 2;
            }
        }
        throw new NeverRunHere("unclosed comment at {$idx}");
    }

    public function c_char(int &$val, int $idx): int
    {
        $char = $this->buf[$idx];
        if ($char != "\\") {
            $val = MainApp::$ord[$char];
            return $idx + 1;
        }
        $idx += 1;
        $char = $this->buf[$idx];
        if (isset(self::escaped[$char])) {
            $char = self::escaped[$char];
            $val = MainApp::$ord[$char];
            return $idx + 1;
        }
        if ($char == 'x') {
            $tmp = 0;
            $idx += 1;
            $char = $this->buf[$idx];
            while (isset(MainApp::$isxdigit[$char])) {
                $tmp = $tmp * 16 + MainApp::$isxdigit[$char];
                $idx += 1;
                $char = $this->buf[$idx];
            }
            $val = $tmp;
            return $idx;
        }

        if (isset(MainApp::$isoctal[$char])) {
            $tmp = MainApp::$isoctal[$char];
            $idx += 1;
            $char = $this->buf[$idx];
            if (isset(MainApp::$isoctal[$char])) {
                $tmp = $tmp * 8 + MainApp::$isoctal[$char];
                $idx += 1;
                $char = $this->buf[$idx];
            }
            if (isset(MainApp::$isoctal[$char])) {
                $tmp = $tmp * 8 + MainApp::$isoctal[$char];
                $idx += 1;
            }
            $val = $tmp;
            return $idx;
        }

        $val = MainApp::$ord[$char];
        return $idx + 1;
    }

    /**
     * @param $idx
     * @return int
     * @throws NeverRunHere
     */
    public function char_literal(int $idx): int
    {
        $idx += 1;
        $t = $this->add(MainApp::TK_NUM, $idx);
        $idx = $this->c_char($t->val, $idx);
        $char = $this->buf[$idx];
        if ($char != "'") {
            throw new NeverRunHere("unclosed character literal at {$idx}");
        }
        $t->end = $idx + 1;
        return $idx + 1;
    }

    /**
     * @param $idx
     * @return int
     * @throws NeverRunHere
     */
    public function string_literal(int $idx): int
    {
        $ll = strlen($this->buf);

        $idx += 1;
        $t = $this->add(MainApp::TK_STR, $idx);
        $char = $this->buf[$idx];
        while ($char != '"') {
            $tmp = 0;
            $idx = $this->c_char($tmp, $idx);
            $t->str .= chr($tmp);

            $char = $this->buf[$idx];
            if ($idx >= $ll || $char == "\n") {
                throw new NeverRunHere("unclosed string literal at {$idx}");
            }
        }
        $t->len = strlen($t->str);
        $t->end = $idx + 1;
        return $idx + 1;
    }

    public function ident(int $idx): int
    {
        $len = 1;
        $char = $this->buf[$idx + $len];
        while (isset(MainApp::$isalpha[$char]) || isset(MainApp::$isdigit[$char]) || $char == '_') {
            $len += 1;
            $char = $this->buf[$idx + $len];
        }

        $name = substr($this->buf, $idx, $len);
        $ty = isset(MainApp::keywords[$name]) ? MainApp::keywords[$name] : MainApp::TK_IDENT;
        $t = $this->add($ty, $idx);
        $t->name = $name;
        $t->end = $idx + $len;
        return $idx + $len;
    }

    /**
     * @param int $idx
     * @return int
     * @throws NeverRunHere
     */
    public function hexadecimal(int $idx): int
    {
        $t = $this->add(MainApp::TK_NUM, $idx);
        $idx += 2;

        $char = $this->buf[$idx];
        if (!isset(MainApp::$isxdigit[$char])) {
            throw new NeverRunHere("bad hexadecimal number at {$idx}");
        }

        $tmp = 0;
        while (isset(MainApp::$isxdigit[$char])) {
            $tmp = $tmp * 16 + MainApp::$isxdigit[$char];
            $idx += 1;
            $char = $this->buf[$idx];
        }
        $t->end = $idx;
        return $idx;
    }

    public function octal(int $idx): int
    {
        $t = $this->add(MainApp::TK_NUM, $idx);

        $char = $this->buf[$idx];
        $tmp = 0;
        while (isset(MainApp::$isoctal[$char])) {
            $tmp = $tmp * 8 + MainApp::$isoctal[$char];
            $idx += 1;
            $char = $this->buf[$idx];
        }

        $t->end = $idx;
        return $idx;
    }

    public function decimal(int $idx): int
    {
        $t = $this->add(MainApp::TK_NUM, $idx);

        $char = $this->buf[$idx];
        $tmp = 0;
        while (isset(MainApp::$isdigit[$char])) {
            $tmp = $tmp * 10 + MainApp::$isdigit[$char];
            $idx += 1;
            $char = $this->buf[$idx];
        }

        $t->end = $idx;
        return $idx;
    }

    /**
     * @param int $idx
     * @return int
     * @throws NeverRunHere
     */
    public function number(int $idx): int
    {
        if (self::startswith("0x", $idx, $this->buf) || self::startswith("0X", $idx, $this->buf)) {
            return $this->hexadecimal($idx);
        }

        $char = $this->buf[$idx];
        if ($char == '0') {
            return $this->octal($idx);
        }

        return $this->decimal($idx);
    }

    /**
     * @throws NeverRunHere
     */
    public function scan()
    {
        $idx = 0;
        $ll = strlen($this->buf);
        $nn = 0;
        while ($idx < $ll) {
            $nn += 1;
            if ($nn % 1000 == 0) {
                echo '.';
                if ($nn % 5000 == 0) {
                    echo '_';
                }
            }
            // New line (preprocessor-only token)
            $char = $this->buf[$idx];

            if ($char == "\n") {
                $t = $this->add(13, $idx);
                $idx++;
                $t->end = $idx;
                continue;
            }

            if ($char == " " || $char == "\t" || $char == "\v" || $char == "\f") {
                $idx++;
                continue;
            }

            if (self::startswith("//", $idx, $this->buf)) {
                while ($idx < $ll && $this->buf[$idx] != "\n") {
                    $idx++;
                }
                continue;
            }

            if (self::startswith("/*", $idx, $this->buf)) {
                $idx = $this->block_comment($idx);
                continue;
            }

            if ($char == "'") {
                $idx = $this->char_literal($idx);
                continue;
            }

            if ($char == '"') {
                $idx = $this->string_literal($idx);
                continue;
            }

            $idx_ = $idx + 1;
            $char_ = $idx_ < $ll ? $this->buf[$idx_] : '';
            $symbol = $char . $char_;
            if (isset(MainApp::symbols_2[$symbol])) {
                $ty = MainApp::symbols_2[$symbol];
                $t = $this->add($ty, $idx);
                $idx += strlen($symbol);
                $t->end = $idx;
                continue;
            }

            $idx_ = $idx + 2;
            $char__ = $idx_ < $ll ? $this->buf[$idx_] : '';
            $symbol = $char . $char_ . $char__;
            if (isset(MainApp::symbols_3[$symbol])) {
                $ty = MainApp::symbols_3[$symbol];
                $t = $this->add($ty, $idx);
                $idx += strlen($symbol);
                $t->end = $idx;
                continue;
            }

            if (strpos(MainApp::symbols_c, $char) !== false) {
                $t = $this->add(MainApp::$ord[$char], $idx);
                $idx += 1;
                $t->end = $idx;
                continue;
            }

            if (self::isalpha($char) || $char == '_') {
                $idx = $this->ident($idx);
                continue;
            }

            if (isset(MainApp::$isdigit[$char])) {
                $idx = $this->number($idx);
                continue;
            }

            throw new NeverRunHere("cannot tokenize at {$idx}");
        }

    }

    public function is_eof(): bool
    {
        $t = $this->tokens[$this->pos];
        return $t->ty == MainApp::TK_EOF;
    }

    public function toplevel()
    {

    }

}