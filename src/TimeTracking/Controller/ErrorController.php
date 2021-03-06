<?php

namespace TimeTracking\Controller;

use TheDava\Controller\AbstractController;

class ErrorController extends AbstractController
{
    /**
     * Default action of every controller
     *
     * @return mixed
     */
    public function indexAction()
    {
        return [
            'error'       => null,
            'last_errors' => $this->getDispatcher()->getLastErrors(),
        ];
    }
}
