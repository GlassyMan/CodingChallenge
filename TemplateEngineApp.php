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
    * @param string                 $templateName    Template file name (for test)
    * @param string					$engineName      Engine class name  (for test)
    * @param AbstractConfig  		$config          Configuratuon class object or name
    */
    function __construct(string $templateName = '', string $engineName = '', AbstractConfig $config = null)
    {
        // set configuration
        if (is_null($config)) {
            $config = new BasicConfig();
        }
        // if template file name was not given then default template file
        if (empty($templateName)) {
            $templateName = $config->getDefaultTemplate();
            $templatePath = $config->getTemplatesDir().'\\'.$config->getDefaultTemplate();
            // reading the template file
            if (empty($templatePath) || ($template = file_get_contents($templatePath)) === FALSE || empty($template)) {
                throw new Exception('Error: Default template file not read');
            }
        // else if template file name was given, but is wrong, then error
        } else {
            $templatePath = $config->getTemplatesDir().'\\'.$templateName;            
            // reading the template file
            if (($template = file_get_contents($templatePath)) === FALSE || empty($template)) {
                throw new Exception('Error: Input template file not read');
            }
        }
        // if template engine class name was given then set it
        if (!empty($engineName) && class_exists($engineName)) {
            $engine = new $engineName();
        // else default template engine class 
        } else {
            $defaultEngine = $config->getDefaultEngine();            
            if (!empty($defaultEngine) && class_exists($defaultEngine)) {
                $engine = new $defaultEngine(); 
            } else {
                // if default template engine class was not given then template engine class from a template engine bundle according to template file
                $templateEngineBundle = $config->getTemplateEngineBundle();
                if (!empty($templateEngineBundle) && isset($templateEngineBundle[$templateName]) && class_exists($templateEngineBundle[$templateName])) {
                    $engine = new $templateEngineBundle[$templateName](); 
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
    * @param AbstractTemplateEngine	$engine      Engine instance (for test)
    * @param string       			$template    Template text (for test)
    * @param AbstractConfig 		$config      Configuratuon
    */
    function init(AbstractTemplateEngine $engine, string $template, AbstractConfig $config) 
    {
        $this->engine 	= $engine;
        $this->template = $template;
        $this->config 	= $config;
    }

    /**
    * Start of the template engine
    *
    * @return string    Processed template text
    */
    function build() : string {
        $this->engine->build($this->template, $this->config);
        return $this->engine->getBuildedTemplate();
    }
}
