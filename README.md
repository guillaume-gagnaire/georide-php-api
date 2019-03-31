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
    $trackers = $georide->request('GET', '/user/trackers');
    if (sizeof($trackers) === 0) {
        throw new Exception("No tracker configured.");
    }

    $tracker = $trackers[0];
    $trips = $georide->request('GET', "/tracker/$tracker->trackerId/trips", [
        'from' => '2019-02-23T00:00:00Z',
        'to' => '2019-02-24T00:00:00Z'
    ]);

    var_dump($trips);
} catch (ApiException $e) {
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}

```