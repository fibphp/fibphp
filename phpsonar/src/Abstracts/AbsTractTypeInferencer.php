<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\12\15 0015
 * Time: 23:47
 */

namespace phpsonar\Abstracts;

use PhpParser\Node;
use phpsonar\Interfaces\Visitor;
use phpsonar\State;

abstract class AbsTractTypeInferencer extends AbstractTypeVisitor implements Visitor
{


    /**
     * If NodeVisitor::enterNode() returns DONT_TRAVERSE_CHILDREN, child nodes
     * of the current node will not be traversed for any visitors.
     *
     * For subsequent visitors enterNode() will still be called on the current
     * node and leaveNode() will also be invoked for the current node.
     */
    const DONT_TRAVERSE_CHILDREN = 1;

    /**
     * If NodeVisitor::enterNode() or NodeVisitor::leaveNode() returns
     * STOP_TRAVERSAL, traversal is aborted.
     *
     * The afterTraverse() method will still be invoked.
     */
    const STOP_TRAVERSAL = 2;

    /**
     * If NodeVisitor::leaveNode() returns REMOVE_NODE for a node that occurs
     * in an array, it will be removed from the array.
     *
     * For subsequent visitors leaveNode() will still be invoked for the
     * removed node.
     */
    const REMOVE_NODE = 3;

    /**
     * If NodeVisitor::enterNode() returns DONT_TRAVERSE_CURRENT_AND_CHILDREN, child nodes
     * of the current node will not be traversed for any visitors.
     *
     * For subsequent visitors enterNode() will not be called as well.
     * leaveNode() will be invoked for visitors that has enterNode() method invoked.
     */
    const DONT_TRAVERSE_CURRENT_AND_CHILDREN = 4;

    /** @var bool Whether traversal should be stopped */
    protected $stopTraversal = false;

    /**
     * Traverses an array of nodes using the registered visitors.
     *
     * @param Node[] $nodes Array of nodes
     *
     * @param State $state
     * @return Node[] Traversed array of nodes
     */
    public function traverse(array $nodes, State $state): array
    {
        $this->stopTraversal = false;

        if (null !== $return = $this->beforeTraverse($nodes, $state)) {
            $nodes = $return;
        }

        $nodes = $this->traverseArray($nodes, $state);

        if (null !== $return = $this->afterTraverse($nodes, $state)) {
            $nodes = $return;
        }

        return $nodes;
    }

    /**
     * Recursively traverse a node.
     *
     * @param Node $node Node to traverse.
     *
     * @param State $state
     * @return Node Result of traversal (may be original node or new one)
     */
    protected function traverseNode(Node $node, State $state): Node
    {
        foreach ($node->getSubNodeNames() as $name) {
            $subNode =& $node->$name;

            if (\is_array($subNode)) {
                $subNode = $this->traverseArray($subNode, $state);
                if ($this->stopTraversal) {
                    break;
                }
            } elseif ($subNode instanceof Node) {
                $traverseChildren = true;
                $breakVisitorIndex = null;

                $return = $this->enterNode($subNode, $state);
                if (null !== $return) {
                    if ($return instanceof Node) {
                        $this->ensureReplacementReasonable($subNode, $return);
                        $subNode = $return;
                    } elseif (self::DONT_TRAVERSE_CHILDREN === $return) {
                        $traverseChildren = false;
                    } elseif (self::DONT_TRAVERSE_CURRENT_AND_CHILDREN === $return) {
                        break;
                    } elseif (self::STOP_TRAVERSAL === $return) {
                        $this->stopTraversal = true;
                        break;
                    } else {
                        throw new \LogicException(
                            'enterNode() returned invalid value of type ' . gettype($return)
                        );
                    }
                }

                if ($traverseChildren) {
                    $subNode = $this->traverseNode($subNode, $state);
                    if ($this->stopTraversal) {
                        break;
                    }
                }

                $return = $this->leaveNode($subNode, $state);

                if (null !== $return) {
                    if ($return instanceof Node) {
                        $this->ensureReplacementReasonable($subNode, $return);
                        $subNode = $return;
                    } elseif (self::STOP_TRAVERSAL === $return) {
                        $this->stopTraversal = true;
                        break;
                    } elseif (\is_array($return)) {
                        throw new \LogicException(
                            'leaveNode() may only return an array ' .
                            'if the parent structure is an array'
                        );
                    } else {
                        throw new \LogicException(
                            'leaveNode() returned invalid value of type ' . gettype($return)
                        );
                    }
                }
            }
        }

        return $node;
    }

    /**
     * Traverses an array of nodes using the registered visitors.
     *
     * @param Node[] $nodes Array of nodes
     *
     * @param State $state
     * @return Node[] Traversed array of nodes
     */
    protected function traverseArray(array $nodes, State $state): array
    {

        $doNodes = [];

        foreach ($nodes as $i => &$node) {
            if (!($node instanceof Node)) {
                if (\is_array($node)) {
                    throw new \LogicException('Invalid node structure: Contains nested arrays');
                }
                continue;
            }
            $traverseChildren = true;

            $return = $this->enterNode($node, $state);
            if (null !== $return) {
                if ($return instanceof Node) {
                    $this->ensureReplacementReasonable($node, $return);
                    $node = $return;
                } elseif (self::DONT_TRAVERSE_CHILDREN === $return) {
                    $traverseChildren = false;
                } elseif (self::DONT_TRAVERSE_CURRENT_AND_CHILDREN === $return) {
                    break;
                } elseif (self::STOP_TRAVERSAL === $return) {
                    $this->stopTraversal = true;
                    break;
                } else {
                    throw new \LogicException(
                        'enterNode() returned invalid value of type ' . gettype($return)
                    );
                }
            }

            if ($traverseChildren) {
                $node = $this->traverseNode($node, $state);
                if ($this->stopTraversal) {
                    break;
                }
            }

            $return = $this->leaveNode($node, $state);

            if (null !== $return) {
                if ($return instanceof Node) {
                    $this->ensureReplacementReasonable($node, $return);
                    $node = $return;
                } elseif (\is_array($return)) {
                    $doNodes[] = [$i, $return];
                    break;
                } elseif (self::REMOVE_NODE === $return) {
                    $doNodes[] = [$i, []];
                    break;
                } elseif (self::STOP_TRAVERSAL === $return) {
                    $this->stopTraversal = true;
                    break;
                } elseif (false === $return) {
                    throw new \LogicException(
                        'bool(false) return from leaveNode() no longer supported. ' .
                        'Return NodeTraverser::REMOVE_NODE instead'
                    );
                } else {
                    throw new \LogicException(
                        'leaveNode() returned invalid value of type ' . gettype($return)
                    );
                }
            }

        }

        if (!empty($doNodes)) {
            while (list($i, $replace) = array_pop($doNodes)) {
                array_splice($nodes, $i, 1, $replace);
            }
        }

        return $nodes;
    }

    private function ensureReplacementReasonable($old, $new)
    {
        if ($old instanceof Node\Stmt && $new instanceof Node\Expr) {
            throw new \LogicException(
                "Trying to replace statement ({$old->getType()}) " .
                "with expression ({$new->getType()}). Are you missing a " .
                "Stmt_Expression wrapper?"
            );
        }

        if ($old instanceof Node\Expr && $new instanceof Node\Stmt) {
            throw new \LogicException(
                "Trying to replace expression ({$old->getType()}) " .
                "with statement ({$new->getType()})"
            );
        }
    }

}