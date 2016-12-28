<?php

namespace TimeTracking\Controller;

use TheDava\Controller\AbstractController;
use TheDava\Controller\Plugins\RedirectControllerPlugin;

class IndexController extends AbstractController
{
    use RedirectControllerPlugin;

    /**
     * Default action of every controller
     *
     * @return mixed
     */
    public function indexAction()
    {
        return [];
    }

    public function debugAction()
    {
        if (!TT_DEBUG) {
            $this->redirectTo('Index');
        }

        return [
            'server' => $_SERVER,
        ];
    }
}
