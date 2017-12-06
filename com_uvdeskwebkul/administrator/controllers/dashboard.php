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

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;
/**
 * [UvdeskwebkulControllerDashboard controller class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class UvdeskwebkulControllerDashboard extends JControllerAdmin
{
    /**
     * Method to clone existing Viewtickets
     *
     * @return void
     */
    public function duplicate()
    {
        // Check for request forgeries
        Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $pks = $this->input->post->get('cid', array(), 'array');
        try
        {
            if (empty($pks)) {
                throw new Exception(JText::_('COM_UVDESKWEBKUL_NO_ELEMENT_SELECTED'));
            }
            ArrayHelper::toInteger($pks);
            $model = $this->getModel();
            $model->duplicate($pks);
            $this->setMessage(Jtext::_('COM_UVDESKWEBKUL_ITEMS_SUCCESS_DUPLICATED'));
        }
        catch (Exception $e)
        {
            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
        }

        $this->setRedirect('index.php?option=com_uvdeskwebkul&view=dashboard');
    }
    /**
     * Proxy for getModel.
     *
     * @param string $name   Optional. Model name
     * @param string $prefix Optional. Class prefix
     * @param array  $config Optional. Configuration array for model
     *
     * @return object The Model
     *
     * @since 1.6
     */
    public function getModel($name = 'dashboard', $prefix = 'UvdeskwebkulModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));

        return $model;
    }
    /**
     * Method to save the submitted ordering values for records via AJAX.
     *
     * @return void
     *
     * @since 3.0
     */
    public function saveOrderAjax()
    {
        // Get the input
        $input = JFactory::getApplication()->input;
        $pks   = $input->post->get('cid', array(), 'array');
        $order = $input->post->get('order', array(), 'array');

        // Sanitize the input
        ArrayHelper::toInteger($pks);
        ArrayHelper::toInteger($order);

        // Get the model
        $model = $this->getModel();

        // Save the ordering
        $return = $model->saveorder($pks, $order);

        if ($return) {
            echo "1";
        }

        // Close the application
        JFactory::getApplication()->close();
    }
}
