<?php namespace Intilery\Github;

/**
 * Class Manager
 * @package Intilery\Github
 */
class Manager
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * Manager constructor.
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function _clone()
    {
        return $this->__execute("git clone git@github.com:" . $this->repository->getUsername() .
            "/" . $this->repository->getProject() . ".git " . $this->repository->getPath());
    }

    /**
     * @param $message
     * @return mixed
     */
    public function _commit($message)
    {
        return $this->__execute("cd " . $this->repository->getPath() . ";git config user.email \"lookersync@intilery.com\";git config user.name \"Looker Sync\";git commit -a -m \"" . $message . "\"");
    }

    public function _add() {
        return $this->__execute("cd " . $this->repository->getPath() . ";git add *");
    }

    /**
     * @param $remote
     * @param $branch
     * @return mixed
     */
    public function _push($remote, $branch)
    {
        return $this->__execute("cd " . $this->repository->getPath() . ";git push " . $remote . " " . $branch);
    }

    /**
     * @param $remote
     * @return mixed
     */
    public function _fetch($remote)
    {
        return $this->__execute("cd " . $this->repository->getPath() . ";git fetch " . $remote);
    }

    /**
     * @param $remote
     * @param $branch
     * @return mixed
     */
    public function _pull($remote, $branch)
    {
        return $this->__execute("cd " . $this->repository->getPath() . ";;git pull " . $remote . " " . $branch);
    }

    /**
     * @param $command
     * @return mixed
     */
    private function __execute($command)
    {
        // Bit of a hack (understatement), but it works
        $r=exec(
            $c="ssh-keyscan -H github.com >> ~/.ssh/known_hosts;" .
            "ssh-agent bash -c 'ssh-add " . $this->repository->getKeyPath() . ";" .
            $command . "' 2>&1",
            $out,
            $var
        );

        return $out;
    }

}