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
use phpsonar\SonarNode\Arg;
use phpsonar\SonarNode\Const_;
use phpsonar\SonarNode\Expr;
use phpsonar\SonarNode\Identifier;
use phpsonar\SonarNode\Name;
use phpsonar\SonarNode\NullableType;
use phpsonar\SonarNode\Param;
use phpsonar\SonarNode\Scalar;
use phpsonar\SonarNode\Stmt;
use phpsonar\SonarNode\VarLikeIdentifier;

class SonarTypeInferencer extends AbsTractTypeInferencer
{

    public function __construct(Analyzer $analyzer, array $visitorMap = [])
    {
        parent::__construct($analyzer, $visitorMap);

        $this->registerTypeVisitor('Arg', new Arg($analyzer, $visitorMap));
        $this->registerTypeVisitor('Const', new Const_($analyzer, $visitorMap));
        $this->registerTypeVisitor('Expr', new Expr($analyzer, [
            'Expr_FuncCall'=> new Expr\FuncCall($analyzer),
            'Expr_Assign' => new Expr\Assign($analyzer)
        ]));
        $this->registerTypeVisitor('Identifier', new Identifier($analyzer, $visitorMap));
        $this->registerTypeVisitor('Name', new Name($analyzer, $visitorMap));
        $this->registerTypeVisitor('NullableType', new NullableType($analyzer, $visitorMap));
        $this->registerTypeVisitor('Param', new Param($analyzer, $visitorMap));
        $this->registerTypeVisitor('Scalar', new Scalar($analyzer, $visitorMap));
        $this->registerTypeVisitor('Stmt', new Stmt($analyzer, [
            'Stmt_Function' => new Stmt\Function_($analyzer),
            'Stmt_Const' => new Stmt\Const_($analyzer),
            'Stmt_Class' => new Stmt\Class_($analyzer),
            'Stmt_Interface' => new Stmt\Interface_($analyzer),
            'Stmt_Namespace' => new Stmt\Namespace_($analyzer),
        ]));
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