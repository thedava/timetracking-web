<?php

namespace TimeTracking\Service;

use TheDava\Config;

class ClientConfigGenerator
{
    /**
     * Returns the static config for the client
     *
     * @return array
     */
    protected function getStaticConfig()
    {
        $config = Config::get();

        return $config['client']['config'];
    }

    /**
     * Gathers all dynamic configuration options for the timetracking client
     *
     * @return array
     */
    protected function gatherDynamicConfig()
    {
        $apiUrl = $_SERVER['HTTP_HOST'];
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $apiUrl = 'https://' . $apiUrl;
        } else {
            $apiUrl = 'http://' . $apiUrl;
        }

        return [
            'api_url' => $apiUrl,
        ];
    }

    /**
     * @return array
     */
    public function generate()
    {
        return array_merge($this->getStaticConfig(), $this->gatherDynamicConfig());
    }

    /**
     * Provides the download of the config file
     */
    public function provideDownload()
    {
        $config = Config::get();

        header('Content-Disposition: attachment; filename="' . $config['client']['configFileName'] . '"');

        echo json_encode($this->generate());
        exit;
    }
}
