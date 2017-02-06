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
	jQuery('.wk-checkall-toggle').on('change',function(){
		if(this.checked) {
			jQuery('.mass-action-checkbox').attr('checked', true);
		}
		else{
			jQuery('.mass-action-checkbox').attr('checked', false);

		}
	});
	jQuery('.attach-file').click(function(){
    	jQuery('#attachments').trigger('click');
	});
	jQuery('.deleteMultiple').on('click',function(){
		var selected=new Array();

		jQuery('.wktable .mass-action-checkbox').each(function () {
			if(jQuery(this).is(":checked")){
				selected.push(jQuery(this).val());
			}
		});
		if(selected.length){
			jQuery.ajax({
				url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewtickets&task=viewtickets.updateTicket"?>',
				type:"POST",
				data:{'apiurl':'tickets/trash.json','params':selected,"forselect":"deleted"},
				success:function(data){
					location.reload(); 
				}

			});
		}
		else{
			alert("Please Select at least one ticket");
		}
	});
	jQuery('.assignment').on('change',function(){
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
				data:{'apiurl':'tickets/agent.json ','params':selected,'selected':jQuery(this).val(),"forselect":"assignment"},
				success:function(data){
					location.reload(); 					
				}

			});
		}
		else{

			alert("Please Select at least one ticket");
			 jQuery('.assignment').prop('selectedIndex',0);
			//return jQuery(this).defaultSelected
		}
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
	jQuery('.groups').on('change',function(){
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
				data:{'apiurl':'tickets/group.json','params':selected,'selected':jQuery(this).val(),"forselect":"groups"},
				success:function(data){
					console.log(data);
					//location.reload(); 					
				}

			});
		}
		else{

			alert("Please Select at least one ticket");
			 jQuery('.assignment').prop('selectedIndex',0);
		}
	});
	jQuery('.priority').on('change',function(){
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
				data:{'apiurl':'tickets/priority.json','params':selected,'selected':jQuery(this).val(),"forselect":"priority"},
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
	jQuery('.label').on('change',function(){
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
				data:{'apiurl':'tickets/label.json','params':selected,'selected':jQuery(this).val(),"forselect":"label"},
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

<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">  
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
$document->addStyleSheet(JURI::base().'components/com_uvdeskwebkul/assets/css/uvdesk.css');
$status=JRequest::getVar('status');
if(!isset($status)){
	$status=1;
}
$model=$this->getModel();
$members=json_decode($model->getMembers());
$members=$members->users;
$tickets=json_decode($model->getTickets());
/*$ticketType=json_decode($model->getTicketType());*/
$customFields=json_decode($model->customFields());
$membersArray=array();
if($status==1){
	$sname="Open";
}
elseif($status==2){
	$sname="Answered";

}
elseif($status==3){
	$sname="Pending";

}
elseif($status==4){
	$sname="Resolved";

}
elseif($status==5){
	$sname="Closed";

}
else{
	$sname="Spam";

}
?>
<script type="text/javascript">
	jQuery(function(){
		jQuery('body').append('<div class="content-wrap"><img class="ajax-loader-img ajax-loader" style="" src="https://cdn.uvdesk.com/uvdesk/images/aa1b406.gif" alt=""></div>');
		jQuery("#wkTabs li:nth-child(<?php echo $status?>)").addClass('active');
		jQuery("#wkTabContent div:nth-child(<?php echo $status?>)" ).addClass('in active');
		agent='<select id="agent" name="agent" class="inputbox wkAgent"> <option value="0">Select Agent</option>';
		<?php
			foreach ($members as $value) {
				?>
					agent+='<option value="<?php echo $value->id?>"><?php echo $value->name?></option>';
				<?php
			}
		?>
		agent+="</select></div>";
	
		//document.write(agent);
		//jQuery('.filter-single.status .label-name').empty();
		//jQuery('.filter-single.status .label-name').append("<?php echo $sname?>");
	});
	
	
</script>
<div class="orderhistory_main">
	<!-- <form action="#" method="post" name="adminForm" id="adminForm"> -->
		<div id="j-sidebar-container" class="span3">
			<div class="panel panel-default">
				<div class="panel-heading">
	                <h4 class="panel-title">Tickets</h4>
	            </div>
				<div class="panel-body">
					<strong>
						<i class="fa fa-inbox"></i>Support Mailbox	            	
					</strong>
					<div class="ticket-label-block">
						<ul class="list-block predefined-label-list">
							
						</ul>
						<span class="clearfix"></span>
						<button class="btn btn-default" style="margin-top: 10px; float: left; color: #617d8a; border: 1px dashed #617d8a; padding: 4px 10px;" data-target="#createTicketModal" data-toggle="modal"> 
							<i class="fa fa-plus-circle"></i>Create Ticket
						</button>
					</div>
				</div>
			</div>
			<div class="modal fade" id="createTicketModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
		<div class="modal-dialog modal-sm" role="document">		    	
			<div class="modal-content">
				<div class="modal-content">
					<div class="modal-header">
					    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
					    	<span aria-hidden="true">×</span>
					    </button>
					   	<h4 id="myModalLabel" class="modal-title text-center">Create a Ticket</h4>
					</div>
					<div class="modal-body">
						<form name="" method="post" action="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&task=viewtickets.createTicket')?>" enctype="multipart/form-data" novalidate="false" id="create-ticket-form">
						   	<div enctype="multipart/form-data" novalidate="false" id="create-ticket-form">
							   	<div class="">
							   		<div class="form-group required ">
							   			<label for="name" class="required">Customer Name</label>
						   				<input id="name" name="name" required="required" placeholder="Enter Name" class="form-control" type="text">
					   				</div>
				   				</div> 
			   				    <div class="">
			   				    	<div class="form-group required ">
			   				    		<label for="from" class="required">Your Email</label>
		   				    			<input id="from" name="from" required="required" placeholder="Enter Your Email" class="form-control" type="email">
					    			</div>
				    			</div>
			    			    <div class="">
			    			    	<div class="form-group required ">
			    			    		<label for="type" class="required">Type</label>
			    			    		
										<select id="type" name="type" required="required" data-role="tagsinput" class="selectpicker form-control" tabindex="-98">
											<option value="" selected="selected">Choose query type</option>
											<?php 
											foreach ($tickets->type as $value) {
												?>											
											<option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
											<?php
											}
											?>
										</select>
									</div>
								</div>
								</div>
							    <div class="">
							    	<div class="form-group required ">
							    		<label for="subject" class="required">Subject</label>
							    		<input id="subject" name="subject" required="required" placeholder="Enter Subject" class="form-control" type="text">
						    		<!-- </div> -->
					    		</div>
				    		    <div class="">
				    		    	<div class="form-group required ">
				    		    		<label for="reply" class="required">Message</label>
				    		    		<textarea id="reply" name="reply" required="required" placeholder="Brief Description about your query" data-iconlibrary="fa" data-height="250" class="form-control"></textarea>
						    		</div>
					    		</div>   
					    		<div class="">
					    			<div class="form-group ">
					    				<div class="labelWidget">
					    					<input id="attachments" name="attachments[]" infolabel="right" infolabeltext="+ Attach File" decoratefile="decorateFile" decoratecss="attach-file" enableremoveoption="enableRemoveOption" multiple="true" class="fileHide" type="file">
					    					<label class="attach-file pointer"></label>
					    					<i class="fa fa-times remove-file pointer"></i>
				    					</div>
										<span class="label-right pointer" id="addFile">+ Attach File</span>
									</div>
								</div>
								<input id="token" name="token" value="<?php  ?>" type="hidden">
							</div> 
							<div class="custom-fields">
								<?php

									foreach ($customFields as $value) {
										$dependent='independent';
										if(count($value->customFieldsDependency)){
											$dependent=" dependent";
											foreach ($value->customFieldsDependency as $dependency){
												$dependent.=" dependency".$dependency->id;
											}
										}
								?>
									<div class="<?php echo $dependent?>">
										<div class="form-group ">
											<label for="fororder Id<?php echo $value->id?>"><?php echo $value->name ?></label>
											<input name="customFields[<?php echo $value->id?>]" class="form-control" value="" id="fororder Id<?php echo $value->id?>" required="<?php echo $value->required?>" placeholder="<?php echo $value->value?>" type="<?php echo $value->fieldType?>">
										</div>
									</div>	
								<?php
								}
								?>							
							</div>
							<div class="">
							    <button type="submit" id="submit1" name="submit1" class="btn btn-md btn-info">Create Ticket</button>
							</div>
						</form>
				    </div>
				</div>
			</div>
		</div>
	</div>			
		</div>
		<div id="j-main-container"  class="span9">
			<div class="wkTopBar">
				<div class="withoutSelection">
					<div class="agents" style="float: left;margin-right: 5px;">
						<select class="assignment">
						<option value="0" selected="selected">Select Agent</option>
							<?php foreach ($members as $value) {?>
								<option value="<?php echo $value->id?>"><?php echo $value->name?></option>
							<?php 	}?>
						</select>
					</div>
					<div class="mass-status" style="float: left;margin-right: 5px;">
						<select class="status">
						<option value="0" selected="selected">Move To</option>
							<?php foreach ($tickets->status as $value) {?>
								<option value="<?php echo $value->id?>"><?php echo $value->name?></option>
							<?php 	}?>
						</select>
					</div>
					<div class="mass-group" style="float: left;margin-right: 5px;">
						<select class="groups">
						<option value="0" selected="selected">Group</option>
							<?php foreach ($tickets->group as $value) {?>
								<option value="<?php echo $value->id?>"><?php echo $value->name?></option>
							<?php 	}?>
						</select>
					</div>
					<div class="mass-priority" style="float: left;margin-right: 5px;">
						<select class="priority">
						<option value="0" selected="selected">Priority</option>
							<?php foreach ($tickets->priority as $value) {?>
								<option value="<?php echo $value->id?>"><?php echo $value->name?></option>
							<?php 	}?>
						</select>
					</div>
					<div class="mass-label" style="float: left;margin-right: 5px;">
						<select class="label">
						<option value="0" selected="selected">Label</option>
							<?php foreach ($tickets->labels->custom as $value) {?>
								<option value="<?php echo $value->id?>"><?php echo $value->name?></option>
							<?php 	}?>
						</select>
					</div>
					<div class="mass-label" style="float: left;margin-right: 5px;">
						<div class="deleteMultiple btn btn-default"><i class="fa fa-trash-o"></i>Delete</div>
					</div>
				</div>
				<div class="withSelection">
					<div></div>
				</div>
				<div class="filter-header form-horizontal">
					<div class="filter-single all hide-remove wk_label_remove" data-filter="all">
						<span class="filter-label">Label :</span>
						<span class="label-name">All</span>
					</div>
					<div class="filter-action"></div>
					<div class="filter-single status hide-remove wk_status_remove" data-filter="status">
						<span class="filter-label">Status : </span>
							<span class="label-name">Open</span>
					</div>
					
				</div>
			</div>
			<div id="tabsContainer">
				<ul id = "wkTabs" class = "nav nav-tabs">
					<li class = "open">
						<a href = "#open" data-toggle = "tab">
							<i class="fa fa-inbox"></i>
							<p class="label-text">Open</p>
						</a>
					</li>				
					<li class="pending">
						<a href = "#pending" data-toggle = "tab">
						<i class="fa fa-exclamation-triangle"></i>
						<p class="label-text">Pending</p>
						</a>
					</li>
					<li class="answered">
						<a href = "#answered" data-toggle = "tab">
						<i class="fa fa-lightbulb-o"></i>
						<p class="label-text">Answered</p>
						</a>
					</li>	
					<li class="resolved">
						<a href = "#resolved" data-toggle = "tab">
						<i class="fa fa-check-circle"></i>
						<p class="label-text">Resolved</p>
						</a>
					</li>			
					<li class="closed">
						<a href = "#closed" data-toggle = "tab">
						<i class="fa fa-minus-circle"></i>
						<p class="label-text">Closed</p>
						</a>
					</li>
					<li class="spam"> 
						<a href = "#spam" data-toggle = "tab">
						<i class="fa fa-ban"></i>
						<p class="label-text">Spam</p>
						</a>

					</li>				
				</ul>
				<script type="text/javascript">
					jQuery(function(){
						jQuery('.wk_label_remove .label-name').text('<?php echo JRequest::getVar("label");?>');	
						callApi('<?php echo $status;?>','<?php echo JRequest::getVar("label");?>');
						jQuery("#wkTabs li").on('click',function(){
							if(!jQuery(this).hasClass('active')){
								callApi(jQuery(this).index()+1);
							}		
							/*window.history.pushState("jQuery(this).index()+1", "Title", "/40");		*/
						});
					});
					function callApi(status,label){
						var customerId="<?php echo JRequest::getVar('customerId')?>";
						jQuery.ajax({
							url:"index.php?option=com_uvdeskwebkul&task=viewtickets.apiCall",
							data:{
									status:status,
									label:label,
									customerId:customerId,
							},
							dataType:'json',
							method:'POST',
							success: function(result){
								result=JSON.parse(JSON.stringify(result));
								if(result!=null){
									var star='gray';
									var bodyString='';
									var website='envelope-o';
									for (var i =1; i<=Object.keys(result.tabs).length; i++) {
										jQuery('#wkTabs li:nth-child('+i+') a span').remove();
										jQuery('#wkTabs li:nth-child('+i+') a').append('<span class="badge">'+result.tabs[i]+'</span>');
									};
									labels='<li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets&label=all")?>">All<span class="badge badge-success">'+result.labels.predefind.all+'</span></a></li><li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets&label=new")?>">New<span class="badge badge-success">'+result.labels.predefind.new+'</span></a></li><li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets&label=unassigned")?>">Unassigned<span class="badge badge-danger">'+result.labels.predefind.unassigned+'</span></a></li><li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets&label=mine")?>">Assigned to me<span class="badge badge-success">'+result.labels.predefind.mine+'</span></a></li><li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets&label=starred")?>">Starred<span class="badge badge-success">'+result.labels.predefind.starred+'</span></a></li><li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets&label=trashed")?>">Trashed<span class="badge badge-success">'+result.labels.predefind.trashed+'</span></a></li>';
								jQuery('.predefined-label-list').empty();
								jQuery('.predefined-label-list').append(labels);
								if(Object.keys(result.tickets).length>0){
										for (var i =0; i<Object.keys(result.tickets).length; i++) {
											if(result.tickets[i].isStarred!==null){
												star='yellow';
											}
											if(result.tickets[i].source=='website'){
												website='television';
											}
											var group='';
											if(result.tickets[i].group!=null){
												group='<span class="badge badge-lg group">'+result.tickets[i].group+'</span>';
											}
											var unassigned='';
											var unassignedName='Unassigned';
											if(result.tickets[i].agent==null){
												unassigned='Unassigned';
											}
											else{
												unassigned=result.tickets[i].agent.name;
												unassignedName=result.tickets[i].agent.name.substr(0,result.tickets[i].agent.name.indexOf(' '));
											}
											/*console.log(result.tickets[i]);return;*/
											bodyString+='<tr><td  class="quick-link" ><div class="icheckbox_square-blue" style="position:relative;"><input class="mass-action-checkbox checkbox" value="'+result.tickets[i].id+'" style="" type="checkbox"/></td><td style="color:'+result.tickets[i].priority.color+'">'+result.tickets[i].priority.name+'</td><td class="id"><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'");?>"> #'+result.tickets[i].incrementId+'</a></td><td class="customer-name"><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'");?>" title="'+result.tickets[i].customer.name+'">'+result.tickets[i].customer.name+'</a></td><td class="subject"><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'");?>">'+result.tickets[i].subject+'</a><span class="fade-subject"></span></td><td class="details"><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'");?>"><span class="date">'+result.tickets[i].formatedCreatedAt+'</span><span class="badge badge-lg">'+result.tickets[i].totalThreads+'</span></a></td><td>'+group+'</td><td class="agent-name"><a href="#" class="edit-ticket-agent"></a><span></span><a class="semibold" href="javascript:void(0)" title="'+unassigned+'">'+unassignedName+'</a></td><td><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'");?>"><botton class="btn btn-success">View Ticket</button></a></td></tr>';												
											}
									}
									else{
										bodyString="<tbody><tr><td colspan='6'><p style='text-align:center'>No Record Found</p></td></tr><tr></tr></tbody>"
									}
									jQuery('.wktable').empty();
									jQuery('.wktable').append(bodyString);
								}							
							},
							beforeSend: function(){
						        jQuery('.content-wrap').show();
						    },
						    complete: function(){							    	
								jQuery('.wk_status_remove .label-name').empty();
								jQuery('.wk_status_remove .label-name').text(jQuery('#wkTabs li.active .label-text').text());
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
					<div class = "tab-pane fade" id = "open">
						<div class="ticket-table">
							<table class="table table-bordered">
								<thead style="background-color:#EEEEEE">
									<tr>
										<th><input name="checkall-toggle" class="wk-checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
										<th>Priority</th>
										<th>Ticket No.</th>
										<th>Customer Name</th>
										<th>Subject</th>
										<th>Created Date</th>
										<th>Group</th>
										<th>Agent</th>
										<th>Action</th>
									</tr>
								</thead>
									<tbody class="wktable">
									
								</tbody>
							</table>
						</div>
					</div>
					<div class = "tab-pane fade" id = "pending">
						<div class="ticket-table">
								<table class="table table-bordered">
								<thead style="background-color:#EEEEEE">
									<tr>
										<th><input name="checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
										<th>Priority</th>
										<th>Ticket No.</th>
										<th>Customer Name</th>
										<th>Subject</th>
										<th>Created Date</th>
										<th>Group</th>
										<th>Agent</th>
										<th>Action</th>
									</tr>
								</thead>
									<tbody class="wktable">
									
								</tbody>
							</table>
						</div>
					</div>
					<div class = "tab-pane fade" id = "answered">
						<div class="ticket-table">
							<table class="table table-bordered">
								<thead style="background-color:#EEEEEE">
									<tr>
										<th><input name="checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
										<th>Priority</th>
										<th>Ticket No.</th>
										<th>Customer Name</th>
										<th>Subject</th>
										<th>Created Date</th>
										<th>Group</th>
										<th>Agent</th>
										<th>Action</th>
									</tr>
								</thead>
									<tbody class="wktable">
									
								</tbody>
							</table>
						</div>
					</div>	
					<div class = "tab-pane fade" id = "resolved">
						<div class="ticket-table">
							<table class="table table-bordered">
								<thead style="background-color:#EEEEEE">
									<tr>
										<th><input name="checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
										<th>Priority</th>
										<th>Ticket No.</th>
										<th>Customer Name</th>
										<th>Subject</th>
										<th>Created Date</th>
										<th>Group</th>
										<th>Agent</th>
										<th>Action</th>
									</tr>
								</thead>
									<tbody class="wktable">
									
								</tbody>
							</table>
						</div>
					</div>	
					<div class = "tab-pane fade" id = "closed">
						<div class="ticket-table">
							<table class="table table-bordered">
								<thead style="background-color:#EEEEEE">
									<tr>
										<th><input name="checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
										<th>Priority</th>
										<th>Ticket No.</th>
										<th>Customer Name</th>
										<th>Subject</th>
										<th>Created Date</th>
										<th>Group</th>
										<th>Agent</th>
										<th>Action</th>
									</tr>
								</thead>
									<tbody class="wktable">
									
								</tbody>
							</table>
						</div>
					</div>	
					<div class = "tab-pane fade" id = "spam">
						<div class="ticket-table">
								<table class="table table-bordered">
								<thead style="background-color:#EEEEEE">
									<tr>
										<th><input name="checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
										<th>Priority</th>
										<th>Ticket No.</th>
										<th>Customer Name</th>
										<th>Subject</th>
										<th>Created Date</th>
										<th>Group</th>
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
	<!-- </form> -->
	
</div>

