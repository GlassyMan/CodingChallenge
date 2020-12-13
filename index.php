<?php

/**
* Start script for processing of templates
*
* @author   Vadim Glusmann
* @packet   CodingChallenge
*/
include_once 'autoload.php';

// initial values
$config = new BasicConfig();

displayUsage();

// lists of all available template files and engines as list[name]=index
$templatesList = array_flip(array_keys($config->getTemplateEngineBundle()));
$enginesList   = array_flip(array_values($config->getTemplateEngineBundle()));

// add the default template files to list if defined
setDefaultValue($config->getDefaultTemplate(), $templatesList);
// add the default template engines to list if defined
setDefaultValue($config->getDefaultEngine(), $enginesList);

// lists of all available template files and engines as list[index]=name
$templatesList = array_flip($templatesList);
$enginesList   = array_flip($enginesList);

// display all templates and engines
displayListAvailableTemplatesEngines($templatesList, $enginesList, $config);

echo "\nDefault Template: \t".$config->getDefaultTemplate();
echo "\nDefault Engine: \t".$config->getDefaultEngine()."\n";

// set passed template and engine to config
initPassedTemplateEngine($argc, $argv, $templatesList, $enginesList, $config);

// set a template and engine what are used for processing
$used = getUsedTemplateEngine($config);

echo "\nVerwendetes Template: \t".$used['template'];
echo "\nVerwendetes Engine: \t".$used['engine']."\n";

echo "\n----------\n\nTemplate:\n\n";

// processing of templates
try {
    $app = new TemplateEngineApp('', '', $config);
    echo $app->build();
} catch (Exception $e) {
    echo $e->getMessage();
}
echo "\n\n----------\n";

function displayUsage() {
	echo "\n----------\n";
	echo "\nUsage: \n".
		 "  ...>php index.php [name=<...>] [template=<Index>] [engine=<Index>] \n";
	echo "\n----------\n";
	echo "\nFolgende Templates und Engines sind vorhanden: \n\n".
		 "  <Index>. Template:\t <Index>. Engine \n";
}

// display list of all available template files and engines
function displayListAvailableTemplatesEngines(array $templatesList, array $enginesList, AbstractConfig $config) {
	// all available template files and engines
	$listLength = count($templatesList) > count($enginesList) ? count($templatesList) : count($enginesList);
	for ($i = 0; $i < $listLength; $i++) {
		echo '  '.(isset($templatesList[$i]) ? ($i + 1).'. '.$templatesList[$i].':' : "-------\t")."\t".
				  (isset($enginesList[$i])   ? ($i + 1).'. '.$enginesList[$i] : '-------')."\n";
	}
}

// init passed template and engine
function initPassedTemplateEngine(int $argc, array $argv, array $templatesList, array $enginesList, AbstractConfig $config) {
	// set of the console input to the configuration
	if ($argv > 1) {
		$init = [];
		for ($i = 1; $i < $argc; $i++) {
			if (strpos($argv[$i], '=')) {
				list($name, $value) = explode('=', $argv[$i]);
				if ($name == 'name') {
					$init['name'] = $value;
				} elseif ($name == 'engine' && isset($enginesList[--$value])) {
					$init['defaultEngine'] = $enginesList[$value];
				} elseif ($name == 'template' && isset($templatesList[--$value])) {
					$init['defaultTemplate'] = $templatesList[$value];
				}
			}
		}
		// set initial values
		if (count($init)) {
			$config->init($init);
		}
	}
}

// get a template and engine what are used for display
function getUsedTemplateEngine(AbstractConfig $config) : array {
	// template and engine what are used for display
	$usedTemplate = $config->getDefaultTemplate();
	$usedEngine   = $config->getDefaultEngine();

	// if default template or default engine is missing in configuration
	if ($usedTemplate == '' || $usedEngine == '') {
		// search in the bundle
		$templateEngineBundle = $config->getTemplateEngineBundle();
		if (!empty($templateEngineBundle)) {
			if ($usedTemplate != '' && $usedEngine == '' && isset($templateEngineBundle[$usedTemplate])) {
				$usedEngine = $templateEngineBundle[$usedTemplate];
			} elseif ($usedEngine != '' && $usedTemplate == '') {
				$usedTemplate = array_search($usedEngine, $templateEngineBundle);
				if ($usedTemplate === FALSE) {
					$usedTemplate = '';
				}
			}
		}
	}
	return ['template' => $usedTemplate, 'engine' => $usedEngine];
}

// add the default value to templates / engines list
function setDefaultValue(string $defaultValue, array $valuesList) {
	if (!empty($defaultValue) && !isset($valuesList[$defaultValue])) {
		$valuesList[$defaultValue] = count($valuesList);
	}
}

