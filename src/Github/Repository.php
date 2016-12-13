<?php namespace Intilery\Github;

/**
 * Class Repository
 * @package Intilery\Github
 */
class Repository
{
    /**
     * @var
     */
    private $username;
    /**
     * @var
     */
    private $project;
    /**
     * @var
     */
    private $keyPath;
    /**
     * @var
     */
    private $path;
    /**
     * @var null
     */
    private $secret;
    /**
     * @var
     */
    private $replaces;

    /**
     * Repository constructor.
     * @param $usename
     * @param $project
     * @param $keyPath
     * @param $path
     * @param null $secret
     * @param null $replaces
     */
    public function __construct($usename, $project, $keyPath, $path, $secret = null, $replaces = null)
    {
        $this->username = $usename;
        $this->project = $project;
        $this->keyPath = $keyPath;
        $this->path = $path;
        $this->secret = $secret;
        $this->replaces = $replaces;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getKeyPath()
    {
        return $this->keyPath;
    }

    /**
     * @param mixed $keyPath
     */
    public function setKeyPath($keyPath)
    {
        $this->keyPath = $keyPath;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return mixed
     */
    public function getReplaces()
    {
        return $this->replaces;
    }

    /**
     * @param mixed $replaces
     */
    public function setReplaces($replaces)
    {
        $this->replaces = $replaces;
    }

}