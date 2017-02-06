<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Uvdeskwebkul
 * @author     webkul <support@webkul.com>
 * @copyright  Copyright (C) 2010 webkul.com. All Rights Reserved
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Uvdeskwebkul', JPATH_COMPONENT);
JLoader::register('UvdeskwebkulController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Uvdeskwebkul');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
