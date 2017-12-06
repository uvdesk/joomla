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
JHtml::_('jquery.framework');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.framework');
$document = JFactory::getDocument();
$model=$this->getModel();
$sizeHelper=new JFilesystemHelper();
$maxUpoloadSize=$sizeHelper->fileUploadMaxSize();
$fileUploadInMb=filter_var($maxUpoloadSize, FILTER_SANITIZE_NUMBER_INT);
$document->addStyleSheet(JURI::root().'administrator/components/com_uvdeskwebkul/assets/css/uvdesk.css');
$document->addStyleSheet(JURI::root().'administrator/components/com_uvdeskwebkul/assets/css/createticket.css');
$document->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
$document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css');
$document->addScript('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js');
$attachments='';
$tickets=json_decode($model->getTicketType());
$customer=$this->get('customer');
$customFields=json_decode($model->customFields());
$jInput=JFactory::getApplication()->input;

if (isset($tickets->error)&&$tickets->error=='access_denied') {
    JFactory::getApplication()->redirect($jInput->server->get('HTTP_REFERER', '', 'RAW'), JText::_('COM_UVDESKWEBKUL_ACCESS_DENIED'), 'error');
} else {
    ?>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
    <script type="text/javascript">
        var yourobject=(<?php echo json_encode($customFields)?>);        
        jQuery(function(){
            jQuery('#wkCreateTicketForm').on('submit',function(){
                if(jQuery('.wkselectpicker').val()==0){
                    swal ( "<?php echo JText::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" , "<?php echo JText::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED_SELECT_TICKET')?>" ,  "error" );                    
                    return false;
                }
                var subject=jQuery('#subject').val();
                subject=subject.trim();
                if (jQuery('#subject').val()==0) {
                    swal ( "<?php echo JText::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo JText::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED_SELECT_TICKET_SUBJECT')?>" ,  "error" );                    
                    return false;
                }
                var reply=jQuery('#reply').val();
                reply=reply.trim();
                if (jQuery('#reply').val()==0) {
                    swal ( "<?php echo JText::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo JText::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED_SELECT_TICKET_MESSAGE')?>" ,  "error" );                    
                    return false;
                }
            });
            jQuery('.wkselectpicker').on('change', function(){
                var selectType=jQuery(this).val();
                var data='';
                for (var key in yourobject) {
                    if (yourobject.hasOwnProperty(key) && yourobject[key].status==1&& (yourobject[key].agentType=='customer'||yourobject[key].agentType=='both')) {
                        for (var key1 in yourobject[key].customFieldsDependency) {
                            if(typeof(yourobject[key].customFieldsDependency[key1])!='undefined'&&yourobject[key].customFieldsDependency[key1].id==selectType){                            
                                var required='';
                                var sup='';
                                if (yourobject[key].required) {
                                    required=" required=true ";
                                    sup='<span class="wk_required">*</span>';
                                }
                                if(yourobject[key].value==null){
                                    yourobject[key].value='';
                                }
                                if (yourobject[key].fieldType=='radio') { 
                                    data+='<div class="wk_radio" ><label><strong>'+yourobject[key].name+sup+'</strong></label>';                  
                                    jQuery.each(yourobject[key].customFieldValues, function( key2, value2 ) {
                                        var checked="";
                                        if(key==0){
                                            checked= "checked='checked'";
                                        }                                
                                        data+="<div class='wk_radioWrapper'><input name='customFields["+yourobject[key].id+"]' id='"+value2.id+"' class='form-control' value='"+value2.id+"' "+checked+"  placeholder='"+yourobject[key].value+"' type='"+yourobject[key].fieldType+"' /><label for='"+value2.id+"'>"+value2.name+"</label></div>";
                                    });
                                    data+='</div>';
                                } else if (yourobject[key].fieldType=='textarea') {
                                    data+="<div class='wk_common_input'><label for='"+yourobject[key].id+"'><strong>"+yourobject[key].name+sup+"</strong></label><textarea name='customFields["+yourobject[key].id+"]' class='form-control' "+required+" placeholder='"+yourobject[key].value+"' id='"+yourobject[key].id+"' ></textarea></div>";
                                } else if (yourobject[key].fieldType=='select') {
                                    data+='<div class="wk_select" ><label><strong>'+yourobject[key].name+sup+'</strong></label><div class="wk_selectWrapper"><select name="customFields['+yourobject[key].id+']" '+required+'><option>Select option</option>';
                                    jQuery.each(yourobject[key].customFieldValues, function( key2, value2 ) {
                                        data+="<option  value='"+value2.id+"'>"+value2.name+"</option>";
                                    });
                                    data+='</select></div></div>';

                                } else if (yourobject[key].fieldType=='checkbox') {
                                    data+='<div class="wk_radio" ><label><strong>'+yourobject[key].name+sup+'</strong></label>';                  
                                    jQuery.each(yourobject[key].customFieldValues, function( key2, value2 ) {                                    
                                        data+="<div class='wk_radioWrapper'><input name='customFields["+yourobject[key].id+"]' id='"+value2.id+"' class='form-control' value='"+value2.id+"'   placeholder='"+yourobject[key].value+"' type='"+yourobject[key].fieldType+"' /><label for='"+value2.id+"'>"+value2.name+"</label></div>";
                                    });
                                    data+='</div>';
                                } else {
                                    data+="<div class='wk_common_input'><label for='"+yourobject[key].id+"'><strong>"+yourobject[key].name+sup+"</strong></label><input name='customFields["+yourobject[key].id+"]' id='"+yourobject[key].id+"' class='form-control' value='' "+required+" placeholder='"+yourobject[key].value+"' type='"+yourobject[key].fieldType+"' /></div>";
                                }
                            }
                        }
                    }
                }
                jQuery('.wk-custom-fields').html(data);
            });

            jQuery('.labelWidget span').on('click',function(){
                jQuery('.labelWidget .attachments:last-child').trigger('click');
            });
            jQuery('.labelWidget .attachments:last-child').on('change',function(){
                var numItems = jQuery('.wk_file_attach').length+1;
                
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
        .wk_block-container_main{
            float:none!important;
        }
    </style>
    <div class="orderhistory_main_front">
        <div id="wk_block-container" class="span12 wk_block-container_main">
            <div class="create_ticket_header"><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET');?></div>
            <div class="front_form">
                <form action="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=createticket&task=createticket.createTicket', false)?>" method="post" name="adminForm" id="wkCreateTicketForm" enctype="multipart/form-data" class="form-validate form-horizontal" >
                    <div novalidate="false" id="create-ticket-form">
                    <?php 
                    if (isset($customer->id)) {?>
                        <div class="" style="display: none">
                            <div class="form-group">
                                <label for="name" ><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_NAME')?><span class="wk_required">*</span></label>
                                <input id="name" name="name" required="required" placeholder="<?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_NAME')?>" value="<?php echo $customer->name?>" class="form-control" type="text">
                            </div>
                        </div> 
                        <div class="" style="display: none">
                            <div class="form-group">
                                <label for="from" ><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_EMAIL')?><span class="wk_required">*</span></label>
                                <input id="from" name="from" required="required" value="<?php echo $customer->email?>" placeholder="<?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_EMAIL')?>" class="form-control" type="email">
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                            <div class="">
                                <div class="form-group">
                                    <label for="name" ><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_NAME');?><span class="wk_required">*</span></label>
                                    <input id="name" name="name" required="required" placeholder="<?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_NAME');?>" class="form-control" type="text">
                                </div>
                            </div> 
                            <div class="">
                                <div class="form-group">
                                    <label for="from" ><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_EMAIL');?><span class="wk_required">*</span></label>
                                    <input id="from" name="from" required="required" placeholder="<?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_EMAIL');?>" class="form-control" type="email">
                                </div>
                            </div>
                        <?php 
                    } ?>
                        <div class="">
                            <div class="form-group ">
                                <label for="type" ><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_TYPE');?><span class="wk_required">*</span></label>
                                
                                <select id="type" name="type" data-role="tagsinput" class="wkselectpicker form-control" tabindex="-98">
                                    <option value="0" selected="selected"><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CHOOSE_TICKET_TYPE')?></option>
                                    <?php 
                                   
                                    foreach ($tickets->types as $value) {
                                        if ($value->isActive) {
                                            ?>
                                        <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                                        <?php

                                        }

                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group">
                            <label for="subject" ><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CHOOSE_TICKET_SUBJECT');?><span class="wk_required">*</span></label>
                            <input id="subject" name="subject" required="required" placeholder="<?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CHOOSE_TICKET_SUBJECT');?>" class="form-control" type="text">
                        <!-- </div> -->
                        </div>
                        <div class="">
                            <div class="form-group ">
                                <label for="reply" ><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CHOOSE_TICKET_MESSAGE');?><span class="wk_required">*</span></label>
                                <textarea id="reply" name="reply" required="required" placeholder="<?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CHOOSE_TICKET_MESSAGE');?>" data-iconlibrary="fa" data-height="250" class="form-control"></textarea>
                            </div>
                        </div>   
                        <div class="">
                            <div class="form-group ">
                                <div class="labelWidget">
                                    <div class="wk_file_attach1">
                                        <input class="attachments" multiple="multiple" name="ticketAttachments[]" type="file" />
                                    </div>
                                    <span class="icon-attachment"></span><span class="pointer" id="addFile"><?php echo JText::_('COM_UVDESKWEBKUL_ADD_ATTACHMENT');?></span>
                                    <div class=""><?php echo JText::_('COM_UVDESKWEBKUL_MAX_UPLOAD_SIZE');?>:<?php echo $fileUploadInMb ?>MB</div>         
                                </div>                           
                            </div>
                        </div>
                    <div class="wk-custom-fields">
                    <?php

                        ?>
                    </div>
                    <div class="">
                        <input type="submit" id="submit1" name="submit1" class="btn btn-md btn-info" value="<?php echo JText::_('COM_UVDESKWEBKUL_CREATE_TICKET')?>" />
                    </div>
                    <?php echo JHtml::_('form.token'); ?>
                    <input type="hidden" name="task" value="createticket.createTicket" />
                </form>
            </div>
        </div>
    </div>
<?php
} 
