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
$document = JFactory::getDocument();
?>
<style type="text/css">
    #wkDashboardTabs li{
            width: 16.5%;
        float: left;
        list-style-type: none;
        /*border: 1px solid;*/
    }
    .wkTabContainer{
        padding: 15px;
        overflow: hidden;
    }
    .wkTabContainer{
        font-family: 'Open Sans', sans-serif;
        font-size: 14px;
    }
    #wkDashboardTabs li a{
        font-weight: 500;
    }
    #j-main-container{
        padding-right: 5px;
    }
    ul,ol{
        margin: 0px;
    }

</style>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
$document->addStyleSheet(JURI::base().'components/com_uvdeskwebkul/assets/css/uvdesk.css');
$model=$this->getModel('dashboard');
$tickets=json_decode($model->getTickets());

if (isset($tickets->tabs)) {
    $tabs=json_decode(json_encode($tickets->tabs), true);
    ?>
    <div class="orderhistory_main">
        <form action="#" method="post" name="adminForm" id="adminForm">
            <div id="j-main-container"  class="span12">
                <div class="">
                    <div class="block-title" style="float:none">
                        <h3><?php echo JText::_('COM_UVDESKWEBKUL_VIEW_DASHBOARD')?> </h3>
                    </div>
                </div>
                <div class="wkTabContainer">
                    <ul id = "wkDashboardTabs" >
                        <a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=1')?>">
                            <li class = "open">
                                <label><?php echo $tabs[1]?></label>
                                <label><?php echo JText::_('COM_UVDESKWEBKUL_DASHBOARD_OPEN_TICKETS')?></label>
                                <div class="vr">&nbsp;</div>
                            </li>
                        </a>
                        <a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=2')?>">
                            <li class="pending">
                                <label><?php echo $tabs[2]?></label>
                                <label><?php echo JText::_('COM_UVDESKWEBKUL_DASHBOARD_PENDING_TICKETS')?></label>
                                <div class="vr">&nbsp;</div>
                            </li>
                        </a>
                        <a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=3')?>">
                            <li class="resolved">                            
                                <label><?php echo $tabs[3]?></label>
                                <label><?php echo JText::_('COM_UVDESKWEBKUL_DASHBOARD_RESOLVED_TICKETS')?></label>
                                <div class="vr">&nbsp;</div>
                            </li>
                        </a>                    
                        <a href="<?php echo  JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=4')?>">
                            <li class="closed">
                                <label><?php echo $tabs[4]?></label>
                                <label><?php echo JText::_('COM_UVDESKWEBKUL_DASHBOARD_CLOSED_TICKETS')?></label>
                                <div class="vr">&nbsp;</div>
                            </li>
                        </a>                
                        <a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=5')?>">
                            <li class="spam">
                                <label><?php echo $tabs[5]?></label>
                                <label><?php echo JText::_('COM_UVDESKWEBKUL_DASHBOARD_SPAM_TICKETS')?></label>
                                <div class="vr">&nbsp;</div>
                            </li>
                        </a>
                        <a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=6')?>">
                            <li class="answered">
                                <label><?php echo $tabs[6]?></label>
                                <label><?php echo JText::_('COM_UVDESKWEBKUL_DASHBOARD_ANSWERED_TICKETS')?></label>
                                <div class="vr">&nbsp;</div>
                            </li>
                        </a>
                    </ul>
                </div>
                <div id = "wkTabContent" class = "tab-content">
                    <div class="wkrating">
                        <div class="ratings">
                            <h4 class="panel-title">
                                <i class="fa fa-star"></i><span class="wkiconspacing"><?php echo JText::_('COM_UVDESKWEBKUL_DASHBOARD_RATING')?></span>
                            </h4>
                            <div class="no-data">
                                <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                <?php echo JText::_('COM_UVDESKWEBKUL_DASHBOARD_NO_DATA_TO_DISPLAY')?>
                            </div>
                        </div>
                    </div>
                    <div class="wktask">
                        <div class="ratings">
                            <h4 class="panel-title">
                                <i class="fa fa-tasks"></i><span class="wkiconspacing"><?php echo JText::_('COM_UVDESKWEBKUL_DASHBOARD_TASK')?></span>
                            </h4>
                            <div class="no-data">
                                <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                <?php echo JText::_('COM_UVDESKWEBKUL_DASHBOARD_NO_DATA_TO_DISPLAY')?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php

} else {
    JFactory::getApplication()->enqueueMessage('Please fill correct API key and sub domain', 'error');
}
?>
