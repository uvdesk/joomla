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
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
$document = JFactory::getDocument();
?>
<style type="text/css">
	#wkTabs li{
		width: 16.56%;
		float: left;
		/*border: 1px solid;*/
	}
	.content{
		border: 2px solid;
		min-width: 100px;
	}
	.navbar-inner .nav-collapse{
		display: block;
	}
	.sidebar{
		width: 100%;
	}
	.tr{
		vertical-align: middle;
	}
</style>
<script type="text/javascript">
jQuery(function(){
	jQuery('.attach-file').click(function(){
    	jQuery('#attachments').trigger('click');
	});
	jQuery('.status').on('change',function(){
		var selected=new Array();
		jQuery('.wktable .mass-action-checkbox').each(function () {
			if(jQuery(this).is(":checked")){
				selected.push(jQuery(this).val());
			}
		});
		if(selected.length&& jQuery(this).val()!=0){
			jQuery.ajax({
				url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewtickets&task=viewtickets.updateTicket"?>',
				type:"POST",
				data:{'apiurl':'tickets/status.json','params':selected,'selected':jQuery(this).val(),"forselect":"status"},
				success:function(data){
					location.reload(); 					
				}

			});
		}
		else{

			alert("Please Select at least one ticket");
			 jQuery('.assignment').prop('selectedIndex',0);
		}
	});

});
</script>
<style type="text/css">
	#tabsContainer{
		padding: 10px;
	}
	.wk_selectbox{
		float: left;
		margin-left: 10px;
		margin-bottom: 10px;
	}
	.wk_selectbox .chzn-single{
		height: 32px;
	}
	#search-ticket{
		padding-left: 25px; 
	}
	.wk_selectbox .icon-search{
		position: absolute;
		top: 12px;
		left: 10px;
		color: blue;
	}
	.orderhistory_main_front *{
		font-size: 14px;
  		font-family: Open Sans;
	}
</style>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">  
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<?php
$document->addStyleSheet(JURI::root().'administrator/components/com_uvdeskwebkul/assets/css/uvdesk.css');
$status=JRequest::getVar('status');
if(!isset($status)){
	$status=1;
}
$model=$this->getModel();
$tickets=$model->getTickets();
$customerId=$this->get('customerId');
?>
<div class="orderhistory_main_front">
	<div id="j-main-container"  class="span12">
		<div id="tabsContainer">
			<div class="wk_selectbox">
				<span>Sort By</span>
				<select id="sortticket" style="width: 120px">
					<option value="t.id">Ticket Id</option>
					<option value="name">Agent Name</option>
				</select>
			</div>
			<div class="wk_selectbox">
				<span>Status</span>
				<select id="filterticket" style="width: 120px">
				<option value="0">Filter By Status</option>
					<?php
						foreach ($tickets->status as $value) {
							$defaultStatus=1;
							?>
							<option value="<?php echo $value->id?>" style="color:<?php echo $value->color?> "><?php echo $value->name?></option>
							<?php
						}
					?>
				</select>
			</div>
			<div class="wk_selectbox">
				<div class="form-search search-only" style="width: 200px;position:relative">
                    <span class="icon-search"></span>
                    <input class="form-control search-query" id="search-ticket" placeholder="Search tickets ..." type="text">
		        </div>
			</div>
				<script type="text/javascript">
					jQuery(function(){
						jQuery('body').append('<div class="content-wrap"><img class="ajax-loader-img ajax-loader" style="" src="https://cdn.uvdesk.com/uvdesk/images/aa1b406.gif" alt=""></div>');
						var status=jQuery('#filterticket').val();
						if(status==0){
							status='<?php echo $defaultStatus?>';
						}
						callApi('<?php echo $status;?>');
						jQuery('#filterticket').on('change',function(){
							callApi(jQuery(this).val());
						});
						jQuery('#sortticket').on('change',function(){
							callApi(status);
						});
						jQuery('.wk_selectbox #search-ticket').on('keyup',function(){
							callApi(status);
						});
					});

					function callApi(status){
						var sort=jQuery('#sortticket').val();
						var search='';
						search=jQuery('.wk_selectbox #search-ticket').val();
						jQuery.ajax({
							url:"index.php?option=com_uvdeskwebkul&task=viewtickets.apiCall",
							data:{
									status:status,
									cid:'<?php echo $customerId?>',
									sort:sort,
									search:search,

							},
							dataType:'json',
							method:'POST',
							success: function(result){
								var bodyString='';
								result=JSON.parse(JSON.stringify(result));
								//console.log(result);
								if(Object.keys(result.tickets).length>0){
									for (var i =0; i<Object.keys(result.tickets).length; i++) {
										var unassigned='';
										var unassignedName='Unassigned';
										if(result.tickets[i].agent==null){
											unassigned='Unassigned';
										}
										else{
											unassigned=result.tickets[i].agent.name;
											unassignedName=result.tickets[i].agent.name.substr(0,result.tickets[i].agent.name.indexOf(' '));
										}
										var q='<?php echo JUri::root()."index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'";?>';
										
										bodyString+='<tr><td class="quick-link" style="color:'+result.tickets[i].priority.color+'">'+result.tickets[i].priority.name+'</td><td class="id">#'+result.tickets[i].incrementId+'</td><td class="subject">'+result.tickets[i].subject+'</td><td class="details"><span class="date">'+result.tickets[i].formatedCreatedAt+'</span><span class="badge badge-lg">'+result.tickets[i].totalThreads+'</span></td><td class="agent-name">'+unassignedName+'</td><td><a href="'+q+'"><botton class="btn btn-success">View Ticket</button></a></td></tr>';
										//<a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'");?>"><</a>
									}
								}
								else{
									bodyString="<tbody><tr><td colspan='6'><p style='text-align:center'>No Record Found</p></td></tr><tr></tr></tbody>"
								}
									jQuery('.wktable').empty();
									jQuery('.wktable').append(bodyString);
							},
							beforeSend: function(){
						        jQuery('.content-wrap').show();
						    },
						    complete: function(){
						        jQuery('.content-wrap').hide();
						    },
							error:function(xhr, ajaxOptions, thrownError){
									console.log(xhr);
									console.log(ajaxOptions);
									console.log(thrownError);
							}
						});
					}
				</script>
				<div id = "wkTabContent" class = "tab-content">
					<div class = "" id="open">
						<div class="ticket-table">
							<table class="table table-bordered">
								<thead style="background-color:#EEEEEE">
									<tr>
										<th>Priority</th>
										<th>Ticket No.</th>
										<th>Subject</th>
										<th>Created Date</th>
										<th>Agent</th>
										<th>Action</th>
									</tr>
								</thead>
									<tbody class="wktable">
									
								</tbody>
							</table>
						</div>
					</div>			
				</div>
		  	</div>
</div>

