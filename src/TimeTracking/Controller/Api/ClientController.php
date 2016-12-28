<?php

namespace TimeTracking\Controller\Api;

use TheDava\Controller\AbstractController;
use TimeTracking\Service\ClientConfigGenerator;

class ClientController extends AbstractController
{
    /**
     * Default action of every controller
     *
     * @return mixed
     */
    public function indexAction()
    {
        return null;
    }

    /**
     * Provides basic client configurations
     *
     * @return array
     */
    public function downloadConfigAction()
    {
        $generator = new ClientConfigGenerator();

        return $generator->generate();
    }
}
