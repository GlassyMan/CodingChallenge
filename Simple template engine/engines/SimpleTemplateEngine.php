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
    protected $tag_start_sign = '{{';

    // masked start characters for control tag
    protected $tag_start_sign_mask = '\{\{';

    // end characters for control tag
    protected $tag_end_sign = '}}';

    // masked end characters for control tag
    protected $tag_end_sign_mask = '\}\}';

    // characters for a first spezial control tag (in block)
    protected $block_start_sign = '#';

    // characters for a end spezial control tag (in block)
    protected $block_end_sign = '/';
    
    /**
    * Replacement in the template
    *
    * @param string         $template   Template text
    * @param AbstractConfig $config     Configuratuon
    */
    function build($template, AbstractConfig $config) 
    {
        $this->work($template, $config->getTemplateVariables());
    }

    /**
    * Parsing the template text
    *
    * @param string $template           Template text
    * @param array  $template_variables Content for replacement in the template text
    * @param int    $index              Index of the current record of $template_variables  (necessary when editing the array)
    * @param int    $lenght             Lenght of the all record of $template_variables     (necessary when editing the array)
    */
    protected function work($template, $template_variables, $index = 0, $lenght = 0) 
    {
        // limit for the loop
        $words_number = str_word_count($template);
        $i = 0;

        while ($i < $words_number) {
            // template is not void
            if (strlen($template) > 3) {
                $matches = [];
                // search patterns to find the control tags, for example '/([^\{]*)\{\{([^\}]*)\}\}/'
                $pattern = '/([^'.$this->tag_start_sign_mask.']*)'.$this->tag_start_sign_mask.'([^'.$this->tag_end_sign_mask.']*)'.$this->tag_end_sign_mask.'/';
                preg_match($pattern, $template, $matches, PREG_OFFSET_CAPTURE);
                // save the text before the control tag
                $this->builded_template .= $matches[1][0];
                // set the text after the control tag
                $template = substr($template, $matches[2][1] + strlen($matches[2][0]) + strlen($this->tag_end_sign));

                // control tag contains no special characters, then direct substitution
                if (ctype_alnum($matches[2][0][0])) {
                    $this->replaceTag($matches[2][0], $template_variables);
                // control tag contains special characters at the beginning of a block with content, then special processing for every tag
                } elseif ($matches[2][0][0] == $this->block_start_sign) {
                    $tag_options = '';
                    // find control tag self
                    $replace_tag = substr($matches[2][0], strlen($this->block_start_sign), strlen($matches[2][0]) - 1);

                    // find options in control tag, for example by {{#each Stuff}} or {{#unless @last}} are options Stuff or @last
                    if (($options_pos = strpos($replace_tag, ' ')) !== FALSE) {
                        $tag_options = substr($replace_tag, $options_pos + 1);
                        $replace_tag = substr($replace_tag, 0, $options_pos);
                    }
                    // method name for method for processing each control tag
                    $func_name = "${replace_tag}Tag";
                    if (!method_exists($this, $func_name)) {
                        throw new Exception('Error: Processing of tag '.$this->tag_start_sign.$replace_tag.$this->tag_end_sign.' impossible');
                    }

                    // find the content in the block by trimming the final control tag, if there is, else template text self
                    if (($content_pos = strpos($template, $this->tag_start_sign.$this->block_end_sign.$replace_tag.$this->tag_end_sign)) !== FALSE) {
                        $tag_content = substr($template, 0, $content_pos);
                    } else {
                        $tag_content = $template;
                    }

                    // replacement in block of the tag
                    $this->$func_name($tag_options, $tag_content, $template_variables, $index, $lenght);
                    // trimming the final control tag
                    $template = substr($template, strlen($tag_content) + strlen($replace_tag) + strlen($this->tag_start_sign) + strlen($this->tag_end_sign) + strlen($this->block_end_sign));
                }
            }
            $i++;
        }
    }

    /**
    * Direct substitution
    *
    * @param string $replace_tag        Tag that needs to be replaced
    * @param array  $template_variables Content for replacement
    */
    protected function replaceTag($replace_tag, $template_variables) 
    {
        $this->builded_template .= isset($template_variables[$replace_tag]) ? $template_variables[$replace_tag] : $replace_tag;        
    }

    /**
    * Replacement for 'each' tag
    *
    * @param string $tag_options        Tag options, for example Stuff
    * @param string $tag_content        Content in the tag block
    * @param array  $template_variables Content for replacement
    */
    protected function eachTag($tag_options, $tag_content, $template_variables) 
    {
        if (isset($template_variables[$tag_options])) {
            $template_variables = $template_variables[$tag_options];
            $lenght = count($template_variables);

            foreach ($template_variables as $index => $stuff) {
                $this->work($tag_content, $stuff, $index, $lenght);
            }
        } else {
            $this->builded_template .= "[$tag_options]";
        }
    }
}
