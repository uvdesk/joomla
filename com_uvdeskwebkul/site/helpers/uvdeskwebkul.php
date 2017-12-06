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
defined('_JEXEC') or die;
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
     * Get an instance of the named model
     *
     * @param string $name Model name
     *
     * @return null|object
     */
    public static function getModel($name)
    {
        $model = null;

        // If the file exists, let's
        if (file_exists(JPATH_SITE . '/components/com_uvdeskwebkul/models/' . strtolower($name) . '.php')) {
            include JPATH_SITE . '/components/com_uvdeskwebkul/models/' . strtolower($name) . '.php';
            $model = JModelLegacy::getInstance($name, 'UvdeskwebkulModel');
        }

        return $model;
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
     * Gets the edit permission for an user
     *
     * @param mixed $item The item
     *
     * @return bool
     */
    public static function canUserEdit($item)
    {
        $permission = false;
        $user       = JFactory::getUser();

        if ($user->authorise('core.edit', 'com_uvdeskwebkul')) {
            $permission = true;
        } else {
            if (isset($item->created_by)) {
                if ($user->authorise('core.edit.own', 'com_uvdeskwebkul') && $item->created_by == $user->id) {
                    $permission = true;
                }
            } else {
                $permission = true;
            }
        }
        return $permission;
    }
}
