<?php

namespace GuillaumeGagnaire\Georide\API\Types;

/**
 * Defines a Georide tracker position
 */
class Position
{
    public $id;
    public $fixtime;
    public $latitude;
    public $longitude;
    public $altitude;
    public $speed;
    public $address;

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
