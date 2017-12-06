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
/**
 * [UvdeskwebkulControllerViewtickets controller class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
abstract class JHtmlListhelper
{
    /**
     * Method to get model
     *  
     * @param int    $value value
     * @param string $view  view name
     * @param string $field field name
     * @param int    $i     numbering
     *
     * @return void
     */
    static function toggle($value = 0,$view='', $field='', $i=0)
    {
        $states = array(
            0 => array('icon-remove', JText::_('Toggle'), 'inactive btn-danger'),
            1 => array('icon-checkmark', JText::_('Toggle'), 'active btn-success')
        );

        $state  = \Joomla\Utilities\ArrayHelper::getValue($states, (int) $value, $states[0]);
        $text   = '<span aria-hidden="true" class="' . $state[0] . '"></span>';
        $html   = '<a href="#" class="btn btn-micro ' . $state[2] . '"';
        $html  .= 'onclick="return toggleField(\'cb'.$i.'\',\'' . $view . '.toggle\',\'' . $field . '\')" title="' . JText::_($state[1]) . '">' . $text . '</a>';

        return $html;
    }
}
