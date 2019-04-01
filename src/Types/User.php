<?php

namespace GuillaumeGagnaire\Georide\API\Types;

/**
 * Defines a Georide user
 */
class User
{
    public $id;
    public $email;
    public $firstName;
    public $createdAt;
    public $phoneNumber;
    public $pushUserToken;
    public $legal;
    public $dateOfBirth;

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
