<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\15 0015
 * Time: 22:41
 */

namespace phpsonar\Interfaces;

use PhpParser\Node;
use phpsonar\State;

interface Visitor
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
    public function beforeTraverse(array $nodes, State $state);

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
    public function afterTraverse(array $nodes, State $state);

}