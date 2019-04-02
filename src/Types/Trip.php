<?php

namespace GuillaumeGagnaire\Georide\API\Types;

/**
 * Defines a Georide user
 */
class Trip
{
    public $trackerId;
    public $averageSpeed;
    public $distance;
    public $duration;
    public $startAddress;
    public $niceStartAddress;
    public $startLat;
    public $startLon;
    public $endAddress;
    public $niceEndAddress;
    public $endLat;
    public $endLon;
    public $startTime;
    public $endTime;

    /**
     * Constructor.
     *
     * Hydrates the model with retrieved data.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
