<?php

namespace TimeTracking;

use TimeTracking\Controller\AbstractController;
use TimeTracking\Core\ReflectionObject;
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
        'e_param_missing'        => 'The parameter "%s" is required and missing!',
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
     * @return ReflectionObject
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

        try {
            $controllerInstance = new $controller($this);
            if (!$controllerInstance instanceof AbstractController) {
                throw new \Exception(sprintf($this->options['e_controller_invalid'], $controller));
            }
        } catch (\Exception $error) {
            $this->lastErrors[] = $error->getMessage();
            $controller = sprintf($tpl, $this->options['controller_error']);
            $controllerInstance = new $controller($this);
        }

        $this->controller = $controller;
        return new ReflectionObject($controllerInstance);
    }

    /**
     * @param ReflectionObject $refController
     *
     * @return \ReflectionMethod
     */
    protected function extractAction(ReflectionObject $refController)
    {
        $tpl = $this->options['action_prefix'] . '%s' . $this->options['action_suffix'];

        $param = $this->extract($this->options['action_param'], $this->options['action_default'], $this->options['action_default']);

        $action = sprintf($tpl, $param);
        if ($refController->hasMethod($action)) {
            $refMethod = $refController->getMethod($action);

            if (!StaticValidator::execute($refMethod, MethodAccessibleValidator::class)) {
                $this->lastErrors[] = sprintf($this->options['e_action_invalid'], $action, $this->controller);
                $action = sprintf($tpl, $this->options['action_default']);
                $refMethod = $refController->getMethod($action);
            }

            $this->action = $action;
            return $refMethod;
        }

        $this->lastErrors[] = sprintf($this->options['e_action_not_found'], $action, $this->controller);
        $action = sprintf($tpl, $this->options['action_default']);
        $refMethod = $refController->getMethod($action);

        $this->action = $action;
        return $refMethod;
    }

    /**
     * @param array|\ReflectionParameter[] $params
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function extractParams(array $params)
    {
        $result = [];
        $undefinedValue = new \stdClass();

        foreach ($params as $param) {
            $defaultValue = $undefinedValue;
            if ($param->isDefaultValueAvailable()) {
                $defaultValue = $param->getDefaultValue();
            }

            $result[$param->getName()] = $paramResult = $this->extract($param->getName(), $defaultValue, null, false);

            // Check for undefined params
            if ($paramResult === $undefinedValue) {
                $message = sprintf($this->options['e_param_missing'], $param->getName());
                $this->lastErrors[] = $message;
                throw new \Exception($message);
            }
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
        $refController = $this->extractController();
        $refAction = $this->extractAction($refController);

        // Extract parameters
        $params = $this->extractParams($refAction->getParameters());

        // Invoke action
        return $refAction->invokeArgs($refController->getObject(), $params);
    }
}
