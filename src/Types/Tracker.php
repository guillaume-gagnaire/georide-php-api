<?php

namespace GuillaumeGagnaire\Georide\API\Types;

use GuillaumeGagnaire\Georide\API\Client;

/**
 * Defines a Georide tracker
 */
class Tracker
{
    private $client;

    public $trackerId;
    public $trackerName;
    public $deviceButtonAction;
    public $vibrationLevel;
    public $positionId;
    public $fixtime;
    public $latitude;
    public $longitude;
    public $altitude;
    public $lockedPositionId;
    public $lockedLatitude;
    public $lockedLongitude;
    public $role;
    public $lastPaymentDate;
    public $giftCardId;
    public $expires;
    public $activationDate;
    public $odometer;
    public $isLocked;
    public $isStolen;
    public $isCrashed;
    public $speed;
    public $moving;
    public $canSeePosition;
    public $canLock;
    public $canUnlock;
    public $canShare;
    public $canUnshare;
    public $canCheckSpeed;
    public $canSeeStatistics;
    public $canSendBrokenDownSignal;
    public $canSendStolenSignal;
    public $status;

    /**
     * Constructor.
     *
     * Hydrates the model with retrieved data.
     *
     * @param array $data
     * @param \GuillaumeGagnaire\Georide\API\Client $client
     */
    public function __construct($data = [], Client $client)
    {
        $this->client = $client;

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Unlocks a tracker.
     *
     * @return boolean
     */
    public function toggleLock(): bool
    {
        try {
            $this->client->request('POST', "/tracker/$this->trackerId/toggleLock");
        } catch (ApiException $e) {
            return false;
        }
        return true;
    }

    /**
     * Unlocks a tracker.
     *
     * @return boolean
     */
    public function lock(): bool
    {
        try {
            $this->client->request('POST', "/tracker/$this->trackerId/lock");
        } catch (ApiException $e) {
            return false;
        }
        return true;
    }

    /**
     * Unlocks a tracker.
     *
     * @return boolean
     */
    public function unlock(): bool
    {
        try {
            $this->client->request('POST', "/tracker/$this->trackerId/unlock");
        } catch (ApiException $e) {
            return false;
        }
        return true;
    }

    /**
     * Get a list of trips in a specified date interval
     *
     * @param string $from
     * @param string $to
     * @return array|null
     */
    public function getTrips(string $from, string $to):? array
    {
        $from = date_format(date_timestamp_set(new \DateTime(), strtotime($from)), 'c');
        $to = date_format(date_timestamp_set(new \DateTime(), strtotime($to)), 'c');
        try {
            $trips = $this->client->request('GET', "/tracker/$this->trackerId/trips", [
                'from' => $from,
                'to' => $to
            ]);
            $ret = [];
            foreach ($trips as $trip) {
                $ret[] = new Trip($trip);
            }
        } catch (ApiException $e) {
            return null;
        }
        return $ret;
    }

    /**
     * Get a list of positions in a specified date interval
     *
     * @param string $from
     * @param string $to
     * @return array|null
     */
    public function getPositions(string $from, string $to):? array
    {
        $from = date_format(date_timestamp_set(new \DateTime(), strtotime($from)), 'c');
        $to = date_format(date_timestamp_set(new \DateTime(), strtotime($to)), 'c');
        try {
            $positions = $this->client->request('GET', "/tracker/$this->trackerId/trips/positions", [
                'from' => $from,
                'to' => $to
            ]);
            $ret = [];
            foreach ($positions as $position) {
                $ret[] = new Position($position);
            }
        } catch (ApiException $e) {
            return null;
        }
        return $ret;
    }
}
