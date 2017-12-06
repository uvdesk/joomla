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

jimport('joomla.application.component.controller');
/**
 * [UvdeskwebkulController main controller class]
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
     * Method display
     *
     * @param array $cachable  cacheble or not
     * @param array $urlparams is params of Url
     *
     * @return array  The URL arguments to use to assemble the subsequent URL.
     *
     * @since 3.3
     */
    public function display($cachable = false, $urlparams = false)
    {
        $app  = JFactory::getApplication();
        $view = $app->input->getCmd('view', 'viewtickets');
        $app->input->set('view', $view);

        parent::display($cachable, $urlparams);

        return $this;
    }
}
