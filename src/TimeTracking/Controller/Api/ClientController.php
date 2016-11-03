<?php
/**
 * Created by PhpStorm.
 * User: dava
 * Date: 01.11.16
 * Time: 19:14
 */

namespace TimeTracking\Controller\Api;


use TimeTracking\Controller\AbstractController;
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
