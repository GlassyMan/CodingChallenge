<?php

/**
* Start script for processing of templates
*
* @author   Vadim Glusmann
* @packet   CodingChallenge
*/
include_once 'autoload.php';

// initial values
$init = [];
$config = new BasicConfig();

echo "\n----------\n";
echo "\nUsage: \n".
     "  ...>php index.php [name=<...>] [template=<Index>] [engine=<Index>] \n";
echo "\n----------\n";
echo "\nFolgende Templates und Engines sind vorhanden: \n\n".
     "  <Index>. Template:\t <Index>. Engine \n";

// lists of all available template files and engines
$templates_index = array_flip(array_keys($config->getTemplateEngineBundle()));
$engines_index   = array_flip(array_values($config->getTemplateEngineBundle()));

// add the default template files to list
if (!empty($config->getDefaultTemplate()) && !isset($templates_index[$config->getDefaultTemplate()])) {
    $templates_index[$config->getDefaultTemplate()] = count($templates_index);
}
// add the default template engines to list
if (!empty($config->getDefaultEngine()) && !isset($engines_index[$config->getDefaultEngine()])) {
    $engines_index[$config->getDefaultEngine()] = count($engines_index);
}

$index_lenght = count($templates_index) > count($engines_index) ? count($templates_index) : count($engines_index);

// for display
$templates_index = array_flip($templates_index);
$engines_index   = array_flip($engines_index);


// view of all available template files and engines
for ($i = 0; $i < $index_lenght; $i++) {
    echo '  '.(isset($templates_index[$i]) ? ($i + 1).'. '.$templates_index[$i].':' : "-------\t")."\t".
              (isset($engines_index[$i])   ? ($i + 1).'. '.$engines_index[$i] : '-------')."\n";
}

echo "\nDefault Template: \t".$config->getDefaultTemplate();
echo "\nDefault Engine: \t".$config->getDefaultEngine()."\n";

// store of the input to the configuration
if ($argv > 1) {
    for ($i = 1; $i < $argc; $i++) {
        if (strpos($argv[$i], '=')) {
            list($name, $value) = explode('=', $argv[$i]);
            if ($name == 'name') {
                $init['name'] = $value;
            } elseif ($name == 'engine' && isset($engines_index[--$value])) {
                $init['default_engine'] = $engines_index[$value];
            } elseif ($name == 'template' && isset($templates_index[--$value])) {
                $init['default_template'] = $templates_index[$value];
            }
        }
    }
    // set initial values
    if (count($config)) {
        $config->init($init);
    }
}

// template and engine what are used for display
$used_template = $config->getDefaultTemplate();
$used_engine   = $config->getDefaultEngine();
// if default template or default engine is missing in configuration
if ($used_template == '' || $used_engine == '') {
    // search in the bundle
    $template_engine_bundle = $config->getTemplateEngineBundle();
    if (!empty($template_engine_bundle)) {
        if ($used_template != '' && $used_engine == '' && isset($template_engine_bundle[$used_template])) {
            $used_engine = $template_engine_bundle[$used_template];
        } elseif ($used_engine != '' && $used_template == '') {
            $used_template = array_search($used_engine, $template_engine_bundle);
            if ($used_template === FALSE) {
                $used_template = '';
            }
        }
    }
}

echo "\nVerwendetes Template: \t".$used_template;
echo "\nVerwendetes Engine: \t".$used_engine."\n";
echo "\n----------\n\nTemplate:\n\n";

// processing of templates
try {
    $app = new TemplateEngineApp('', '', $config);
    echo $app->build();
} catch (Exception $e) {
    echo $e->getMessage();
}
echo "\n\n----------\n";