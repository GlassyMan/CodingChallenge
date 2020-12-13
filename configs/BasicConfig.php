<?php

/**
* Concrete class for a base configuration
*
* @author   Vadim Glusmann
* @packet   CodingChallenge
*/
class BasicConfig extends AbstractConfig
{
    // content for replacement
    protected $templateVariables = [
        'Name'  => 'Your name goes here',
        'Stuff' => [
          [
            'Thing' => 'roses',
            'Desc'  => 'red'
          ],
          [
            'Thing' => 'violets',
            'Desc'  => 'blue'
          ],
          [
            'Thing' => 'you',
            'Desc'  => 'able to solve this'
          ],
          [
            'Thing' => 'we',
            'Desc'  => 'interested in you'
          ]
        ]
    ];

    // project directory for config files
    protected $configsDir = 'configs';

    // project directory for engine class files
    protected $enginesDir = 'engines';

    // project directory for template files
    protected $templatesDir = 'templates';

    // default engine class name
    protected $defaultEngine = '';

    // default template file
    protected $defaultTemplate = 'extra.tmpl';

    // assignment of the template files to the processing template engines
    protected $templateEngineBundle = [
        'template.tmpl' => 'SimpleTemplateEngine',
        'extra.tmpl'  	=> 'ExtraTemplateEngine'
    ];
};