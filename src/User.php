<?php

namespace GuillaumeGagnaire\Georide\API;

use GuillaumeGagnaire\Georide\API\Client;
use GuillaumeGagnaire\Georide\API\ApiException;

/**
 * Handles the requests to the Georide API
 */
class User
{
    private $client;

    /**
     * Creates the instance of the User namespace
     *
     * @param \GuillaumeGagnaire\Georide\API\Client $client     Instanciated client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
            $ret = $this->client->request('POST', '/user/login', [
                'email' => $email,
                'password' => $password
            ]);
            $this->client->setAuthToken($ret->authToken);
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
        if ($this->client->getAuthToken() === null) {
            return false;
        }

        try {
            $this->client->request('POST', '/user/logout');
            $this->client->setAuthToken(null);
        } catch (ApiException $e) {
            return false;
        }

        return true;
    }

    /**
     * Renew the current session for 30 days.
     *
     * @return boolean
     */
    public function renewSession(): bool
    {
        if ($this->client->getAuthToken() === null) {
            return false;
        }

        try {
            $this->client->request('POST', '/user/new-token');
            $this->client->setAuthToken($ret->authToken);
        } catch (ApiException $e) {
            return false;
        }

        return true;
    }

    /**
     * Get informations from the current user.
     *
     * @return Types\User|null
     */
    public function getInformations():? Types\User
    {
        try {
            $ret = $this->client->request('GET', '/user');
        } catch (ApiException $e) {
            return null;
        }
        return new Types\User($ret);
    }

    /**
     * Get trackers who belongs to the current user.
     *
     * @return array|null
     */
    public function getTrackers():? array
    {
        try {
            $trackers = $this->client->request('GET', '/user/trackers');
            $ret = [];
            foreach ($trackers as $tracker) {
                $ret[] = new Types\Tracker($tracker, $this->client);
            }
        } catch (ApiException $e) {
            return null;
        }
        return $ret;
    }
}
