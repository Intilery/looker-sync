<?php namespace Intilery\Looker;

use Intilery\Github\Repository;
use Intilery\Github\Manager;
use Intilery\Github\Shell;
use Intilery\Github\WebHook;

/**
 * Class Init
 * @package Intilery\Looker
 */
class Init
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var
     */
    private $master;

    /**
     * @var
     */
    private $children;

    /**
     * @var
     */
    private $shell;

    /**
     * Init constructor.
     */
    public function __construct()
    {
        $this->shell = new Shell();
        $this->loadConfig();
        $this->loadMaster();
        $this->process();
    }

    /**
     *
     */
    public function loadMaster()
    {
        // Move to a config file
        $this->repository = new Repository(
            $this->master->username,
            $this->master->project,
            $this->master->keypath,
            $this->master->repopath,
            $this->master->webhooksecret
        );

        $this->manager = new Manager(
            $this->repository
        );


        // Verify the WebHook request was real
        //new WebHook($this->repository);
    }

    /**
     *
     */
    public function process()
    {
        if (!$this->shell->exists($this->repository->getPath())) {
            $this->manager->_clone();
        } else {
            $this->manager->_reset();
            $this->manager->_pull();
        }

        foreach ($this->children as $repo) {
            $repository = new Repository(
                $repo->username,
                $repo->project,
                $repo->keypath,
                $repo->repopath,
                null,
                $repo->replaces
            );

            $manager = new Manager($repository);

            if (!$this->shell->exists($repository->getPath())) {
                $manager->_clone();
            } else {
                $manager->_reset();
                $manager->_pull();
            }

            $this->shell->flatCopy($this->repository->getPath(), $repository->getPath());
            if (!empty($repository->getReplaces())) {
                $this->shell->findReplace($repository->getPath(), $repository->getReplaces());
            }
            $manager->_add();
            $manager->_commit("Auto sync from base");
            $manager->_push("origin", "master");
            //$this->shell->delete($repository->getPath());
        }

        //$this->shell->delete($this->repository->getPath());
    }

    /**
     * @return Manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     *
     */
    private function loadConfig()
    {
        $config = json_decode(file_get_contents(__DIR__ . '/../../sync.json'));
        $this->master = $config->master;
        $this->children = $config->children;
    }
}
