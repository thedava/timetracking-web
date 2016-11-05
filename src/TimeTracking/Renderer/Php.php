<?php

namespace TimeTracking\Renderer;

use TimeTracking\DispatcherAwareTrait;
use TimeTracking\View\Helper\AbstractViewHelper;
use TimeTracking\View\Helper\EscapeViewHelper;

class Php
{
    use DispatcherAwareTrait,
        EscapeViewHelper;

    /**
     * @param string $template
     *
     * @return string
     */
    protected function getTemplatePath($template)
    {
        return _ROOT_ . '/view/' . $template . '.phtml';
    }

    /**
     * Renders the given template
     *
     * @param string $__template
     * @param array  $__vars
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function render($__template, array $__vars = [])
    {
        extract($__vars, EXTR_SKIP);
        $__path = $this->getTemplatePath($__template);

        if (!file_exists($__path)) {
            throw new \Exception('Unable to render template "' . $__template . '"');
        }

        ob_start();
        include $__path;
        $__content = ob_get_contents();
        ob_end_clean();

        return $__content;
    }

    /**
     * Invokes the given view helper
     *
     * @param string $viewHelperClass
     *
     * @throws \Exception
     *
     * @return string
     */
    public function invoke($viewHelperClass)
    {
        $instance = new $viewHelperClass($this);

        if (!$instance instanceof AbstractViewHelper) {
            throw new \Exception('"' . $viewHelperClass . '" is not a valid view helper!');
        }

        return $instance->render();
    }
}
