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
 * [UvdeskwebkulViewUser View class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class UvdeskwebkulViewUser extends JViewLegacy
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
        /*echo "<pre>";
        print_r($this->form);die;*/

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        //$this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return void
     *
     * @throws Exception
     */
    /*	protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $user  = JFactory::getUser();
        $isNew = ($this->item->id == 0);

        if (isset($this->item->checked_out))
        {
            $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        }
        else
        {
            $checkedOut = false;
        }

        $canDo = UvdeskwebkulHelpersUvdeskwebkul::getActions();

        JToolBarHelper::title(JText::_('COM_UVDESKWEBKUL_TITLE_viewTICKET'), 'viewticket.png');

        // If not checked out, can save the item.
        if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
        {
            JToolBarHelper::apply('viewticket.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('viewticket.save', 'JTOOLBAR_SAVE');
        }

        if (!$checkedOut && ($canDo->get('core.create')))
        {
            JToolBarHelper::custom('viewticket.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
        }

        // If an existing item, can save to a copy.
        if (!$isNew && $canDo->get('core.create'))
        {
            JToolBarHelper::custom('viewticket.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
        }

        // Button for version control
        if ($this->state->params->get('save_history', 1) && $user->authorise('core.edit')) {
            JToolbarHelper::versions('com_uvdeskwebkul.viewticket', $this->item->id);
        }

        if (empty($this->item->id))
        {
            JToolBarHelper::cancel('viewticket.cancel', 'JTOOLBAR_CANCEL');
        }
        else
        {
            JToolBarHelper::cancel('viewticket.cancel', 'JTOOLBAR_CLOSE');
        }
    }*/
}
