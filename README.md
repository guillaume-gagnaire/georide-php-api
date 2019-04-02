# georide-php-api

>  GeoRide is a motorcycle GPS tracker with a full featured smartphone app. Since GeoRide have an open API, the purpose of this package is to handle requests to the open API, managing authentication and provide a set of classes to interact with the GeoRide API.

## Installation

```bash
composer require guillaume-gagnaire/georide-api
```

## Usage

```php
<?php

use GuillaumeGagnaire\Georide\API\Client;
use GuillaumeGagnaire\Georide\API\ApiException;

try {
    // Get an instance of Georide API Client
    $georide = new Client();

    // Create a new session ...
    $georide->user->login('test@example.com', 'passw0rd');
    $authToken = $georide->getAuthToken();

    // or resume an existing session
    $georide->setAuthToken($authToken);

    /**
     * And then, you can call the API freely,
     * ex: get the trips from the first found tracker
     */
    $trackers = $georide->user->getTrackers();
    if (sizeof($trackers) === 0) {
        throw new Exception("No tracker configured.");
    }

    $tracker = $trackers[0];

    // Lock your motorcycle
    $tracker->lock();

    // Unlock your motorcycle
    $tracker->unlock();

    // Toggle the lock of your motorcycle
    $tracker->toggleLock();

    // Get the trips of your motorcycle
    $trips = $tracker->getTrips('2019-02-23', '2019-02-24');

    // Get the GPS positions of your motorcycle
    $positions = $tracker->getPositions('2019-02-23', '2019-02-24');
} catch (ApiException $e) {
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}

```