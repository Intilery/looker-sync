<?php namespace Intilery\Github;

/**
 * Class WebHook
 * @package Intilery\Github
 */
class WebHook
{
    /**
     * @var
     */
    private $repository;

    /**
     * @var
     */
    private $response;

    /**
     * @var
     */
    private $payload;

    /**
     * WebHook constructor.
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->$repository = $repository;
        if (!$this->validateHeaders()) {
            die('Invalid headers');
        }
        $this->decodeBody();
    }

    /**
     *
     */
    private function decodeBody()
    {
        if ('application/json' == $_SERVER['HTTP_CONTENT_TYPE']) {
            $json = $this->response ?: file_get_contents('php://input');
        } elseif ('application/x-www-form-urlencoded' == $_SERVER['HTTP_CONTENT_TYPE']) {
            $json = $_POST['payload'];
        } else {
            die('Unabled to decode body');
        }

        $this->payload = json_decode($json);
    }

    /**
     *
     */
    private function validateHeaders()
    {
        if ('ping' == $_SERVER['HTTP_X_GITHUB_EVENT']) {
            echo 'pong';
            return false;
        }

        if ($this->repository->getSecret() !== NULL) {
            if (empty($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
                return false;
            }

            list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + array('', '');
            if (!in_array($algo, hash_algos(), TRUE)) {
                return false;
            }

            $this->response = file_get_contents('php://input');
            if ($hash !== hash_hmac($algo, $this->response, $this->repository->getSecret())) {
                return false;
            }
        };
    }
}
