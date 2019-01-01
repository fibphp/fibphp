<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\16 0016
 * Time: 0:13
 */

namespace phpsonar\Abstracts;


use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Scalar\String_;
use phpsonar\Analyzer;
use phpsonar\Anodoc\Exception;
use phpsonar\Anodoc\Parser;
use phpsonar\Anodoc\Tags\ParamTag;
use phpsonar\Interfaces\TypeVisitor;
use phpsonar\State;
use phpsonar\Types\BaseTypes\ArrayType;
use phpsonar\Types\BaseTypes\BoolType;
use phpsonar\Types\BaseTypes\CallableType;
use phpsonar\Types\BaseTypes\FloatType;
use phpsonar\Types\BaseTypes\IntType;
use phpsonar\Types\BaseTypes\NullType;
use phpsonar\Types\BaseTypes\ObjectType;
use phpsonar\Types\BaseTypes\ResourceType;
use phpsonar\Types\BaseTypes\StringType;
use phpsonar\Types\ClassType;
use phpsonar\Types\MixedType;
use phpsonar\Types\ParamsTuple;
use phpsonar\Types\ReturnType;
use phpsonar\Types\Wrapper\ListOf;
use phpsonar\Types\Wrapper\Mixedable;
use phpsonar\Types\Wrapper\Nullable;
use phpsonar\Types\Wrapper\UnionType;
use phpsonar\Types\Wrapper\VoidType;
use phpsonar\Util;

abstract class AbstractTypeVisitor implements TypeVisitor
{

    private $_analyzer = null;

    private $_typeVisitorMap = [];

    protected static $doc_parser = null;

    public function __construct(Analyzer $analyzer, array $visitorMap = [])
    {
        $this->_analyzer = $analyzer;
        $this->_typeVisitorMap = $visitorMap;
    }

    public static function getDocParser()
    {
        if (empty(self::$doc_parser)) {
            $doc_parser = new Parser();
            $doc_parser->registerTag('param', 'phpsonar\Anodoc\Tags\ParamTag');
            self::$doc_parser = $doc_parser;
        }
        return self::$doc_parser;
    }

    /**
     * @param Node|null $node
     * @param ParamsTuple|null $param
     * @param ReturnType|null $return
     * @param Comment|null $comment
     * @param State|null $state
     * @return array
     */
    protected static function tryFixParamAndReturnByComment(Node $node = null, ParamsTuple $param = null, ReturnType $return = null, Comment $comment = null, State $state = null)
    {
        $param = empty($param) ? new ParamsTuple($node) : $param;
        $return = empty($return) ? new ReturnType($node) : $return;

        /** @var Comment $comment */
        $text = !empty($comment) ? $comment->getText() : '';
        if (empty($text)) {
            return [$param, $return];
        }

        try {
            $doc = self::getDocParser()->parse($text);
            $doc_return = $doc->getTag('return');
            $doc_params = $doc->getTags('param');
            if (!empty($doc_return)) {
                $return_ = self::tryBuildReturnTypeByComment($node, $doc_return->getValue(), $state);
                if (!empty($return_)) {
                    $return->setType($return_);
                }
            }

            if (!empty($doc_params)) {
                $args = $param->getArgs();
                foreach ($args as $arg) {
                    /** @var ParamTag $doc */
                    $last_param = $param->getParam($arg);
                    $doc = $doc_params->getSubItem('param', $arg);
                    if (empty($doc) || !empty($last_param['isTypeHit'])) {  // TODO isTypeHit 暂时跳过 文档分析
                        continue;
                    }
                    $value = $doc->getValue();
                    $arg_ = self::tryBuildParamTypeByComment($node, !empty($value['type']) ? $value['type'] : '', $state);
                    if (!empty($arg_)) {
                        $param->setParam($arg, $arg_);
                    }
                }
            }
        } catch (Exception $ex) {
            $log_msg = "error:" . get_class($ex) . ", msg:" . $ex->getMessage();
            error_log($log_msg);
        } catch (\Exception $ex2) {
            $log_msg = "error:" . get_class($ex2) . ", msg:" . $ex2->getMessage();
            error_log($log_msg);
        }
        return [$param, $return];
    }

    protected static function _tryBuildParamTypeByComment(Node $node = null, $type_str = '', State $state = null)
    {
        false && func_get_args();

        if (empty($type_str) || Util::stri_cmp($type_str, 'mixed')) {
            return new MixedType($node);
        } elseif (Util::stri_cmp($type_str, 'string')) {
            return new StringType($node);
        } elseif (Util::stri_cmp($type_str, 'int')) {
            return new IntType($node);
        } elseif (Util::stri_cmp($type_str, 'bool') || Util::stri_cmp($type_str, 'boolean') || Util::stri_cmp($type_str, 'true') || Util::stri_cmp($type_str, 'false')) {
            return new BoolType($node);
        } elseif (Util::stri_cmp($type_str, 'float')) {
            return new FloatType($node);
        } elseif (Util::stri_cmp($type_str, 'callable')) {
            return new CallableType($node);
        } elseif (Util::stri_cmp($type_str, 'resource')) {
            return new ResourceType($node);
        } elseif (Util::stri_cmp($type_str, 'object')) {
            return new ObjectType($node);
        } elseif (Util::stri_cmp($type_str, 'void')) {
            return new VoidType($node);
        } elseif (Util::stri_cmp($type_str, 'null')) {
            return new NullType($node);
        } elseif (Util::stri_cmp($type_str, 'array')) {
            return new ArrayType($node);
        }

        if (!empty($state) && $state->checkIdentifier($type_str)) {
            echo "\n TODO checkIdentifier {$type_str} \n";
            return new ClassType($node, $type_str);
        }
        return null;
    }

    protected static function tryParserUnionType(Node $node, string $type_str, State $state = null)
    {
        $type_tmp = explode('|', $type_str);
        $type_arr = [];
        foreach ($type_tmp as $item) {
            $t = trim($item);
            if (!empty($t) && !in_array($t, $type_arr)) {
                $type_arr[] = $t;
            }
        }
        $null_able = false;
        $mixed_able = false;
        $types = [];
        foreach ($type_arr as $t_str) {
            if (Util::stri_cmp($t_str, 'null')) {
                $null_able = true;
                continue;
            }
            if (Util::stri_cmp($t_str, 'mixed')) {
                $mixed_able = true;
                continue;
            }
            $type = self::tryBuildParamTypeByComment($node, $t_str, $state);
            if (!empty($type)) {
                $types[] = $type;
            }
        }
        if (!empty($types)) {
            $ret_type = new UnionType($types);
            if ($mixed_able) {
                $ret_type = new Mixedable($ret_type);
            }
            if ($null_able) {
                $ret_type = new Nullable($ret_type);
            }
            return $ret_type;
        }
        return new MixedType($node);
    }

    protected static function tryParserListType(Node $node, string $type_str, State $state = null)
    {
        $type_str = str_replace('[]', '', $type_str);
        $type = self::tryBuildParamTypeByComment($node, $type_str, $state);
        if (!empty($type)) {
            return new ListOf($type);
        }
        return new ArrayType($node);
    }

    protected static function tryBuildParamTypeByComment(Node $node = null, $type_str = '', State $state = null)
    {
        if (strpos($type_str, '|') !== false) {
            return self::tryParserUnionType($node, $type_str, $state);
        }
        if (strpos($type_str, '[]') !== false) {
            return self::tryParserListType($node, $type_str, $state);
        }

        return self::_tryBuildParamTypeByComment($node, $type_str, $state);
    }

    protected static function tryBuildReturnTypeByComment(Node $node = null, $type_str = '', State $state = null)
    {
        $type_str = explode(' ', $type_str)[0];
        $type_str = explode("\r", $type_str)[0];
        $type_str = explode("\n", $type_str)[0];
        $type_str = explode("\t", $type_str)[0];
        return self::tryBuildParamTypeByComment($node, $type_str, $state);
    }

    /**
     * @param Node|null $node
     * @param array $params
     * @param State $state
     * @return ParamsTuple
     * @throws \phpsonar\Exception\PhpSonarError
     */
    protected static function tryBuildParamsTuple(Node $node = null, array $params = [], State $state = null)
    {
        $param = new ParamsTuple($node);
        if (empty($params)) {
            return $param;
        }

        foreach ($params as $param_node) {
            /** @var Node\Param $param_node */
            /** @var  Node\Expr\Variable $var */
            $var = $param_node->var;
            $name = $var->name;
            $type = self::tryBuildParamType($param_node, $param_node->type, $state);
            $default = $param_node->default;
            $isOptional = !empty($default);
            $default_type = self::tryBuildParamDefault($default, $state);
            $param->addParam($name, $type, $param_node->byRef, $param_node->variadic, $isOptional, $default_type, !empty($type));
        }
        return $param;
    }


    protected static function tryBuildParamType(Node $node, $type = null, State $state = null)
    {
        if (empty($type)) {
            return new MixedType($node);
        }

        if ($type instanceof Node\Identifier) {
            $type_str = $type->name;
            return self::tryBuildParamTypeByComment($node, $type_str, $state);
        } elseif ($type instanceof Node\Name) {
            $type_str = join('\\', $type->parts);
            return self::tryBuildParamTypeByComment($node, $type_str, $state);
        }
        return new MixedType($node);
    }

    protected static function tryBuildParamDefault($default = null, State $state = null)
    {
        if (empty($default)) {
            return null;
        }

        if ($default instanceof Expr) {
            return self::tryExecExpr($default, $state);
        }

        return null;
    }

    /**
     * @param Node|null $node
     * @param Node|null $returnType
     * @param bool $byRef
     * @param State|null $state
     * @return ReturnType
     */
    protected static function tryBuildReturnType(Node $node = null, Node $returnType = null, bool $byRef = false, State $state = null)
    {
        $type = null;
        if (is_null($returnType)) {
            return new ReturnType($node, '', $type, $byRef);
        }

        if ($returnType instanceof Node\Identifier) {
            $type_str = $returnType->name;
            $type = self::tryBuildParamTypeByComment($node, $type_str, $state);
        } elseif ($returnType instanceof Node\NullableType) {
            $returnType = $returnType->type;
            $type_str = $returnType->name;
            $type = self::tryBuildParamTypeByComment($node, $type_str, $state);
            $type = new Nullable($type);
        } else {
            $type = new MixedType($node, ''); // TODO
        }

        return new ReturnType($node, '', $type, $byRef);
    }

    protected static function tryExecExpr(Expr $value, State $state = null)
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