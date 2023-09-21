<?php

namespace Innoboxrr\ZoomSdk\Zoom\API;

use Innoboxrr\ZoomSdk\Support\Constants;
use Innoboxrr\ZoomSdk\Contracts\Abstracts\AbstractSetup;
use Illuminate\Support\Facades\Http;
use Innoboxrr\ZoomSdk\Support\MeetingValidations;

class Meeting extends AbstractSetup
{
    use MeetingValidations;

    protected $requiredKeys = [
        'token',
        'endpoint'
    ];

    public function create()
    {
        $body = [
            'topic' => $this->getTopic('Mi reunión de Zoom'),
            'start_time' => $this->getStartTime(now()->format('Y-m-d\TH:i:s')),
            'duration' => $this->getDuration(60),
            'timezone' => $this->getTimezone(config('zoomsdk.timezone')),
            'password' => $this->getPassword(substr(uniqid(), 0, 6)),
        ];

        $this->createValidation($body);

        return $this->sendRequest('post', 'users/me/meetings', $body);
    }

    public function get(string $meetingId)
    {
        return $this->sendRequest('get', 'meetings/' . $meetingId);
    }

    public function update($meetingId, $data = [])
    {
        $this->updateValidation($data);

        $this->sendRequest('patch', 'meetings/' . $meetingId, $data);
    
        return $this->get($meetingId);
    }

    public function delete($meetingId)
    {
        $this->sendRequest('delete', 'meetings/' . $meetingId);
    
        return true;
    }

    private function sendRequest($method, $uri, $body = [])
    {
        $url = $this->getEndpoint() . $uri;

        $headers = [
            'content-type' => 'application/json'
        ];

        $response = Http::withToken($this->getToken())
            ->withHeaders($headers)
            ->{$method}($url, $body);

        if (!$response->successful()) {
            throw new \Exception("Failed to {$method} a Zoom meeting.");
        }

        return $response->json();
    }
}
