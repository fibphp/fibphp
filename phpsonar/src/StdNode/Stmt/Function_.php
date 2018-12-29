<?php

namespace phpsonar\StdNode\Stmt;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use phpsonar\Abstracts\AbsTractTypeInferencer;
use phpsonar\CodeAt;
use phpsonar\Exception\ReDefineWarn;
use phpsonar\StdNode\Stmt;
use phpsonar\State;
use phpsonar\Types\FunctionType;
use Tiny\Exception\Error;

/**
 * @property Node\Name $namespacedName Namespaced name (if using NameResolver)
 */
class Function_ extends Stmt
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
        /** @var \PhpParser\Node\Stmt\Function_ $node */
        /** @var Identifier $name_ */
        $name_ = $node->name;
        $name = $name_->toLowerString();
        $global_map = $state->getGlobalMap();
        $function_name = $state->_namespace($name);
        try {
            $global_map->setFunction($function_name, new FunctionType($node), CodeAt::createByNode($this->getAnalyzer(), $node));
        } catch (ReDefineWarn $ex) {
            $state->pushWarn($ex->getName(), $ex);
        } catch (Error $ex) {
            $state->pushError($function_name, $ex);
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
