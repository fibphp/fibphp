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

class TypeInferencer extends AbsTractTypeInferencer
{

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
        $visitor = $this->loadTypeVisitor($node->getType());
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
        $visitor = $this->loadTypeVisitor($node->getType());
        if (!empty($visitor)) {
            return $visitor->leaveNode($node, $state);
        }
        return null;
    }

}