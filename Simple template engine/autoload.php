<?php

/**
* Autoload
*
* @author   Vadim Glusmann
* @packet   CodingChallenge
*/
include_once 'app\AbstractConfig.php';
include_once 'app\AbstractTemplateEngine.php';

include_once 'configs\BasicConfig.php';

include_once 'engines\SimpleTemplateEngine.php';
include_once 'engines\ExtraTemplateEngine.php';
include_once 'TemplateEngineApp.php';