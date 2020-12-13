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
    protected $templateVariables = [];

    // project directory for config files
    protected $configsDir = '';

    // project directory for engine class files
    protected $enginesDir = '';

    // project directory for template files
    protected $templatesDir = '';

    // default engine class name
    protected $defaultEngine = '';

    // default template file
    protected $defaultTemplate = '';

    // assignment of the template files to the processing template engines
    protected $templateEngineBundle = [];

    /**
    * change of content for replacement
    *
    * @param array  $params     New content for replacement
    */
    function init($params) {
        if (isset($params['name']) && is_string($params['name'])) {
            $this->templateVariables['Name'] = $params['name']; 
        }
        if (isset($params['stuff']) && is_array($params['stuff'])) {
            $this->templateVariables['Stuff'] = $params['stuff']; 
        }
        if (isset($params['configsDir']) && is_string($params['configsDir'])) {
            $this->configsDir = $params['configsDir']; 
        }
        if (isset($params['enginesDir']) && is_string($params['enginesDir'])) {
            $this->enginesDir = $params['enginesDir']; 
        }
        if (isset($params['templatesDir']) && is_string($params['templatesDir'])) {
            $this->templatesDir = $params['templatesDir']; 
        }
        if (isset($params['defaultEngine']) && is_string($params['defaultEngine'])) {
            $this->defaultEngine = $params['defaultEngine']; 
        }
        if (isset($params['defaultTemplate']) && is_string($params['defaultTemplate'])) {
            $this->defaultTemplate = $params['defaultTemplate']; 
        }
        if (isset($params['templateEngineBundle']) && is_string($params['templateEngineBundle'])) {
            $this->templateEngineBundle = $params['templateEngineBundle']; 
        }
    }

    /**
    * Get content for replacement
    * 
    * @return array     content
    */
    function getTemplateVariables() : array {
        return $this->templateVariables;
    }

    /**
    * Get project directory name for config files
    * 
    * @return string	config directory
    */
    function getConfigsDir() : string {
        return $this->configsDir;
    }

    /**
    * Get project directory name for engine class files
    * 
    * @return string	engines directory
    */
    function getEnginesDir() : string {
        return $this->enginesDir;
    }

    /**
    * Get project directory name for template files
    * 
    * @return string	templates directory
    */
    function getTemplatesDir() : string {
        return $this->templatesDir;
    }

    /**
    * Get default engine class name
    * 
    * @return string	default engine
    */
    function getDefaultEngine() : string {
        return $this->defaultEngine;
    }

    /**
    * Get default template file name
    * 
    * @return string	default template
    */
    function getDefaultTemplate() : string {
        return $this->defaultTemplate;
    }

    /**
    * Get assignment of the template files to the processing template engines
    * 
    * @return array		templates engines pairs
    */
    function getTemplateEngineBundle() : array {
        return $this->templateEngineBundle;
    }
};