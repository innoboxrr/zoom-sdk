<?php

namespace Innoboxrr\ZoomSdk\Zoom\API;

use Illuminate\Support\Facades\Http;
use Innoboxrr\ZoomSdk\Contracts\Abstracts\AbstractSetup;

class Authorization extends AbstractSetup
{
    protected $requiredKeys = [
        'client',
        'account',
        'secret',
        'endpoint'
    ];

    protected $clientSecret;
    protected $authResponse;
    protected $token;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->setClientSecret();
        $this->setAuthResponse();
        $this->setToken();
    }

    protected function setClientSecret()
    {
        $this->clientSecret = base64_encode($this->getClient() . ':' . $this->getSecret());
    }

    protected function getClientSecret()
    {
        return $this->clientSecret;
    }

    protected function setAuthResponse()
    {
        $url = "https://zoom.us/oauth/token?grant_type=account_credentials&account_id={$this->getAccount()}";

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->getClientSecret(),
        ])->post($url);

        $this->authResponse = $response->json();
    }

    protected function getAuthResponse() 
    {
        return $this->authResponse;
    }

    protected function setToken()
    {
        if (!array_key_exists('error', $this->authResponse) && array_key_exists('access_token', $this->authResponse)) {
            $this->token = $this->authResponse['access_token'];
        } else {
            abort(401);
        }
    }

    public function getToken()
    {
        return $this->token;
    }
}
