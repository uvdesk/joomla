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
// no direct access
defined('_JEXEC') or die;
jimport('joomla.filter.output');
jimport('joomla.html.pagination');
JHtml::_('jquery.framework');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.modal');
JHtml::_('behavior.framework', true);
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_uvdeskwebkul/assets/css/uvdesk.css');
$document->addScript(JURI::base().'components/com_uvdeskwebkul/assets/js/customer.js');
$document->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
$document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css');
$document->addScript('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js');
$model=$this->getModel();
$uvDeskCutomer=$model->getMember();
if (isset($uvDeskCutomer->customers)) {
    ?>
    <style type="text/css">
        .sweet-alert fieldset{
            display: none!important;
        }
        .wk_selectbox .icon-search{
            top:18px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <div class="orderhistory_main" style="padding: 10px">
        <div id="wk_block-container_customer" class="span12">
            <div class="wk_header_container">
            <div class="block-title">
                <h3><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS');?></h3>
            </div>
            <div class="wk_sort_search_filter">
                <div class="wk_sort_filter">
                    <!--<label for="wk_customersort" class="wk_sort_filter_label">Sort :</label>-->
                    <select name="wk_sort_filter" id="wk_customersort">
                        <option value="name ASC"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_SORT_NAME_ASC');?></option>
                        <option value="name DESC"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_SORT_NAME_DESC');?></option>
                        <option value="a.email ASC"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_SORT_EMAIL_ASC');?></option>
                        <option value="a.email DESC"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_SORT_EMAIL_DESC');?></option>
                    </select>                    
                </div>
                <div class="wk_selectbox">
                    <div class="form-search search-only" style="width: 200px;position:relative">
                        <span class="icon-search"></span>
                        <input class="form-control search-query" id="search-ticket" placeholder="<?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_SEARCH_CUSTOMER');?>" type="text">
                    </div>
                </div>
            </div>
            <div class="panel panel-default table-container" style="" id="user-table" style="overflow-x: auto;">
                <table class="table table-bordered" id="wkTabContent">
                    <thead>
                        <tr>
                            <th><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_NAME');?></th>
                            <th><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_EMAIL');?></th>
                            <th><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_OPEN_TICKETS');?></th>
                            <th><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_SOURCE');?></th>
                            <th><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_STATUS');?></th>
                            <th class="action"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_ACTION');?></th>
                            <th class="last"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_STAR');?></th>
                        </tr>
                    </thead>
                    <tbody class="wk_bodytable">
                    
                    </tbody>
                    <tfoot class="wk_pagination">
                    
                    </tfoot>         
                </table>
            </div>
        </div>
    </div>
<?php
} else {
    JFactory::getApplication()->redirect('index.php?option=com_config&view=component&component=com_uvdeskwebkul', 'Please fill correct API key and sub domain', 'error');
}
