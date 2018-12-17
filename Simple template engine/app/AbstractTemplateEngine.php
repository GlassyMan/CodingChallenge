<?php

/**
* Abstract class for a template processing
*
* @author   Vadim Glusmann
* @packet   CodingChallenge
*/
abstract class AbstractTemplateEngine 
{
    // start characters for control tag
    protected $tag_start_sign = '';

    // masked start characters for control tag
    protected $tag_start_sign_mask = '';

    // end characters for control tag
    protected $tag_end_sign = '';

    // masked end characters for control tag
    protected $tag_end_sign_mask = '';

    // characters for a first spezial control tag (in block)
    protected $block_start_sign = '';

    // characters for a end spezial control tag (in block)
    protected $block_end_sign = '';

    // processed template text
    protected $builded_template = '';

    /**
    * Replacement in the template
    *
    * @param string          $template   Template text
    * @param AbstractConfig  $config     Configuratuon
    */
    abstract function build($template, AbstractConfig $config);

    /**
    * Return of the processed template text
    *
    * @return string    Processed template text
    */
    function getBuildedTemplate() {
        return $this->builded_template;
    }
}