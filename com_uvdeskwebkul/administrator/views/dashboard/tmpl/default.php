<?php
/**
* @category component - Joomla Help Desk Ticket System
* @package		Joomla.Componnents
* @author    WebKul software private limited 
* @copyright Copyright (C) 2010 webkul.com. All Rights reserved.
* @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
* @filesource  http://store.webkul.com
* @link Technical Support:  Forum - http://webkul.com/ticket
* @version v1.0
**/
// no direct access
defined('_JEXEC') or die;
jimport( 'joomla.filter.output' );
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
//$document->addStyleSheet('');
$document->addStyleSheet(JURI::base().'components/com_uvdeskwebkul/assets/css/uvdesk.css');
$model=$this->getModel('dashboard');
$tickets=json_decode($model->getTickets());
$tabs=json_decode(json_encode($tickets->tabs), True);
?>
<div class="orderhistory_main">
	<form action="#" method="post" name="adminForm" id="adminForm">
		<div id="j-main-container"  class="span12">
			<div class="">
				<div class="block-title">
					<h3>Dashboard </h3>
				</div>
			</div>
			<div class="wkTabContainer">
				<ul id = "wkDashboardTabs" >
					<li class = "open">
						<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=1')?>"><?php echo $tabs[1]?></a>
						<label>Open Tickets</label>
					</li>				
					<li class="pending">
						<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=2')?>"><?php echo $tabs[2]?></a>
						<label>Pending Tickets</label>
					</li>
					<li class="answered">
						<a href="<?php echo  JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=3')?>"><?php echo $tabs[3]?></a>
						<label>Answered Tickets</label>
					</li>	
					<li class="resolved">
						<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=4')?>"><?php echo $tabs[4]?></a>
						<label>Resolved Tickets</label>
					</li>			
					<li class="closed">
						<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=5')?>"><?php echo $tabs[5]?></a>
						<label>Closed Tickets</label>
					</li>
					<li class="spam">
						<a href="<?php echo  JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&status=6')?>"><?php echo $tabs[6]?></a>
						<label>Spam Tickets</label>
					</li>
				</ul>
			</div>
			<div id = "wkTabContent" class = "tab-content">
				<div class="wkrating">
					<div class="ratings">
						<h4 class="panel-title">
							<i class="fa fa-star"></i>Ratings 
						</h4>
						<div class="no-data">
							<i class="fa fa-bar-chart" aria-hidden="true"></i>
							No data to display
						</div>
					</div>
				</div>
				<div class="wktask">
					<div class="ratings">
						<h4 class="panel-title">
							<i class="fa fa-tasks"></i>Tasks
						</h4>
						<div class="no-data">
							<i class="fa fa-bar-chart" aria-hidden="true"></i>
							No data to display
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
