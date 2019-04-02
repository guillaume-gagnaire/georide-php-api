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
    public function unlock()
    {
        try {
            $this->client->request('POST', "/tracker/$this->trackerId/unlock");
        } catch (ApiException $e) {
            return false;
        }
        return true;
    }
}
