<?php

namespace TimeTracking\View\Helper;


use TimeTracking\Config;

class TitleViewHelper extends AbstractViewHelper
{
    const KEY_DEFAULT = '__default';

    /**
     * @return string
     */
    public function render()
    {
        $config = Config::get();
        $titles = $config['meta']['titles'];
        $dispatcher = $this->getRenderer()->getDispatcher();

        // Validate controller titles
        $controller = $dispatcher->getController();
        if (!isset($titles[$controller])) {
            $controller = static::KEY_DEFAULT;
        }

        // Validate action titles
        $action = $dispatcher->getAction(true);
        if (!isset($titles[$controller][$action])) {
            $action = static::KEY_DEFAULT;
        }

        return $titles[$controller][$action];
    }
}
