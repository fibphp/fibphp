<?php

namespace phpsonar\SonarNode\Expr;


use PhpParser\Node;
use phpsonar\Abstracts\AbsTractTypeInferencer;
use phpsonar\CodeAt;
use phpsonar\Exception\ReDefineWarn;
use phpsonar\SonarNode\Expr;
use phpsonar\State;
use Tiny\Exception\Error;

class FuncCall extends Expr
{
    /**
     * Called when entering a node.
     *
     * Return value semantics:
     *  * null
     *        => $node stays as-is
     *  * NodeTraverser::DONT_TRAVERSE_CHILDREN
     *        => Children of $node are not traversed. $node stays as-is
     *  * NodeTraverser::STOP_TRAVERSAL
     *        => Traversal is aborted. $node stays as-is
     *  * otherwise
     *        => $node is set to the return value
     *
     * @param Node $node Node
     *
     * @param State $state
     * @return null|int|Node Replacement node (or special return value)
     */
    public function enterNode(Node $node, State $state)
    {
        /** @var \PhpParser\Node\Expr\FuncCall $node */
        $f_name_ = $node->name;
        $f_name = join('::', $f_name_->parts);
        if ($f_name == 'define') {
            /** @var Node\Arg $name_ */
            $name_ = $node->args[0];
            $value_ = $node->args[1];
            $_name = $name_->value;
            $name = self::tryExecExpr($_name, $state);
            $value = $value_->value;
            $global_map = $state->getGlobalScope();
            $const_name = $state->_namespace($name);
            try {
                $global_map->setConst($const_name, self::tryExecExpr($value, $state), CodeAt::createByNode($this->getAnalyzer(), $node));
            } catch (ReDefineWarn $ex) {
                $state->pushWarn($ex->getName(), $ex);
            } catch (Error $ex) {
                $state->pushError($const_name, $ex);
            }
        }

        return AbsTractTypeInferencer::DONT_TRAVERSE_CHILDREN;
    }

    /**
     * Called when leaving a node.
     *
     * Return value semantics:
     *  * null
     *        => $node stays as-is
     *  * NodeTraverser::REMOVE_NODE
     *        => $node is removed from the parent array
     *  * NodeTraverser::STOP_TRAVERSAL
     *        => Traversal is aborted. $node stays as-is
     *  * array (of Nodes)
     *        => The return value is merged into the parent array (at the position of the $node)
     *  * otherwise
     *        => $node is set to the return value
     *
     * @param Node $node Node
     *
     * @param State $state
     * @return null|int|Node|Node[] Replacement node (or special return value)
     */
    public function leaveNode(Node $node, State $state)
    {
        return null;
    }

}