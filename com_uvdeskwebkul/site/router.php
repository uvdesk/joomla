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
JLoader::registerPrefix('Uvdeskwebkul', JPATH_SITE . '/components/com_uvdeskwebkul/');
/**
 * [UvdeskwebkulRouter Router class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class UvdeskwebkulRouter extends JComponentRouterBase
{
    /**
     * Build method for URLs
     * This method is meant to transform the query parameters into a more human
     * readable form. It is only executed when SEF mode is switched on.
     *
     * @param array $query An array of URL arguments
     *
     * @return array  The URL arguments to use to assemble the subsequent URL.
     *
     * @since 3.3
     */
    public function build(&$query)
    {
        $segments = array();
        $view     = null;

        if (isset($query['task'])) {
            $taskParts  = explode('.', $query['task']);
            $segments[] = implode('/', $taskParts);
            $view       = $taskParts[0];
            unset($query['task']);
        }
        if (isset($query['view'])) {
            $segments[] = $query['view'];
            $view = $query['view'];            
            unset($query['view']);
        }
        if (isset($query['id'])) {
            if ($view !== null) {
                $segments[] = $query['id'];
            } else {
                $segments[] = $query['id'];
            }

            unset($query['id']);
        }
        if (isset($query['ticketId'])) {
            if ($view !== null) {
                $segments[] = $query['ticketId'];
            } else {
                $segments[] = $query['ticketId'];
            }

            unset($query['ticketId']);
        }
        return $segments;
    }

    /**
     * Parse method for URLs
     * This method is meant to transform the human readable URL back into
     * query parameters. It is only executed when SEF mode is switched on.
     *
     * @param array $segments The segments of the URL to parse.
     *
     * @return array The URL attributes to be used by the application.
     *
     * @since 3.3
     */
    public function parse(&$segments)
    {
        $vars = array();

        // View is always the first element of the array
        $vars['view'] = array_shift($segments);
        $model        = UvdeskwebkulHelpersUvdeskwebkul::getModel($vars['view']);

        while (!empty($segments)) {
            $segment = array_pop($segments);
            // If it's the ID, let's put on the request
            if (is_numeric($segment)) {
                $vars['id'] = $segment;
            } else {
                $vars['task'] = $vars['view'] . '.' . $segment;
            }
        }

        return $vars;
    }
}
