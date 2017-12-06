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
 * [UvdeskwebkulController Frontend Main Controller]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class UvdeskwebkulController extends JControllerLegacy
{
    /**
     * Method to display a view.
     *
     * @param boolean $cachable  If true,the view output will be cached
     * @param mixed   $urlparams An array of safe url parameters and their variable types
     *
     * @return JController This object to support chaining.
     *
     * @since 1.5
     */
    public function display($cachable = false, $urlparams = false)
    {
        $view = JFactory::getApplication()->input->getCmd('view', 'dashboard');
        JFactory::getApplication()->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }
}
