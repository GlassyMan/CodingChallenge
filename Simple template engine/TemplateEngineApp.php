<?php

/**
* App for start a template processing
*
* @author   Vadim Glusmann
* @packet   CodingChallenge
*/
class TemplateEngineApp 
{
    // used template engine
    protected $engine = null;

    // used template text
    protected $template = null;

    // used configuration
    protected $config = null;

    /**
    * Constructor
    *
    * @param string                 $template_name    Template file name (for test)
    * @param string                 $engine_name      Engine class name  (for test)
    * @param string|AbstractConfig  $config           Configuratuon class object or name
    */
    function __construct($template_name = '', $engine_name = '', $config = '')
    {
        // set configuration
        if ($config != '' && is_a($config, 'AbstractConfig')) {
            if (is_string($config) && class_exists($config)) {
                $config = new $config;
            }
        } else {
            $config = new BasicConfig();
        }
        // if template file name was not given then default template file
        if (empty($template_name)) {
            $template_name = $config->getDefaultTemplate();
            $template_path = $config->getTemplatesDir().'\\'.$config->getDefaultTemplate();
            // reading the template file
            if (empty($template_path) || ($template = file_get_contents($template_path)) === FALSE || empty($template)) {
                throw new Exception('Error: Default template file not read');
            }
        // else if template file name was given, but is wrong, then error
        } else {
            $template_path = $config->getTemplatesDir().'\\'.$template_name;            
            // reading the template file
            if (($template = file_get_contents($template_path)) === FALSE || empty($template)) {
                throw new Exception('Error: Input template file not read');
            }
        }
        // if template engine class name was given then set it
        if (!empty($engine_name) && class_exists($engine_name)) {
            $engine = new $engine_name();
        // else default template engine class 
        } else {
            $default_engine = $config->getDefaultEngine();            
            if (!empty($default_engine) && class_exists($default_engine)) {
                $engine = new $default_engine(); 
            } else {
                // if default template engine class was not given then template engine class from a template engine bundle according to template file
                $template_engine_bundle = $config->getTemplateEngineBundle();
                if (!empty($template_engine_bundle) && isset($template_engine_bundle[$template_name]) && class_exists($template_engine_bundle[$template_name])) {
                    $engine = new $template_engine_bundle[$template_name](); 
                } else {
                    throw new Exception('Error: Engine class unknown');
                }
            }
        }
        $this->init($engine, $template, $config);
    }

    /**
    * Initialization of the template engine and template
    *
    * @param string       $template    Template text (for test)
    * @param string       $engine      Engine instance (for test)
    * @param BasicConfig  $config      Configuratuon
    */
    function init(AbstractTemplateEngine $engine, $template, BasicConfig $config) 
    {
        $this->engine = $engine;
        $this->template = (is_string($template)) ? $template : '';
        $this->config = $config;
    }

    /**
    * Start of the template engine
    *
    * @return string    Processed template text
    */
    function build() 
    {
        $this->engine->build($this->template, $this->config);
        return $this->engine->getBuildedTemplate();
    }
}
