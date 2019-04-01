<?php

namespace GuillaumeGagnaire\Georide\API;

use GuillaumeGagnaire\Georide\API\ApiException;
use GuillaumeGagnaire\Georide\API\User;

/**
 * Handles the requests to the Georide API
 */
class Client
{
    /**
     * API server base URL, without ending slash.
     *
     * @var string
     */
    private $api_url;

    /**
     * Auth token
     *
     * @var string|null
     */
    private $token = null;

    /**
     * Global timeout for the requests
     *
     * @var integer
     */
    private $timeout = 30;

    /**
     * Contains the methods of user management
     *
     * @var \GuillaumeGagnaire\Georide\API\User
     */
    public $user;

    /**
     * Creates a client instance
     *
     * @param string $api_url       API server base URL
     */
    public function __construct(string $api_url = 'https://api.georide.fr')
    {
        $this->api_url = rtrim($api_url, '/');
        $this->user = new User($this);
    }

    /**
     * Configures the global timeout for all the requests.
     *
     * @param integer $timeout      Global timeout (in seconds)
     * @return void
     */
    public function setTimeout(int $timeout = 30): void
    {
        $this->timeout = $timeout;
    }

    /**
     * Execute a request to the Georide API
     *
     * @param string $method        HTTP method to use
     * @param string $endpoint      API endpoint
     * @param array $data           Data to send
     * @return mixed
     * @throws ApiException
     */
    public function request(string $method, string $endpoint, array $data = [])
    {
        // Adds a leading slash to $endpoint
        if (substr($endpoint, 0, 1) !== '/') {
            $endpoint = '/' . $endpoint;
        }

        $params = [
            'timeout' => $this->timeout,
            'headers' => []
        ];

        // Sets authorization header
        if ($this->token !== null) {
            $params['headers']['Authorization'] = 'Bearer ' . $this->token;
        }

        switch ($method) {
            case 'GET':
            case 'DELETE':
                $params['query'] = $data;
                break;
            case 'POST':
            case 'PUT':
            case 'PATCH':
                $params['json'] = $data;
                break;
        }

        // Execute the request
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request(
                $method,
                $this->api_url . $endpoint,
                $params
            );
            return json_decode($response->getBody());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
        return false;
    }

    /**
     * Retrieves the auth token, or null if not logged in.
     *
     * @return string|null
     */
    public function getAuthToken():? string
    {
        return $this->token;
    }

    /**
     * Provides a new auth token, or null if logged out.
     *
     * @param string|null $token     Auth token
     * @return void
     */
    public function setAuthToken($token): void
    {
        $this->token = $token;
    }
}
