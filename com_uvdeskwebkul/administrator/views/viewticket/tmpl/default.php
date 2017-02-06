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
JHtml::_('jquery.framework');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.modal');
JHtml::_('behavior.framework', true);
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.framework');
$document = JFactory::getDocument();
$model=$this->getModel();
$data=json_decode($model->getData());
$tickets=json_decode($model->getTickets());
$members=json_decode($model->getMembers());
$thread=json_decode($model->getThread($data->ticket->id));
$document->addStyleSheet(JURI::base().'components/com_uvdeskwebkul/assets/css/uvdesk.css');
$document->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
$attachments='';
if(count($data->createThread->attachments)){
		$attachments="and attached ".count($data->createThread->attachments)." file(s)";
}
$usertype=$data->createThread->userType;
$ticketId=$data->ticket->id;
/*echo "<prE>";
print_r($data);die;*/
?>
<style type="text/css">
	.withoutSelection div[class^="mass"]{
	/*	position: relative;*/
	}
	/*.withoutSelection div[class^="mass"] .chzn-container{
		display: none;
		position: absolute;
	}
	.withoutSelection div[class^="mass"]{
		width: 32px!important;
	}*/
	.chosen-container .chosen-drop {
    border-bottom: 0;
    border-top: 1px solid #aaa;
    top: auto;
    bottom: 40px;
}
</style>
 <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
 <script type="text/javascript">
	jQuery(function(){
		/*jQuery('body').on('click',function(event){
			if(!jQuery(event.target).is('.withoutSelection div[class^="mass"] span')){
				jQuery('.withoutSelection div[class^="mass"] .chzn-container').css('display','none');	
			}
		});
		jQuery('.withoutSelection div[class^="mass"] span').on('click',function(){
			jQuery(this).parent().css('width','130px!important');
			jQuery('.withoutSelection div[class^="mass"] .chzn-container').css('display','none');
			jQuery(this).next().next().css({'display':'block'});/*.addClass('chzn-with-drop');
			
			
		});*/
		jQuery('.buttondevide').on('click',function(){
			if(jQuery(this).hasClass('active')){
				jQuery(this).removeClass('active');
				jQuery('#wk_drop_up').css('display','none');
			}else{
				jQuery(this).addClass('active');
				jQuery('#wk_drop_up').css('display','block');
			}
		});
		jQuery('#wk_drop_up li').on('click',function(){
			jQuery('#replyStatus').val(jQuery(this).attr('data'));
			jQuery('#ticketReply').trigger('click');
		});
		
		jQuery('body').append('<div class="content-wrap"><img class="ajax-loader-img ajax-loader" style="" src="https://cdn.uvdesk.com/uvdesk/images/aa1b406.gif" alt=""></div>');
		var ticketId="<?php echo $data->ticket->id ?>";
		jQuery.ajax({
			url:"index.php?option=com_uvdeskwebkul&task=viewtickets.apiCall",
			data:{
					ticketId:ticketId
			},
			dataType:'json',
			method:'POST',
			success: function(result){
				
			}
		});
		jQuery('#ticketReply').on('click',function(){
			jQuery.ajax({
				url:"index.php?option=com_uvdeskwebkul&task=viewticket.postReply",
				data:{
						ticketId:"<?php echo $data->ticket->id ?>"
				},
				dataType:'json',
				method:'POST',
				success: function(result){
					
				}
			});
		});
	});
jQuery(function(){
	var ticketId="<?php echo $data->ticket->id ?>";
	jQuery('.deleteMultiple').on('click',function(){
		jQuery.ajax({
				url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewtickets&task=viewticket.updateTicketSingle"?>',
				type:"POST",
				data:{'apiurl':'ticket/'+ticketId+'/trash.json',"forselect":"deleted"},
				success:function(data){
					location.reload(); 
				}

			});
		
	});
	jQuery('.assignment').on('change',function(){
		if(jQuery(this).val()!=0){
			jQuery.ajax({
				url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updateTicketSingle"?>',
				type:"POST",
				data:{'apiurl':'ticket/'+ticketId+'/agent.json','selected':jQuery(this).val(),"forselect":"assignment",ticketId:ticketId},
				success:function(data){
					location.reload(); 					
				}

			});
		}
		
	});
	jQuery('.withoutSelection .isStarred').on('click',function(){
			jQuery.ajax({
				url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updatePatch"?>',
				type:"POST",
				data:{'apiurl':'ticket/'+ticketId+'.json','selected':jQuery(this).val(),"forselect":"starred"},
				success:function(data){
					if(jQuery('.withoutSelection .isStarred i').hasClass('mark-star-yellow')){
						jQuery('.withoutSelection .isStarred i').removeClass('mark-star-yellow');
					}
					else{
						jQuery('.withoutSelection .isStarred i').addClass('mark-star-yellow');

					}
					//location.reload(); 					
				}
				,error:function(error){
					console.log(error);
				}

			});
	})
	jQuery('.status').on('change',function(){
		
		if(jQuery(this).val()!=0){
			jQuery.ajax({
				url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updatePatch"?>',
				type:"POST",
				data:{'apiurl':'ticket/'+ticketId+'.json','selected':jQuery(this).val(),"forselect":"status"},
				success:function(data){
					console.log(data);
					location.reload(); 					
				}
				,error:function(error){
					console.log(error);
				}

			});
		}
	});
	jQuery('.group').on('change',function(){
		jQuery.ajax({
				url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updatePatch"?>',
				type:"POST",
				data:{'apiurl':'ticket/'+ticketId+'.json','selected':jQuery(this).val(),"forselect":"groups"},
				success:function(data){
					console.log(data);
					location.reload(); 					
				}

			});
	});
	jQuery('.priority').on('change',function(){
		jQuery.ajax({
				url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updatePatch"?>',
				type:"POST",
				data:{'apiurl':'ticket/'+ticketId+'.json','selected':jQuery(this).val(),"forselect":"priority"},
				success:function(data){
					//console.log(data);
					location.reload(); 					
				}

			});
		});
	jQuery('.label').on('change',function(){
		alert();
		jQuery.ajax({
				url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updatePatch"?>',
				type:"POST",
				data:{'apiurl':'ticket/'+ticketId+'.json','selected':jQuery(this).val(),"forselect":"labeled"},
				success:function(data){
				 					
				}

			});
	});
	
});
jQuery(function(){
	jQuery('.wk_label_remove .label-name').text('<?php echo JRequest::getVar("label");?>');	
	callApi();
	jQuery("#wkTabs li").on('click',function(){
		if(!jQuery(this).hasClass('active')){
			callApi(jQuery(this).index()+1);
		}
	});
});
function callApi(){

	jQuery.ajax({
		url:"index.php?option=com_uvdeskwebkul&task=viewtickets.apiCall",
		data:{
				status:1,
				label:"all",
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
<div class="orderhistory_main">
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
				</div>
			</div>
		</div>
		<?php 
		if(count($data->ticket->customFieldValues)){?>
			<div class="panel panel-default">
			    <div class="panel-heading">
			        <h4 class="panel-title">Custom Fields</h4>
			    </div>
			    <?php
			    foreach ($data->ticket->customFieldValues as $value) {?>
				    <div class="panel-body">
				    	<div class="custom-field">
							<div><label><?php echo $value->ticketCustomFieldsValues->name?></label></div>
							<?php echo str_replace('"', "", $value->value); ?>
						</div>
					</div>
				<?php
				}?>
			</div>
		<?php
	}
		?>
	</div>
	<div id="wk_block-container" class="span9">
		<div id="sticky-header-sticky-wrapper" class="sticky-wrapper" style="height: 54px;">
			<div class="withoutSelection">
					<div class="isStarred" style="float: left;margin-right: 5px;">
					<?php
						if(isset($data->ticket->isStarred)){
							?>
							<a href="javascript:void(0)" class="mark-star">
								<i class="fa fa-star mark-star-yellow"></i>
							</a>
							<?php
						}
						else{
							?>
							<a href="javascript:void(0)" class="mark-star">
								<i class="fa fa-star"></i>
							</a>
							<?php
						}
					?>						
					</div>
					<div class="source" style="float: left;margin-right: 5px;">
						<?php
						if($data->ticket->source=="website"){
							?>
							<a href="" class="mark-star">
								<i class="fa fa-television source" aria-hidden="true"></i>
							</a>
							<?php
						}
						else{
							?>
							<a href="" class="mark-star">
								<i class="fa fa-envelope" aria-hidden="true"></i>
							</a>
							<?php
						}
					?>
					</div>
					<div class="mass-agents agents" style="float: left;margin-right: 5px;">
						<!-- <span title="Agent" class="wk_user"></span> -->
						<select class="assignment chosen" style="width:130px">
						<option value="0">Select Agent</option>
							<?php foreach ($members->users as $value) {?>
								<option value="<?php echo $value->id?>"><?php echo $value->name?></option>
							<?php 	}?>
						</select>
					</div>
					<div class="mass-status" style="float: left;margin-right: 5px;">
						<!-- <span title="status" class="wk_status"></span> -->
						<select class="status" style="width:130px">
						<option value="0">Move To</option>
							<?php foreach ($tickets->status as $value) {?>
								<option value="<?php echo $value->id?>"><?php echo $value->name?></option>
							<?php 	}?>
						</select>
					</div>
					<div class="mass-group" style="float: left;margin-right: 5px;">
						<!-- <span title="groups" class="wk_group"></span> -->
						<select class="group" style="width:130px">
						<option value="0">Group</option>
							<?php foreach ($tickets->group as $value) {?>
								<option value="<?php echo $value->id?>"><?php echo $value->name?></option>
							<?php 	}?>
						</select>
					</div>
					<div class="mass-priority" style="float: left;margin-right: 5px;">
						<!-- <span title="Priority" class="wk_priority"></span> -->
						<select class="priority" style="width:130px">
								<option value="0">Priority</option>
							<?php foreach ($tickets->priority as $value) {?>
								<option value="<?php echo $value->id?>"><?php echo $value->name?></option>
							<?php 	}?>
						</select>
					</div>
				<!-- 	<div class="mass-label" style="float: left;margin-right: 5px;">
						<span class="wk_label"></span>						
						<select class="label">
						<option value="0">Label</option>
							<?php foreach ($tickets->labels->custom as $value) {?>
								<option value="<?php echo $value->id?>"><?php echo $value->name?></option>
							<?php 	}?>
						</select>
					</div> -->
					<div class="mass-delete" style="float: left;margin-right: 5px;">
					<!-- 	<span title="Delete" class="icon-delete deleteMultiple"></span> -->
						<!-- <div class=" btn btn-default"><i class="fa fa-trash-o"></i>Delete</div> -->
					</div>
				</div>
		</div>
		<div class="ticket-info-block">
			<div class="left-info">
				<div class="left-info span6">
					<label class="subject">
						<span class="ticket-id">#<?php echo $data->ticket->incrementId?></span><?php echo $data->ticket->subject?>
					</label>
				</div>
				<div class="right-info">
					<div class="pull-right">
						<span class="badge priority" data-toggle="tooltip" data-placement="top" data-original-title="Priority"><?php echo $data->ticket->priority->name;?>
						</span>
						<span class="badge status" data-toggle="tooltip" data-placement="top" data-original-title="Status"><?php echo $data->ticket->status->name?></span>
						<span class="badge type" data-toggle="tooltip" data-placement="top" data-original-title="Type"><?php echo $data->ticket->type->name?></span>
						<span class="badge" data-toggle="tooltip" data-placement="top" data-original-title="Threads"><?php echo $data->ticketTotalThreads?></span>
						<span class="agent">
							<span class="badge" data-toggle="tooltip" data-placement="top" data-original-title="Agent"><i class="fa fa-user"></i>
							</span>
							<span class="name" title="<?php echo $data->ticket->agent->detail->agent->name?>">
								<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=user&userId='.$data->ticket->agent->id)?>"><?php echo $data->ticket->agent->detail->agent->firstName?></a>
							</span>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="ticket-message-block">
			<div class="ticket-create">
				<div class="thread-created-info">
					<span class="pull-left"></span>
					<span class="info">
						<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=user&userId='.$data->createThread->id);?>"><?php echo $data->createThread->fullname?>(<?php echo $data->createThread->userType?>)</a>Created a ticket <?php echo $attachments?>
					</span>
					<span class="text-right date"><?php echo $data->createThread->createdAt->date?></span>
				</div>
				<div class="">
					<?php
						if(!isset($data->ticket->{$usertype}->smallThumbnail)){
							$thumbUrl="https://cdn.uvdesk.com/uvdesk/images/d94332c.png";
						}
						else{
							$thumbUrl=$data->ticket->{$usertype}->smallThumbnail;
						}

					?>
					<div class="pull-left">
						<a style="float: left;margin-top:5px;" href="javascript:void(0)">
							<span class="round-tabs three">
								<img class="border" src="<?php echo $thumbUrl?>">
				            </span>
				        </a>
				    </div>
					<div class="thread-body">
				        <div class="thread-info">
				        	<div class="thread-info-row first">
				        		<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=user&userId='.$data->ticket->{$usertype}->id)?>">
				        			<strong><?php echo $data->ticket->{$usertype}->detail->{$usertype}->firstName?></strong>
				        		</a>
				        		<span style="display: inline-block; vertical-align: middle; margin-top: 2px; color: #314876;word-break: break-all;">
					        		<i class="fa fa-chevron-left"></i><?php echo $data->ticket->{$usertype}->email?>
					        		<!-- <strong data-toggle="tooltip" data-original-title="Tickets">(1)</strong> -->
						            <i class="fa fa-chevron-right"></i>
						        </span>
						    </div>
						</div>
						<div class="message" style="padding: 0px; margin-top: 5px;">
							<p><?php echo $data->createThread->reply?><br></p>
						</div>
						<div class="thread-attachments">
						<?php 
							/*echo "<pre>";
							print_r($data->createThread->attachments);die;*/

						if(count($data->createThread->attachments)){
							foreach ($data->createThread->attachments as $attachment) {?>
							<div class="attachment">
								<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=downloads&attachmentId='.$attachment->id.'&fileformat=image/jpeg&filename='.$attachment->name)?>" target="_blank">
									<i class="fa fa-file zip"></i>
									<span><?php echo pathinfo($attachment->name, PATHINFO_EXTENSION);?></span>
								</a>
							</div>
							<?php
							}
						}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="ticket-thread">
			<!-- 	<div class="thread-pagination">
					<button class="btn btn-default">
						<span class="pages" style="display: inline;">All Expanded
							<span class="count"></span>
						</span>
					</button>
					<span></span>
				</div> -->
				<?php 

				foreach (array_reverse($thread->threads) as $key => $value) {
					$attachmentDetail='';
					$usert=$value->userType;
					if(count($value->attachments)){
						$attachmentDetail=" and attached ".count($value->attachments)." file(s)";
					}
				?>
				<div class="thread">
					<div class="thread-created-info">						
						<span class="info">					
							<a href="javascript:void(0)" id="thread<?php echo $data->ticket->id;?>" class="copy-thread-link">#<?php echo $data->ticket->id;?> &nbsp;</a><?php echo $value->user->detail->{$usert}->name." ".$value->threadType." ".$attachmentDetail;?> 				
						</span>
						<span class="text-right date"><?php echo $value->formatedCreatedAt?></span>
					</div>
					<div class="thread-created-message">
						<div class="pull-left">									
							<a style="float: left;margin-top:5px" href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=user&userId='.$value->user->id)?>">						
				                <span class="round-tabs three">
				                	<img class="border" src="<?php echo $value->user->smallThumbnail?>" alt="">
				                </span>
			           		</a>
			        	</div>
			       		<div class="thread-body">
			           		<div class="thread-info">
			               		<div class="thread-info-row first">
			               			<a style="float: left;margin-top:5px" href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=user&userId='.$value->user->id)?>">
			               				<strong><?php echo $value->user->detail->{$usert}->name?></strong>
			               			</a>
			               		</div>
			                	<div class="thread-info-row"></div>
			                </div>
			                <div class="message reply agent">
			            		<div class="main-reply">
									<?php echo $value->reply?>
								</div>
							</div>	
							<div class="thread-attachments">
							<?php
							if(count($value->attachments)){
								foreach ($value->attachments as $attachment) {
							?>		
									<div class="attachment">
										<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=downloads&attachmentId='.$attachment->id)?>" target="_blank">
											<i class="fa fa-file zip" title="" data-toggle="tooltip" data-original-title="<?php echo $attachment->name ?>">
												<span><?php echo pathinfo($attachment->name, PATHINFO_EXTENSION);?></span>
											</i>
										</a>		
									</div>
								<?php
								}
							}
							if (count($value->attachments)) {
							?>
							</div>	
							<div class="thread-attachments-<?php echo pathinfo($attachment->name, PATHINFO_EXTENSION);?> pull-left">
								<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=downloads&threadId='.$value->id)?>" target="_blank">
									<img src="https://cdn.uvdesk.com/uvdesk/images/1777810.jpg">
								</a>
								<span>Download all files as an archive</span>
							</div>
							<?php
						}
							?>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			</div>
		</div>
			<form enctype="multipart/form-data" id="replyForm" action="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewticket&id='.JRequest::getVar('id').'&task=viewticket.postReply',false)?>" method="POST" >
			<div class="edit" style="width: 100%;position: relative">
			<?php
			jimport( 'joomla.html.editor' );
			$editor = JFactory::getEditor();
	     	echo $editor->display('content','', '550', '400', '60', '20', false);
									?>
				<!-- <div>Add Attachments: <input name="replyfiles[]" type="file" multiple="true" ></div> -->

				<input class="btn btn-success" type="submit" id="ticketReply" style="display:none" value="Reply">
				<input type="hidden" name="ticketId" value="<?php echo $data->ticket->id ?>" />
				<input type="hidden" name="replyStatus" id="replyStatus">

			</div>
			<div class="replyButton">
			<!-- 	<select id="submit" name="submit" style="width:130px;">
					<option selected="true">Select Reply As</option>
					<?php
						foreach ($tickets->status as  $value) {
							?>
							<option value="<?php echo $value->id?>">Repy as <b><?php echo $value->name?></b></option>
							<?php
						}
					?>
				<option 
				</select> -->
				<div class="submitbutton">
					<div class="buttondevide">
						<div id="wk_drop_up">
						<ul>
						<?php
						foreach ($tickets->status as  $value) {
							?>
							<li data="<?php echo $value->id?>">as <b><?php echo $value->name?></b></li>
							
							<?php
						}
					?>						
					</ul>
						</div>
						<span class="icon-arrow-down-3"></span>
					</div>
					<input  class="btn btn-success" type="submit" id="ticketReply" value="Reply" >
				</div>
			</div>
		</form>
	</div>
		
</div>