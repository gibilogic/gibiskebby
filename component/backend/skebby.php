<?php

/**
 * @version       $Id: backend/skebby.php 2013-06-26 00:00:00Z matteo $
 * @package       GiBi Skebby
 * @author        GiBiLogic
 * @authorUrl     http://www.gibilogic.com
 * @authorEmail   info@gibilogic.com
 * @copyright     Copyright (C) 2013 GiBiLogic. All rights reserved.
 * @license       GNU/GPLv2
 * @description   Backend entry point
 */

defined('_JEXEC') or die();
jimport('joomla.application.component.helper');

$view_name = JRequest::getWord('view', 'skebby');
$path = JPATH_COMPONENT . '/controllers/' . $view_name . '.php';
if (file_exists($path)) {
	require_once $path;

	$classname = 'SkebbyController' . ucfirst($view_name);
	$controller = new $classname();
	$controller->execute(JRequest::getCmd('task', 'index'));
	$controller->redirect();
}
else {
	die("Controller not found");
}
