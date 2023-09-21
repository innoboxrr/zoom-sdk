<?php

namespace Innoboxrr\ZoomSdk\Zoom\Traits;

use Innoboxrr\ZoomSdk\Zoom\API\Meeting;
use Innoboxrr\ZoomSdk\Zoom\Setup;

trait MeetingsOperations
{

	public function createMeeting(array $config = [])
    {
        $data = $this->prepareConfig($config);
        $meeting = new Meeting($data);
        return $meeting->create();
    }

    public function getMeeting(string $meetingId, array $config = [])
    {
        $data = $this->prepareConfig($config);
        $meeting = new Meeting($data);
        return $meeting->get($meetingId);
    }

    public function updateMeeting(string $meetingId, array $config = [])
    {
        $data = $this->prepareConfig($config);
        $meeting = new Meeting($data);
        return $meeting->update($meetingId);
    }

    public function deleteMeeting(string $meetingId, array $config = [])
    {
        $data = $this->prepareConfig($config);
        $meeting = new Meeting($data);
        return $meeting->delete($meetingId);
    }

    protected function prepareConfig(array $config): array
    {
        $setup = new Setup($config);

        return [
            'token' => $setup->getToken(),
            'endpoint' => $setup->getEndpoint()
        ] + ($config['meeting'] ?? []);
    }

}