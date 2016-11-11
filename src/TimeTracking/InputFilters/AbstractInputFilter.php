<?php

namespace TimeTracking\InputFilters;

use Traversable;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputInterface;

class AbstractInputFilter extends InputFilter
{
    /**
     * Add the given input filter
     *
     * @param array $config
     *
     * @throws \Exception
     *
     * @return InputFilter
     */
    protected function addInputFilter(array $config)
    {
        if (!array_key_exists('name', $config)) {
            throw new \Exception('The field "name" is missing in config!');
        }

        if (!is_array($config['input_filters']) || count($config['input_filters']) != 1) {
            throw new \Exception('The field "input_filters" needs to be an array with a single entry!');
        }

        reset($config['input_filters']);
        $inputFilterConfig = current($config['input_filters']);

        if (!isset($inputFilterConfig['name'])) {
            throw new \Exception('The field "name" is missing in input_filters config!');
        }

        $inputFilter = (is_object($inputFilterConfig['name']))
            ? $inputFilterConfig['name']
            : new $inputFilterConfig['name']();

        if (array_key_exists('validators', $config) || array_key_exists('filters', $config)) {
            throw new \Exception('The fields "validators" and "filters" will be ignored if input_filters config is present!');
        }

        return parent::add($inputFilter);
    }

    /**
     * Add an input to the input filter
     *
     * @param array|Traversable|InputInterface|InputFilterInterface $input
     * @param null|string                                           $name
     *
     * @return InputFilter
     */
    public function add($input, $name = null)
    {
        if (is_array($input) && array_key_exists('input_filters', $input)) {
            return $this->addInputFilter($input);
        }

        return parent::add($input, $name);
    }
}
