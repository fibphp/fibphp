<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\15 0015
 * Time: 22:39
 */

namespace phpsonar;


use PhpParser\Node;
use phpsonar\Abstracts\AbsTractTypeInferencer;
use phpsonar\StdNode\Arg;
use phpsonar\StdNode\Const_;
use phpsonar\StdNode\Expr;
use phpsonar\StdNode\Identifier;
use phpsonar\StdNode\Name;
use phpsonar\StdNode\NullableType;
use phpsonar\StdNode\Param;
use phpsonar\StdNode\Scalar;
use phpsonar\StdNode\Stmt;
use phpsonar\StdNode\VarLikeIdentifier;

class TypeInferencer extends AbsTractTypeInferencer
{

    public function __construct(Analyzer $analyzer, array $visitorMap = [])
    {
        parent::__construct($analyzer, $visitorMap);
        $this->registerTypeVisitor('Arg', new Arg($analyzer, $visitorMap));
        $this->registerTypeVisitor('Const', new Const_($analyzer, $visitorMap));
        $this->registerTypeVisitor('Expr', new Expr($analyzer, $visitorMap));
        $this->registerTypeVisitor('Identifier', new Identifier($analyzer, $visitorMap));
        $this->registerTypeVisitor('Name', new Name($analyzer, $visitorMap));
        $this->registerTypeVisitor('NullableType', new NullableType($analyzer, $visitorMap));
        $this->registerTypeVisitor('Param', new Param($analyzer, $visitorMap));
        $this->registerTypeVisitor('Scalar', new Scalar($analyzer, $visitorMap));
        $this->registerTypeVisitor('Stmt', new Stmt($analyzer, $visitorMap));
        $this->registerTypeVisitor('VarLikeIdentifier', new VarLikeIdentifier($analyzer, $visitorMap));
    }

    /**
     * Called once before traversal.
     *
     * Return value semantics:
     *  * null:      $nodes stays as-is
     *  * otherwise: $nodes is set to the return value
     *
     * @param Node[] $nodes Array of nodes
     *
     * @param State $state
     * @return null|Node[] Array of nodes
     */
    public function beforeTraverse(array $nodes, State $state)
    {
        return null;
    }


    /**
     * Called once after traversal.
     *
     * Return value semantics:
     *  * null:      $nodes stays as-is
     *  * otherwise: $nodes is set to the return value
     *
     * @param Node[] $nodes Array of nodes
     *
     * @param State $state
     * @return null|Node[] Array of nodes
     */
    public function afterTraverse(array $nodes, State $state)
    {
        return null;
    }


    ###############################################################################################
    ###############################################################################################
    ###############################################################################################

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
        $visitor = $this->loadBaseTypeVisitor($node->getType());
        if (!empty($visitor)) {
            return $visitor->enterNode($node, $state);
        }
        return null;
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
        $visitor = $this->loadBaseTypeVisitor($node->getType());
        if (!empty($visitor)) {
            return $visitor->leaveNode($node, $state);
        }
        return null;
    }

}