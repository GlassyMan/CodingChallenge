<?php

/**
* Abstract class for a general configuration
*
* @author   Vadim Glusmann
* @packet   CodingChallenge
*/
class AbstractConfig
{
    // content for replacement
    protected $template_variables = [];

    // project directory for config files
    protected $configs_dir = '';

    // project directory for engine class files
    protected $engines_dir = '';

    // project directory for template files
    protected $templates_dir = '';

    // default engine class name
    protected $default_engine = '';

    // default template file
    protected $default_template = '';

    // assignment of the template files to the processing template engines
    protected $template_engine_bundle = [];

    /**
    * change of content for replacement
    *
    * @param array  $params     New content for replacement
    */
    function init($params) {
        if (isset($params['name']) && is_string($params['name'])) {
            $this->template_variables['Name'] = $params['name']; 
        }
        if (isset($params['stuff']) && is_array($params['stuff'])) {
            $this->template_variables['Stuff'] = $params['stuff']; 
        }
        if (isset($params['configs_dir']) && is_string($params['configs_dir'])) {
            $this->configs_dir = $params['configs_dir']; 
        }
        if (isset($params['engines_dir']) && is_string($params['engines_dir'])) {
            $this->engines_dir = $params['engines_dir']; 
        }
        if (isset($params['templates_dir']) && is_string($params['templates_dir'])) {
            $this->templates_dir = $params['templates_dir']; 
        }
        if (isset($params['default_engine']) && is_string($params['default_engine'])) {
            $this->default_engine = $params['default_engine']; 
        }
        if (isset($params['default_template']) && is_string($params['default_template'])) {
            $this->default_template = $params['default_template']; 
        }
        if (isset($params['template_engine_bundle']) && is_string($params['template_engine_bundle'])) {
            $this->template_engine_bundle = $params['template_engine_bundle']; 
        }
    }

    /**
    * Get content for replacement
    * 
    * @return array     content
    */
    function getTemplateVariables() {
        return $this->template_variables;
    }

    /**
    * Get project directory name for config files
    * 
    * @return string
    */
    function getConfigsDir() {
        return $this->configs_dir;
    }

    /**
    * Get project directory name for engine class files
    * 
    * @return string
    */
    function getEnginesDir() {
        return $this->engines_dir;
    }

    /**
    * Get project directory name for template files
    * 
    * @return string
    */
    function getTemplatesDir() {
        return $this->templates_dir;
    }

    /**
    * Get default engine class name
    * 
    * @return string
    */
    function getDefaultEngine() {
        return $this->default_engine;
    }

    /**
    * Get default template file name
    * 
    * @return string
    */
    function getDefaultTemplate() {
        return $this->default_template;
    }

    /**
    * Get assignment of the template files to the processing template engines
    * 
    * @return array
    */
    function getTemplateEngineBundle() {
        return $this->template_engine_bundle;
    }
};