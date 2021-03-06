<?php

abstract class Controller
{
    protected $view;

    protected $isPostRequest = false;

    public function __invoke()
    {
        try {
            if (!is_callable(array($this, $this->getParameter('action')))) {
                return $this->index();
            }
            return call_user_func(array($this, $this->getParameter('action')));
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function handleException(Exception $e)
    {
        if (System::getConfig('debug')) {
            print '<h1>' . $e->getMessage() . '</h1>';
            print '<p>File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '</p>';
            print '<h3>Trace:</h3><pre>' . $e->getTraceAsString() . '</pre>';
        }
    }

    abstract public function index();

    protected function isGetRequest()
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    protected function getParameter($parameter, $type = FILTER_SANITIZE_STRING)
    {
        if ($this->isGetRequest()) {
            return $this->getGetParameter($parameter, $type);
        } else {
            return $this->getPostParameter($parameter, $type);
        }
    }

    protected function getGetParameter($parameter, $type = FILTER_SANITIZE_STRING, $options = 0)
    {
        return filter_input(INPUT_GET, $parameter, $type, $options);
    }

    protected function getPostParameter($parameter, $type = FILTER_SANITIZE_STRING, $options = 0)
    {
        return filter_input(INPUT_POST, $parameter, $type, $options);
    }

    protected function display()
    {
        ob_start("ob_gzhandler");
        if ($this->getView()) {
            $path = dirname(__FILE__) . '/' . str_replace('_', '/', $this->getView()) . '.php';
            if (file_exists) {
                include(dirname(__FILE__) . '/' . str_replace('_', '/', $this->getView()) . '.php');
            }
        }
    }

    protected function displayMessage($msg, $success = true, $data = null)
    {
        print json_encode(array('message' => $msg, 'success' => $success, 'data' => $data));
    }

    protected function getView()
    {
        return $this->view;
    }
}
