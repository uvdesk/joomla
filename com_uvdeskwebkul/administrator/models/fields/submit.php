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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.formfield');
/**
 * [JFormFieldSubmit custom field class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class JFormFieldSubmit extends JFormField
{
    protected $type = 'submit';
    protected $value;
    protected $for;
    /**
     * Get a form field markup for the input
     *
     * @return string
     */
    public function getInput()
    {
        $this->value = $this->getAttribute('value');
        return '<button id="' . $this->id . '"'
        . ' name="submit_' . $this->for . '"'
        . ' value="' . $this->value . '"'
        . ' title="' . JText::_('JSEARCH_FILTER_SUBMIT') . '"'
        . ' class="btn" style="margin-top: -10px;">'
        . JText::_('JSEARCH_FILTER_SUBMIT')
        . ' </button>';
    }
}
