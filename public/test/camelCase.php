<?php

set_include_path(implode(PATH_SEPARATOR, array(realpath('../../library'), get_include_path())));

require_once('Zend/Filter/Inflector.php');

require_once('Zend/Db/Table/Abstract.php');

require_once('Zwe/Model.php');
require_once('Zwe/Model/Blog.php');

new Zwe_Model_Blog();