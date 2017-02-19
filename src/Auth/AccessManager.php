<?php

namespace FindBrok\PersonalityInsights\Auth;

use FindBrok\PersonalityInsights\Exceptions\InvalidCredentialsName;

class AccessManager
{
    /**
     * The Service ID in IOC.
     */
    const SERVICE_ID = 'PIAccessManager';

    /**
     * The Credentials we are using.
     *
     * @var array
     */
    protected $credentials = [];

    /**
     * Credentials name to use.
     *
     * @var string
     */
    protected $credentialsName;

    /**
     * The API Version we are using.
     *
     * @var string
     */
    protected $apiVersion = null;

    /**
     * CredentialManager constructor.
     *
     * @param string $credentialsName
     * @param string $apiVersion
     */
    public function __construct($credentialsName = null, $apiVersion = null)
    {
        $this->setCredentialsName($credentialsName);
        $this->setCredentials($credentialsName);
        $this->setApiVersion($apiVersion);
    }

    /**
     * Sets the Credentials name.
     *
     * @param string $credentialsName
     *
     * @return $this
     */
    public function setCredentialsName($credentialsName = null)
    {
        // No credentials name.
        if (is_null($credentialsName)) {
            $credentialsName = config('personality-insights.default_credentials');
        }

        // Credentials does not exist.
        if (! config()->has('personality-insights.credentials.'.$credentialsName)) {
            throw new InvalidCredentialsName;
        }

        $this->credentialsName = $credentialsName;

        return $this;
    }

    /**
     * Gets the Credentials name to use.
     *
     * @return null|string
     */
    public function getCredentialsName()
    {
        return $this->credentialsName;
    }

    /**
     * Returns the Credentials to use for requests.
     *
     * @return array
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Sets the Credentials we will use to perform requests.
     *
     * @param string $name
     *
     * @throws InvalidCredentialsName
     *
     * @return $this
     */
    public function setCredentials($name = null)
    {
        // No name given.
        if (is_null($name)) {
            $name = config('personality-insights.default_credentials');
        }

        // Credentials does not exist.
        if (! config()->has('personality-insights.credentials.'.$name)) {
            throw new InvalidCredentialsName;
        }

        $this->credentials = config('personality-insights.credentials.'.$name);

        return $this;
    }

    /**
     * Returns the API version to use for
     * making requests.
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * Sets ths API version we are using.
     *
     * @param string $apiVersion
     *
     * @return $this
     */
    public function setApiVersion($apiVersion = null)
    {
        // No version specified.
        if (is_null($apiVersion)) {
            $apiVersion = config('personality-insights.api_version') ?: 'v3';
        }

        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * Get the API path used for getting Profile.
     *
     * @return string
     */
    public function getProfileResourcePath()
    {
        return $this->getApiVersion().'/profile';
    }

    /**
     * Gets the Default Credentials.
     *
     * @return array
     */
    public function getDefaultCredentials()
    {
        return config('personality-insights.credentials.'.config('personality-insights.default_credentials'));
    }
}
