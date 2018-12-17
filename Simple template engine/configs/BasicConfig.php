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
    protected $template_variables = [
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
    protected $configs_dir = 'configs';

    // project directory for engine class files
    protected $engines_dir = 'engines';

    // project directory for template files
    protected $templates_dir = 'templates';

    // default engine class name
    protected $default_engine = '';

    // default template file
    protected $default_template = 'extra.tmpl';

    // assignment of the template files to the processing template engines
    protected $template_engine_bundle = [
        'template.tmpl' => 'SimpleTemplateEngine',
        'extra.tmpl'  => 'ExtraTemplateEngine'
    ];
};