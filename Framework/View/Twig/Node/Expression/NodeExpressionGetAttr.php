<?php

namespace Framework\View\Twig\Node\Expression;

use Framework\View\Twig\Node\NodeExpression;
use Framework\View\Twig\Compiler;
use Framework\View\Twig\NodeInterface;
use Framework\View\Twig\Template;

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2009 Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class NodeExpressionGetAttr extends NodeExpression
{
    public function __construct(NodeExpression $node, NodeExpression $attribute, NodeExpression $arguments = null, $type, $lineno)
    {
        $nodes = array('node' => $node, 'attribute' => $attribute);
        if (null !== $arguments) {
            $nodes['arguments'] = $arguments;
        }

        parent::__construct($nodes, array('type' => $type, 'is_defined_test' => false, 'ignore_strict_check' => false, 'disable_c_ext' => false), $lineno);
    }

    public function compile(Compiler $compiler)
    {
        if (function_exists('twig_template_get_attributes') && !$this->getAttribute('disable_c_ext')) {
            $compiler->raw('twig_template_get_attributes($this, ');
        } else {
            $compiler->raw('$this->getAttribute(');
        }

        if ($this->getAttribute('ignore_strict_check')) {
            $this->getNode('node')->setAttribute('ignore_strict_check', true);
        }

        $compiler->subcompile($this->getNode('node'));

        $compiler->raw(', ')->subcompile($this->getNode('attribute'));

        // only generate optional arguments when needed (to make generated code more readable)
        $needFourth = $this->getAttribute('ignore_strict_check');
        $needThird = $needFourth || $this->getAttribute('is_defined_test');
        $needSecond = $needThird || Template::ANY_CALL !== $this->getAttribute('type');
        $needFirst = $needSecond || $this->hasNode('arguments');

        if ($needFirst) {
            if ($this->hasNode('arguments')) {
                $compiler->raw(', ')->subcompile($this->getNode('arguments'));
            } else {
                $compiler->raw(', array()');
            }
        }

        if ($needSecond) {
            $compiler->raw(', ')->repr($this->getAttribute('type'));
        }

        if ($needThird) {
            $compiler->raw(', ')->repr($this->getAttribute('is_defined_test'));
        }

        if ($needFourth) {
            $compiler->raw(', ')->repr($this->getAttribute('ignore_strict_check'));
        }

        $compiler->raw(')');
    }
}
