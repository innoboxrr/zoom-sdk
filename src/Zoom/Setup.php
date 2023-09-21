<?php

namespace Innoboxrr\ZoomSdk\Zoom;

use Innoboxrr\ZoomSdk\Support\Constants;
use Innoboxrr\ZoomSdk\Contracts\Abstracts\AbstractSetup;
use Innoboxrr\ZoomSdk\Zoom\API\Authorization;

class Setup extends AbstractSetup
{

	protected $requiredKeys = [
		'account',
        'client',
        'secret'
    ];

    protected $token;

    protected $endpoint;

	public function __construct(array $config)
	{
		$this->parseConfig($config);

		parent::__construct($config);

		$this->setEndpoint();

		$this->setToken();
	}

	// Esto no lo he probado, pero ver si funciona.
	// La idea es que si no se pasan las credenciales las intente recuperar del archivo de configuración
	protected function parseConfig(array &$config)
    {
        foreach ($this->requiredKeys as $key) {

            if (!isset($config[$key])) {
            
                $valueFromConfigFile = config('zoomsdk.' . $key);
            
                if ($valueFromConfigFile) {
            
                    $config[$key] = $valueFromConfigFile;
            
                }
            }
        }
    }

	public function setToken()
	{
		$authorization = new Authorization($this->config->getData());

		$this->token = $authorization->getToken();
	}

	public function getToken()
	{
		return $this->token;
	}

	public function setEndpoint()
	{
		$this->endpoint = Constants::ENDPOINT;

		$this->config->merge(['endpoint' => $this->endpoint]);
	}

	public function getEndpoint()
	{
		return $this->endpoint;
	}

}