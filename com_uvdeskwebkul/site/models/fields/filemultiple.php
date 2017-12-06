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

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
/**
 * [JFormFieldFileMultiple custom field class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class JFormFieldFileMultiple extends JFormField
{
    /**
     * The form field type.
     *
     * @var string
     * 
     * @since 1.6
     */
    protected $type = 'file';

    /**
     * Method to get the field input markup.
     *
     * @return string The field input markup.
     *
     * @since 1.6
     */
    protected function getInput()
    {
        // Initialize variables.
        $html = '<input type="file" name="' . $this->name . '[]" multiple >';

        return $html;
    }
}
