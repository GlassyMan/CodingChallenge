<?php

/**
* Processing of simple templates
*
* @author   Vadim Glusmann
* @packet   CodingChallenge
*/
class SimpleTemplateEngine extends AbstractTemplateEngine 
{
    // start characters for control tag
    protected $tagStartSign = '{{';

    // masked start characters for control tag
    protected $tagStartSignMask = '\{\{';

    // end characters for control tag
    protected $tagEndSign = '}}';

    // masked end characters for control tag
    protected $tagEndSignMask = '\}\}';

    // characters for a first specifically control tag (in block)
    protected $blockStartSign = '#';

    // characters for a end specifically control tag (in block)
    protected $blockEndSign = '/';
    
    /**
    * Replacement in the template
    *
    * @param string         $template   Template text
    * @param AbstractConfig $config     Configuratuon
    */
    function build(string $template, AbstractConfig $config) 
    {
        $this->work($template, $config->getTemplateVariables());
    }

    /**
    * Parsing the template text
    *
    * @param string $template           Template text
    * @param array  $templateVariables 	Content for replacement in the template text
    * @param int    $index              Index of the current record of $templateVariables  (necessary when editing the array)
    * @param int    $length             Length of the all record of $templateVariables     (necessary when editing the array)
    */
    protected function work(string $template, array $templateVariables, int $index = 0, int $length = 0) 
    {
        // limit for the loop
        $wordsNumber = str_word_count($template);
        $i = 0;

        while ($i < $wordsNumber) {
            // template is not void
            if (strlen($template) > 3) {
                $matches = [];
                // search patterns to find the control tags, for example '/([^\{]*)\{\{([^\}]*)\}\}/'
                $pattern = '/([^'.$this->tagStartSignMask.']*)'.$this->tagStartSignMask.'([^'.$this->tagEndSignMask.']*)'.$this->tagEndSignMask.'/';
                preg_match($pattern, $template, $matches, PREG_OFFSET_CAPTURE);
                // save the text before the control tag
                $this->buildedTemplate .= $matches[1][0];
                // set the text after the control tag
                $template = substr($template, $matches[2][1] + strlen($matches[2][0]) + strlen($this->tagEndSign));

                // control tag contains no special characters, then direct substitution
                if (ctype_alnum($matches[2][0][0])) {
                    $this->replaceTag($matches[2][0], $templateVariables);
                // control tag contains special characters at the beginning of a block with content, then special processing for every tag
                } elseif ($matches[2][0][0] == $this->blockStartSign) {
                    $tagOptions = '';
                    // find control tag self
                    $replaceTag = substr($matches[2][0], strlen($this->blockStartSign), strlen($matches[2][0]) - 1);

                    // find options in control tag, for example by {{#each Stuff}} or {{#unless @last}} are options Stuff or @last
                    if (($optionsPos = strpos($replaceTag, ' ')) !== FALSE) {
                        $tagOptions = substr($replaceTag, $optionsPos + 1);
                        $replaceTag = substr($replaceTag, 0, $optionsPos);
                    }
                    // method name for method for processing each control tag
                    $funcName = "${replaceTag}Tag";
                    if (!method_exists($this, $funcName)) {
                        throw new Exception('Error: Processing of tag '.$this->tagStartSign.$replaceTag.$this->tagEndSign.' impossible');
                    }

                    // find the content in the block by trimming the final control tag, if there is, else template text self
                    if (($contentPos = strpos($template, $this->tagStartSign.$this->blockEndSign.$replaceTag.$this->tagEndSign)) !== FALSE) {
                        $tag_content = substr($template, 0, $contentPos);
                    } else {
                        $tag_content = $template;
                    }

                    // replacement in block of the tag
                    $this->$funcName($tagOptions, $tag_content, $templateVariables, $index, $length);
                    // trimming the final control tag
                    $template = substr($template, strlen($tag_content) + strlen($replaceTag) + strlen($this->tagStartSign) + strlen($this->tagEndSign) + strlen($this->blockEndSign));
                }
            }
            $i++;
        }
    }

    /**
    * Direct substitution
    *
    * @param string $replaceTag        Tag that needs to be replaced
    * @param array  $templateVariables 	Content for replacement
    */
    protected function replaceTag(string $replaceTag, array $templateVariables) 
    {
        $this->buildedTemplate .= isset($templateVariables[$replaceTag]) ? $templateVariables[$replaceTag] : $replaceTag;        
    }

    /**
    * Replacement for 'each' tag
    *
    * @param string $tagOptions        Tag options, for example Stuff
    * @param string $tagContent        Content in the tag block
    * @param array  $templateVariables Content for replacement
    */
    protected function eachTag(string $tagOptions, string $tagContent, array $templateVariables) 
    {
        if (isset($templateVariables[$tagOptions])) {
            $templateVariables = $templateVariables[$tagOptions];
            $length = count($templateVariables);

            foreach ($templateVariables as $index => $stuff) {
                $this->work($tagContent, $stuff, $index, $length);
            }
        } else {
            $this->buildedTemplate .= "[$tagOptions]";
        }
    }
}
