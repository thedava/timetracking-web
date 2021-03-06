<?php

namespace TimeTracking\Controller;

use TheDava\Config;
use TheDava\Controller\AbstractController;
use TheDava\Controller\Plugins\RedirectControllerPlugin;
use TimeTracking\Service\ClientConfigGenerator;

class ClientController extends AbstractController
{
    use RedirectControllerPlugin;

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
     * Redirects to the download page of the time tracking client
     */
    public function downloadClientAction()
    {
        $config = Config::get();
        $this->redirect($config['client']['downloadUrl']);
    }

    /**
     * Provides basic client configurations as file to download (and import into the client)
     */
    public function downloadConfigAction()
    {
        $generator = new ClientConfigGenerator();

        $generator->provideDownload();
    }
}
