<?php

/**
* Processing of extra templates
*
* @author   Vadim Glusmann
* @packet   CodingChallenge
*/
class ExtraTemplateEngine extends SimpleTemplateEngine 
{
    
    /**
    * Replacement for 'unless' tag
    *
    * @param string $tag_options        Tag options, for example Stuff
    * @param string $tag_content        Content in the tag block
    * @param array  $template_variables Content for replacement
    * @param int    $index              Index of the current record of $template_variables  (necessary when editing the array)
    * @param int    $lenght             Lenght of the all record of $template_variables     (necessary when editing the array)
    */
    protected function unlessTag($tag_options, $tag_content, $template_variables, $index, $lenght) 
    {
        $lenght = $lenght ? $lenght - 1 : 0;
        $conditions = $this->CaseConditions($tag_content);

        if ($tag_options === '@last') {
            $this->builded_template .= ($index != $lenght) ? $conditions['then'] : $conditions['else'];
        }
    }

    /**
    * Find the conditions for case discrimination
    *
    * @param string $content    Content in the tag block
    * 
    * @return array             Case conditions, 'then' for TRUE, 'else' for FALSE
    */
    protected function CaseConditions($content) 
    {
        if (is_string($content) && strlen($content)) {
            // case discrimination by spezial tag 'else', for example {{else}}
            if (($else_pos = strpos($content, $this->tag_start_sign.'else'.$this->tag_end_sign)) !== FALSE) {
                return [
                    'then' => substr($content, 0, $else_pos),
                    'else' => substr($content, $else_pos + 8)
                ];
            }

            return [
                'then' => $content,
                'else' => ''
            ];
        }
        throw new Exception('Error: Description for Case Conditions missing');
    }
}