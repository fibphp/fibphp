<?php

namespace phpsonar\StdNode\Expr;


use PhpParser\Node;
use phpsonar\Abstracts\AbsTractTypeInferencer;
use phpsonar\StdNode\Expr;
use phpsonar\State;
use phpsonar\Types\VarType;
use Tiny\Exception\Error;

class Assign extends Expr
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
        /** @var \PhpParser\Node\Expr\Assign $node */
        /** @var \PhpParser\Node\Expr $var */
        $var = $node->var;
        $name = self::tryExecExpr($var, $state);
        /** @var \PhpParser\Node\Expr $expr */
        $expr = $node->expr;
        $global_map = $state->getGlobalMap();
        try {
            $global_map->setVar($name, new VarType($expr));
        } catch (Error $ex) {
            $state->addWarn($name, $ex);
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