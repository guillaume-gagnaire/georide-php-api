<?php

namespace GuillaumeGagnaire\Georide\API;

use GuillaumeGagnaire\Georide\API\ApiException;

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
     * Creates a client instance
     *
     * @param string $api_url       API server base URL
     */
    public function __construct(string $api_url = 'https://api.georide.fr')
    {
        $this->api_url = rtrim($api_url, '/');
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
     * @return object
     * @throws ApiException
     */
    public function request(string $method, string $endpoint, array $data = []): object
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
     * Provides a new auth token.
     *
     * @param string $token     Auth token
     * @return void
     */
    public function setAuthToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * Sign in to the Georide API
     *
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function login(string $email, string $password): bool
    {
        try {
            $ret = $this->request('POST', '/user/login', [
                'email' => $email,
                'password' => $password
            ]);
            $this->token = $ret->authToken;
        } catch (ApiException $e) {
            return false;
        }
        return true;
    }

    /**
     * Sign out from the Georide API
     *
     * @return boolean
     */
    public function logout(): bool
    {
        if ($this->token === null) {
            return false;
        }

        try {
            $this->request('POST', '/user/logout');
            $this->token = null;
        } catch (ApiException $e) {
            return false;
        }

        return true;
    }
}
