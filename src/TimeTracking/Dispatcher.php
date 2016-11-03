<?php

namespace TimeTracking;

use TimeTracking\Controller\AbstractController;
use TimeTracking\Validators\MethodAccessibleValidator;
use Zend\Validator\NotEmpty;
use Zend\Validator\Regex;
use Zend\Validator\StaticValidator;
use Zend\Validator\ValidatorChain;

class Dispatcher
{
    /**
     * Options for this dispatcher
     *
     * @var array
     */
    protected $options = [
        /**
         * Config for controllers
         */
        'controller_param'       => 'page',
        'controller_prefix'      => 'TimeTracking\\Controller\\',
        'controller_default'     => 'Index',
        'controller_suffix'      => 'Controller',
        'controller_error'       => 'Error',

        /**
         * Config for actions/controller methods
         */
        'action_param'           => 'action',
        'action_prefix'          => '',
        'action_default'         => 'index',
        'action_suffix'          => 'Action',

        /**
         * Error messages
         */
        'e_controller_not_found' => 'There is no controller "%s"!',
        'e_controller_invalid'   => 'The controller "%s" does not extend from AbstractController!',
        'e_action_not_found'     => 'There is no action "%s" for controller "%s"!',
        'e_action_invalid'       => 'The action "%s" is not supported by controller "%s"!',
    ];

    /** @var array */
    protected $params = [];

    /** @var array */
    protected $lastErrors = [];

    /** @var string */
    protected $controller;

    /** @var string */
    protected $action;

    /**
     * Dispatcher constructor.
     *
     * @param array $options
     * @param array $params
     */
    public function __construct(array $params, array $options = [])
    {
        $this->params = $params;
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @return array
     */
    public function getLastErrors()
    {
        return $this->lastErrors;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param bool $rawName
     *
     * @return string
     */
    public function getController($rawName = false)
    {
        $controller = $this->controller;

        if ($rawName && !empty($controller)) {
            $controller = substr($controller, strlen($this->options['controller_prefix']));
            $controller = substr($controller, 0, strlen($controller) - strlen($this->options['controller_suffix']));
        }

        return $controller;
    }

    /**
     * @param bool $rawName
     *
     * @return string
     */
    public function getAction($rawName = false)
    {
        $action = $this->action;

        if ($rawName && !empty($action)) {
            $action = substr($action, strlen($this->options['action_prefix']));
            $action = substr($action, 0, strlen($action) - strlen($this->options['action_suffix']));
        }

        return $action;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return ValidatorChain
     */
    protected function getValidatorChain()
    {
        $validatorChain = new ValidatorChain();
        $validatorChain->attach(new NotEmpty());
        $validatorChain->attach(new Regex('/^[a-zA-Z]+[a-zA-Z0-9_]+$/'));

        return $validatorChain;
    }

    /**
     * @param string       $param
     * @param string|mixed $defaultValue
     * @param string|mixed $errorValue
     * @param bool         $validate
     *
     * @return mixed
     */
    protected function extract($param, $defaultValue, $errorValue, $validate = true)
    {
        if (!isset($this->params[$param])) {
            return $defaultValue;
        }

        $value = $this->params[$param];

        if ($validate) {
            $validatorChain = $this->getValidatorChain();
            if (!$validatorChain->isValid($value)) {
                foreach ($validatorChain->getMessages() as $message) {
                    $this->lastErrors[] = $message;
                }
                return $errorValue;
            }
        }

        return $value;
    }

    /**
     * @return string
     */
    protected function extractController()
    {
        $tpl = $this->options['controller_prefix'] . '%s' . $this->options['controller_suffix'];

        $param = $this->extract($this->options['controller_param'], $this->options['controller_default'], $this->options['controller_error']);
        $controller = sprintf($tpl, $param);

        if (!class_exists($controller)) {
            $this->lastErrors[] = sprintf($this->options['e_controller_not_found'], $controller);
            $controller = sprintf($tpl, $this->options['controller_error']);
        }

        if ($controller instanceof AbstractController) {
            $this->lastErrors[] = sprintf($this->options['e_controller_not_found'], $controller);
            $controller = sprintf($tpl, $this->options['controller_error']);
        }

        return $controller;
    }

    /**
     * @param string $controller
     *
     * @return string
     */
    protected function extractAction($controller)
    {
        $tpl = $this->options['action_prefix'] . '%s' . $this->options['action_suffix'];

        $param = $this->extract($this->options['action_param'], $this->options['action_default'], $this->options['action_default']);

        $action = sprintf($tpl, $param);
        $refController = new \ReflectionClass($controller);
        if ($refController->hasMethod($action)) {
            $refMethod = $refController->getMethod($action);

            if (!StaticValidator::execute($refMethod, MethodAccessibleValidator::class)) {
                $this->lastErrors[] = sprintf($this->options['e_action_invalid'], $action, $controller);
                $action = sprintf($tpl, $this->options['action_default']);
            }
        } else {
            $this->lastErrors[] = sprintf($this->options['e_action_not_found'], $action, $controller);
            $action = sprintf($tpl, $this->options['action_default']);
        }

        return $action;
    }

    /**
     * @param array|\ReflectionParameter[] $params
     *
     * @return array
     */
    protected function extractParams(array $params)
    {
        $result = [];

        foreach ($params as $param) {
            $result[$param->getName()] = $this->extract($param->getName(), $param->getDefaultValue(), null, false);
        }

        return $result;
    }

    /**
     * Dispatcher
     *
     * @return mixed
     */
    public function dispatch()
    {
        // Determine controller and action
        $this->controller = $controller = $this->extractController();
        $this->action = $action = $this->extractAction($controller);

        $refController = new \ReflectionClass($controller);
        $refMethod = $refController->getMethod($action);

        // Extract parameters
        $params = $this->extractParams($refMethod->getParameters());

        // Invoke action
        $controllerObject = $refController->newInstanceArgs([$this]);
        return $refMethod->invokeArgs($controllerObject, $params);
    }
}
