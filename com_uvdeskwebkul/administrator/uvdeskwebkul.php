<?php
/**
 * Joomla Help Desk Ticket System
 *
 * PHP version 7.0
 *
 * @category   Component
 * @package    Joomla
 * @author     WebKul software private limited <support@webkul.com>
 * @copyright  2010 WebKul software private limited
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version    GIT:1.0
 * @filesource http://store.webkul.com
 * @link       Technical Support:  webkul.uvdesk.com
 */
// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_uvdeskwebkul')) {
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}
// Include dependancies
jimport('joomla.application.component.controller');
JLoader::registerPrefix('Uvdeskwebkul', JPATH_COMPONENT_ADMINISTRATOR);
$controller = JControllerLegacy::getInstance('Uvdeskwebkul');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
