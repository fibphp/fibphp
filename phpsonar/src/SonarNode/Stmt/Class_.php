<?php

namespace phpsonar\SonarNode\Stmt;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use phpsonar\Abstracts\AbsTractTypeInferencer;
use phpsonar\CodeAt;
use phpsonar\Exception\ReDefineWarn;
use phpsonar\SonarNode\Stmt;
use phpsonar\State;
use phpsonar\Types\ClassType;
use Tiny\Exception\Error;

class Class_ extends Stmt
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
        /** @var \PhpParser\Node\Stmt\Class_ $node */
        /** @var Identifier $name_ */
        $name_ = $node->name;
        $name = $name_->toString();
        $global_map = $state->getGlobalScope();
        $class_name = $state->_namespace($name);
        try {
            $global_map->setClass($class_name, new ClassType($node), CodeAt::createByNode($this->getAnalyzer(), $node));
        } catch (ReDefineWarn $ex) {
            $state->pushWarn($ex->getName(), $ex);
        } catch (Error $ex) {
            $state->pushError($class_name, $ex);
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
