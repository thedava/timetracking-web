<?php

namespace TimeTracking\Controller\Plugins;

use TimeTracking\DispatcherAwareTrait;

/**
 * @codeCoverageIgnore
 */
trait RedirectControllerPlugin
{
    use DispatcherAwareTrait;

    /**
     * Redirect to the given url
     *
     * @param string $url
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Redirect to the given controller and action
     *
     * @param string $controller The pure controller without prefix and suffix
     * @param string $action     The pure action without prefix and suffix
     */
    protected function redirectTo($controller, $action = null)
    {
        $options = $this->getDispatcher()->getOptions();

        if (empty($action)) {
            $action = $options['action_default'];
        }

        $query = [
            $options['controller_param'] => $controller,
            $options['action_param']     => $action,
        ];
        $this->redirect('/' . http_build_query($query));
    }
}
