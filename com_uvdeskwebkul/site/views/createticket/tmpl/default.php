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
JHtml::_('jquery.framework');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.framework');
$document = JFactory::getDocument();
$model=$this->getModel();
$document->addStyleSheet(JURI::root().'administrator/components/com_uvdeskwebkul/assets/css/uvdesk.css');
$document->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
$attachments='';
$tickets=json_decode($model->getTicketType());
$customer=$this->get('customer');
$customFields=json_decode($model->customFields());
?>
 <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
 <script type="text/javascript">
	jQuery(function(){
		jQuery('body .attach-file').on('click',function(){
			jQuery('#attachments').trigger('click');
		});
});
</script>
<style type="text/css">
	.orderhistory_main_front *{
		 font-size: 14px;
    	font-family: Open Sans;
	}
	.orderhistory_main_front .chzn-single{
		height: 32px;
	}
</style>
<div class="orderhistory_main_front">
	<div id="wk_block-container" class="span12">
		<div class="create_ticket_header">Create a Ticket</div>
		<div class="front_form">
			<form action="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=createticket&task=createticket.createTicket',false)?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate form-horizontal" >
			   	<div novalidate="false" id="create-ticket-form">
			   		<?php if(isset($customer->id)){?>
					   	<div class="" style="display: none">
					   		<div class="form-group required ">
					   			<label for="name" class="required">Customer Name</label>
				   				<input id="name" name="name" required="required" placeholder="Enter Name" value="<?php echo $customer->name?>" class="form-control" type="text">
			   				</div>
			   			</div> 
						<div class="" style="display: none">
					    	<div class="form-group required ">
					    		<label for="from" class="required">Your Email</label>
				    			<input id="from" name="from" required="required" value="<?php echo $customer->email?>" placeholder="Enter Your Email" class="form-control" type="email">
							</div>
						</div>
					<?php
					}else{?>
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
					<?php }
				?>
				    <div class="">
				    	<div class="form-group required ">
				    		<label for="type" class="required">Type</label>
				    		
							<select id="type" name="type" required="required" data-role="tagsinput" class="selectpicker form-control" tabindex="-98">
								<option value="" selected="selected">Choose query type</option>
								<?php 
								foreach ($tickets->types as $value) {
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
		    					<input id="attachments" name="ticketAttachments[]" infolabel="right" infolabeltext="Attach File" decoratefile="decorateFile" decoratecss="attach-file" enableremoveoption="enableRemoveOption" class="fileHide" type="file" />
		    					<label class="attach-file pointer"></label>
		    					<i class="fa fa-times remove-file pointer"></i>
							</div>
							<span class="label-right pointer" id="addFile">Attach File</span>
						</div>
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
							<label for="fororder Id<?php echo $value->id?>" style="float: left"><?php echo $value->name ?></label>
							<input name="customFields[<?php echo $value->id?>]" class="form-control" value="" id="fororder Id<?php echo $value->id?>" required="<?php echo $value->required?>" placeholder="<?php echo $value->value?>" type="<?php echo $value->fieldType?>">
						</div>
					</div>	
					<?php
					}
					?>							
				</div>
				<div class="">
			    	<input type="submit" id="submit1" name="submit1" class="btn btn-md btn-info" value="Create Ticket" />
				</div>
				<input id="token" name="token" value="<?php  ?>" type="hidden">
				<input type="hidden" name="task" value="createticket.createTicket" />
			</form>
		</div>
	</div>
</div>