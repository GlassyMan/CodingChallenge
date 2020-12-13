O<?php

/**
* Abstract class for a template processing
*
* @author   Vadim Glusmann
* @packet   CodingChallenge
*/
abstract class AbstractTemplateEngine 
{
    // start characters for control tag
    protected $tagStartSign = '';

    // masked start characters for control tag
    protected $tagStartSignMask = '';

    // end characters for control tag
    protected $tagEndSign = '';

    // masked end characters for control tag
    protected $tagEndSignMask = '';

    // characters for a first specifically control tag (in block)
    protected $blockStartSign = '';

    // characters for a end specifically control tag (in block)
    protected $blockEndSign = '';

    // processed template text
    protected $buildedTemplate = '';

    /**
    * Replacement in the template
    *
    * @param string          $template   Template text
    * @param AbstractConfig  $config     Configuratuon
    */
    abstract function build(string $template, AbstractConfig $config);

    /**
    * Return of the processed template text
    *
    * @return string    Processed template text
    */
    function getBuildedTemplate() : string {
        return $this->buildedTemplate;
    }
}