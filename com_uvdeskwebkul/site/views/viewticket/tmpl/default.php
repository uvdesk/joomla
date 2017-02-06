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
$attachments='';
if(count($data->createThread->attachments)){
		$attachments="and attached ".count($data->createThread->attachments)." file(s)";
}
$usertype=$data->createThread->userType;
$ticketId=$data->ticket->id;
$document->addStyleSheet(JURI::base().'administrator/components/com_uvdeskwebkul/assets/css/uvdesk.css');
?>

<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
 <script type="text/javascript">
	jQuery(function(){
		var ticketId="<?php echo $data->ticket->id ?>";
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
	
});
</script>
<style type="text/css">
	.orderhistory_main_front *{
		/*font-family: Open Sans;*/
		box-sizing: border-box;
		font-size: 14px;
	}
	.edit .toggle-editor{
	  display: none!important;
	}
</style>
<div class="orderhistory_main_front">
	<div id="wk_block-container" class="span12">
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
						<span class="badge" data-toggle="tooltip" data-placement="top" data-original-title="Threads"><?php echo $data->ticketTotalThreads?> Replies</span>
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
									<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=downloads&attachmentId='.$attachment->id.'&fileformat='.$attachment->contentType.'&filename='.$attachment->name)?>" target="_blank">
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
				<div class="thread-pagination">
					<button class="btn btn-sm btn-info">
						<span class="pages" style="display: inline;">All Expanded
							<span class="count"></span>
							<!-- <span class="caret"></span> -->
						</span>
						<!-- <i class="fa fa-spinner fa-spin" style="display: none;"></i> -->
					</button>
					<span></span>
				</div>
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
								<a href="javascript:void(0)" id="thread<?php echo $data->ticket->id;?>" class="copy-thread-link"></a>
								<?php echo $value->user->detail->{$usert}->name." ".$value->threadType." ".$attachmentDetail;?> 				
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
										foreach ($value->attachments as $attachment) {	?>		
											<div class="attachment">
												<a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=downloads&attachmentId='.$attachment->id)?>" target="_blank">
													<i class="fa fa-file zip" title="" data-toggle="tooltip" data-original-title="<?php echo $attachment->name ?>">
														<span><?php echo pathinfo($attachment->name, PATHINFO_EXTENSION);?></span>
													</i>
												</a>		
											</div>
										<?php	}
									}
									?>
								</div>	
								<?php
								if (count($value->attachments)) {
								?>
								
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
		<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewticket&id='.JRequest::getVar('id').'&task=viewticket.postReply')?>" method="POST" >
			<div class="edit" style="width: 100%;position: relative">
			<?php
			jimport( 'joomla.html.editor' );
			$editor = JFactory::getEditor();
	     	echo $editor->display('content','', '550', '300', '60', '20', false);
									?>
				<input class="btn btn-success" type="submit" id="ticketReply" style="display:none" value="Reply">
				<input type="hidden" name="ticketId" value="<?php echo $data->ticket->id ?>" />
				<input type="hidden" name="replyStatus" id="replyStatus">

			</div>
				<div class="replyButton">
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