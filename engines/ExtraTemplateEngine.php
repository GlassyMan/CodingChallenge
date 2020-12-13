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
    * @param string $tagOptions        Tag options, for example Stuff
    * @param string $tagContent        Content in the tag block
    * @param array  $templateVariables Content for replacement
    * @param int    $index             Index of the current record of $templateVariables  (necessary when editing the array)
    * @param int    $length            Length of the all record of $templateVariables     (necessary when editing the array)
    */
    protected function unlessTag(string $tagOptions, string $tagContent, array $templateVariables, int $index, int $length) 
    {
		// index starts with 0, length with 1
        $length = $length ? $length - 1 : 0;
        $conditions = $this->CaseConditions($tagContent);
        if ($tagOptions === '@last') {
            $this->buildedTemplate .= ($index != $length) ? $conditions['then'] : $conditions['else'];
        }
    }

    /**
    * Find the conditions for case discrimination
    *
    * @param string $content    Content in the tag block
    * 
    * @return array             Case conditions, 'then' for TRUE, 'else' for FALSE
    */
    protected function CaseConditions(string $content) : array
    {
        if (is_string($content) && strlen($content)) {
            // case discrimination by specifically tag 'else', for example {{else}}
            if (($elsePos = strpos($content, $this->tagStartSign.'else'.$this->tagEndSign)) !== FALSE) {
                return [
                    'then' => substr($content, 0, $elsePos),
                    'else' => substr($content, $elsePos + 8)
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