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
if (!class_exists('UvdeskwebkulHelpersUvdeskwebkul')) {
    /**
     * [UvdeskwebkulHelpersUvdeskwebkul Helper class]
     *
     * @category Component
     * @package  Joomla
     * @author   WebKul software private limited <support@webkul.com>
     * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
     * @link     Technical Support:  webkul.uvdesk.com
     */
    class UvdeskwebkulHelpersUvdeskwebkul
    {
        /**
         * Addsubmenu
         *
         * @param string $vName name of view
         *
         * @return null|object
         */
        public static function addSubmenu($vName = '')
        {
            JHtmlSidebar::addEntry(JText::_('COM_UVDESKWEBKUL_TITLE_V1EWTICKETS'), 'index.php?option=com_uvdeskwebkul&view=viewtickets', $vName == 'v1ewtickets');
        }
        /**
         * Gets the files attached to an item
         *
         * @param int    $pk    The item's id
         * @param string $table The table's name     *
         * @param string $field The field's name
         *
         * @return array  The files
         */
        public static function getFiles($pk, $table, $field)
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query
                ->select($field)
                ->from($table)
                ->where('id = ' . (int) $pk);

            $db->setQuery($query);

            return explode(',', $db->loadResult());
        }

        /**
         * Gets a list of the actions that can be performed.
         *
         * @return JObject
         *
         * @since 1.6
         */
        public static function getActions()
        {
            $user   = JFactory::getUser();
            $result = new JObject;

            $assetName = 'com_uvdeskwebkul';

            $actions = array(
                'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
            );

            foreach ($actions as $action) {
                $result->set($action, $user->authorise($action, $assetName));
            }

            return $result;
        }
    }
}


