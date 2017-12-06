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

jimport('joomla.application.component.view');

/**
 * [UvdeskwebkulViewViewticket View class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class UvdeskwebkulViewViewticket extends JViewLegacy
{
    protected $state;
    protected $item;
    protected $form;
    /**
     * Display the view
     *
     * @param string $tpl Template name
     *
     * @return void
     *
     * @throws Exception
     */
    public function display($tpl = null)
    {
        $this->state = $this->get('State');
        $this->item  = $this->get('Item');
        $this->form  = $this->get('Form');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }
        $user=JFactory::getUser();
        $model=$this->getModel();
        $app=JFactory::getApplication();
        if (isset($user->email)) {
            $response=$model->getApiUser($user->email);
            if (count($response->customers)) {
                $this->assignRef('customerId', $response->customers[0]->id);
            } else {
                $app->enqueueMessage(JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_UNAUTHOIRISED'), 'error');
                $app->redirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=login'));
            }
        } else {
            $app->enqueueMessage(JText::_('Please Login First'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=login'));
        }
        parent::display($tpl);
    }

}
