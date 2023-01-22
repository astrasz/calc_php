<?php

declare(strict_types=1);

namespace App\Controller;

use Error;
use Exception;

abstract class AbstractController
{

    protected const  DEFAULT_METHOD = 'getCalculator';
    protected const NEW_LOAN_METHOD = 'new';

    protected array $server;
    protected array $post;
    protected array $get;

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->post = $_POST;
        $this->get = $_GET;
    }

    final public function execute(): void
    {
        try {
            $method = str_replace('/', '', $this->server['REQUEST_URI']);
            if ($paramsPos = strpos($method, '?')) {
                $method = substr($method, 0, $paramsPos);
            }

            if (!$method || !method_exists($this, $method)) {
                $method = Self::DEFAULT_METHOD;
            }
            $this->$method();
        } catch (Error $e) {
            $this->redirect();
        }
    }

    final public function redirect(string $destination = '/', array $params = []): void
    {

        $query = [];
        if (count($params)) {

            foreach ($params as $key => $value) {
                $query[] = urlencode($key) . '=' . $value;
            }
            $params = implode('&', $query);
            $destination .= '?' . $params;
        }

        header("Location: $destination");
        exit;
    }
}
