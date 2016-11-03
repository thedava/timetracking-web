<?php
/**
 * Created by PhpStorm.
 * User: dava
 * Date: 01.11.16
 * Time: 23:47
 */

namespace TimeTracking\View\Helper;


use TimeTracking\Renderer\Php;

abstract class AbstractViewHelper
{
    /** @var Php */
    protected $renderer;

    /**
     * AbstractViewHelper constructor.
     *
     * @param Php $renderer
     */
    public function __construct(Php $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @return Php
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @return string
     */
    abstract public function render();

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
