<?php

namespace scarbo87\RestApiSdk;

class Context
{
    /**
     * @var array|null
     */
    protected $parameters;

    /**
     * @param array|null $parameters
     */
    public function __construct(array $parameters = null)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array|null
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasParameter($key)
    {
        return isset($this->parameters[$key]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParameter($key)
    {
        return isset($this->parameters[$key]) ? $this->parameters[$key] : null;
    }
}
