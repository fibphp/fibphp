<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\16 0016
 * Time: 0:13
 */

namespace phpsonar\Abstracts;


use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Scalar\String_;
use phpsonar\Analyzer;
use phpsonar\Interfaces\TypeVisitor;
use phpsonar\State;

abstract class AbstractTypeVisitor implements TypeVisitor
{

    private $_analyzer = null;

    private $_typeVisitorMap = [];

    public function __construct(Analyzer $analyzer, array $visitorMap = [])
    {
        $this->_analyzer = $analyzer;
        $this->_typeVisitorMap = $visitorMap;
    }

    protected static function tryExecExpr(Expr $value, State $state)
    {
        false && func_get_args();
        if ($value instanceof Expr\ConstFetch) {
            /** @var Name $name_ */
            $name_ = $value->name;
            return join('', $name_->parts);
        } elseif ($value instanceof Expr\Variable) {
            return '$' . $value->name;
        } elseif ($value instanceof Expr\ArrayDimFetch) {
            $var = $value->var;
            $dim = $value->dim;
            $dim_ = self::tryExecExpr($dim, $state);
            return '$' . $var->name . "[{$dim_}]";
        } elseif ($value instanceof String_) {
            return "\"" . addslashes($value->value) . "\"";
        } elseif ($value instanceof DNumber) {
            return $value->value;
        } elseif ($value instanceof LNumber) {
            return $value->value;
        } elseif ($value instanceof MagicConst) {
            return $value->getName();
        } elseif ($value instanceof Encapsed) {
            return join('', array_map(function ($part) use ($state) {
                return self::tryExecExpr($part, $state);
            }, $value->parts));
        } elseif ($value instanceof EncapsedStringPart) {
            return $value->value;
        } elseif ($value instanceof Expr\UnaryMinus) {
            return self::tryExecExpr($value->expr, $state);
        } elseif ($value instanceof Expr\FuncCall) {
            $name = $value->name;
            $f_name = join('::', $name->parts);
            $args = $value->args;
            $f_args = [];
            foreach ($args as $arg) {
                $f_args[] = self::tryExecExpr($arg->value, $state);
            }
            return "{$f_name}(" . join(', ', $f_args) . ")";
        } elseif ($value instanceof Expr\BinaryOp\Concat) {
            return self::tryExecExpr($value->left, $state) . '`.`' . self::tryExecExpr($value->right, $state);
        } elseif ($value instanceof Expr\BinaryOp\BitwiseOr) {
            return self::tryExecExpr($value->left, $state) . '`|`' . self::tryExecExpr($value->right, $state);
        } elseif ($value instanceof Expr\BinaryOp\BitwiseAnd) {
            return self::tryExecExpr($value->left, $state) . '`&`' . self::tryExecExpr($value->right, $state);
        } elseif ($value instanceof Expr\BinaryOp\BitwiseXor) {
            return self::tryExecExpr($value->left, $state) . '`^`' . self::tryExecExpr($value->right, $state);
        }

        return 0; // TODO
    }

    public function registerTypeVisitor($type, TypeVisitor $visitor)
    {
        $this->_typeVisitorMap[$type] = $visitor;
    }

    /**
     * @return Analyzer
     */
    public function getAnalyzer(): ?Analyzer
    {
        return $this->_analyzer;
    }

    /**
     * @param Analyzer $analyzer
     */
    public function setAnalyzer(Analyzer $analyzer): void
    {
        $this->_analyzer = $analyzer;
    }

    /**
     * @return array
     */
    public function getTypeVisitorMap(): array
    {
        return $this->_typeVisitorMap;
    }

    /**
     * @param array $typeVisitorMap
     */
    public function setTypeVisitorMap(array $typeVisitorMap): void
    {
        $this->_typeVisitorMap = $typeVisitorMap;
    }

    /**
     * @param $type
     * @return TypeVisitor
     */
    protected function loadBaseTypeVisitor($type)
    {
        $tmpArr = explode('_', $type);
        if (!empty($tmpArr) && !empty($tmpArr[0]) && !empty($this->_typeVisitorMap[$tmpArr[0]])) {
            return $this->_typeVisitorMap[$tmpArr[0]];
        }
        return null;
    }

    /**
     * @param $type
     * @return TypeVisitor
     */
    protected function loadTypeVisitor($type)
    {
        if (!empty($this->_typeVisitorMap[$type])) {
            return $this->_typeVisitorMap[$type];
        }
        return null;
    }


}