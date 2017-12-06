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
JHtml::_('bootstrap.framework');
$document = JFactory::getDocument();
$model=$this->getModel();
$data=json_decode($model->getData());
$thread=json_decode($model->getThread($data->ticket->id));
$sizeHelper=new JFilesystemHelper();
$maxUpoloadSize=$sizeHelper->fileUploadMaxSize();
$fileUploadInMb=filter_var($maxUpoloadSize, FILTER_SANITIZE_NUMBER_INT);
$uvdeskCustomer=$model->getMember();
if (isset($data->ticket->customer->email)&&$data->ticket->customer->email==JFactory::getUser()->email) {
    $attachments='';
    if (count($data->createThread->attachments)) {
        $attachments="and attached ".count($data->createThread->attachments)." file(s)";
    }
    $usertype=$data->createThread->userType;
    $ticketId=$data->ticket->id;
    $document->addStyleSheet(JURI::base().'administrator/components/com_uvdeskwebkul/assets/css/uvdesk.css');
    $document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css');
    $document->addScript('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js');
    $params= JComponentHelper::getParams('com_uvdeskwebkul');
    $apiKey=$params->get('tinymce'); ?>
    <style>
        .wkViewAllticket{
            overflow-x:auto;
        }
    </style>
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=<?php echo $apiKey; ?>"></script>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
    <script>
    tinymce.init({
        selector: "#wkEditor",
        mode : "textareas",
        theme: "modern",
        paste_data_images: true,
        height: 300,
        menubar:false,
        auto_focus :true,
        plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | forecolor backcolor | bullist numlist outdent indent | link image",
        toolbar2:"",
    // toolbar1: "insertfile undo redo |bold italic | bold | forecolor backcolor | numlist bullist |link image",
        image_advtab: true,
        file_picker_callback: function(callback, value, meta) {
        if (meta.filetype == 'image') {
            jQuery('#wk_upload').trigger('click');
            jQuery('#wk_upload').on('change', function() {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                callback(e.target.result, {
                alt: ''
                });
            };
            reader.readAsDataURL(file);
            });
        }
        },
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        },
        templates: [{
        title: 'Test template 1',
        content: 'Test 1'
        }, {
        title: 'Test template 2',
        content: 'Test 2'
        }]
    });
    tinymce.triggerSave();
    </script>
    <script type="text/javascript">        
        jQuery(function(){
            jQuery('#wk_postReply').on('submit', function(){
                var content=jQuery('#wkEditor').val();
                content=content.trim();
                if(content.length==0){
                     swal ( "<?php echo JText::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_POST_REPLY')?>" ,  "error" );
                    return false;
                }
            });
            jQuery('body').append('<div class="content-wrap"><img class="ajax-loader-img ajax-loader" style="" src="https://cdn.uvdesk.com/uvdesk/images/aa1b406.gif" alt=""></div>');
            jQuery('.wk_thread_pagination1').on('click',function(){
                var page=jQuery(this).val();                              
                jQuery.ajax({
                    url:"index.php?option=com_uvdeskwebkul&task=viewticket.getThread",
                    data:{
                        ticketId:'<?php echo $ticketId; ?>',
                        page:page,
                    },
                    dataType:'json',
                    method:'POST',
                    success: function(result){
                        for (var i=0;i<Object.keys(result.threads).length;i++) {
                            var bodyString='';
                            console.log(result.threads[i]);
                            if (typeof(result.threads[i].smallThumbnail)=='undefined'||result.threads[i].smallThumbnail==null) {
                                result.threads[i].smallThumbnail='https://cdn.uvdesk.com/uvdesk/images/bcf2f42.png'
                            }
                            if (result.threads[i].user.smallThumbnail!=null) {
                                result.threads[i].smallThumbnail=result.threads[i].user.smallThumbnail;
                            }
                            var customerName='';
                            if(typeof(result.threads[i].user.detail.customer)=='undefined'||result.threads[i].user.detail.customer.name==null){
                                customerName=result.threads[i].user.detail.agent.name;
                            } else {
                                customerName=result.threads[i].user.detail.customer.name;
                            }
                            bodyString='<div class="thread"><div class="thread-created-info"><span class="info"><a class="copy-thread-link" id="thread'+result.threads[i].id+'" href="javascript:void(0)"></a> '+customerName+' <?php echo JText::_('COM_UVDESKWEBKUL_REPLIED')?>  </span><span class="text-right date">'+result.threads[i].formatedCreatedAt+'</span></div><div class="thread-created-message"><div class="pull-left"><span class="round-tabs three"><img alt="" src="'+result.threads[i].smallThumbnail+'" class="border"></span></div><div class="thread-body"><div class="thread-info"><div class="thread-info-row first"><strong>'+customerName+'</strong></div><div class="thread-info-row"></div></div><div class="message reply agent"><div class="main-reply">'+result.threads[i].reply+'</div></div><div class="thread-attachments">';
                            if(Object.keys(result.threads[i].attachments).length){
                                for (var j=0;j<Object.keys(result.threads[i].attachments).length;j++) {
                                bodyString+='<div class="attachment"><a target="_blank" href="/JoomlaUvdesk/index.php/component/uvdeskwebkul/downloads.html?attachmentId='+result.threads[i].attachments[j].id+'&amp;fileformat='+result.threads[i].attachments[j].contentType+'&amp;filename='+result.threads[i].attachments[j].name+'"><i class="fa fa-file zip"></i><span>'+result.threads[i].attachments[j].name.split('.').pop()+'</span></a></div>';
                                }
                            }
                            bodyString+='</div></div></div></div>';
                            jQuery('.thread-pagination').after(bodyString);
                            jQuery('.wk_thread_pagination1').val(parseInt(result.pagination.current)+1);
                            if(result.pagination.current==result.pagination.last){
                                jQuery('.thread-pagination').empty();
                            } else if((result.pagination.totalCount-parseInt(result.pagination.current)*10)>0) {
                                jQuery('.wk_thread_pagination1 .count').text(result.pagination.totalCount-parseInt(result.pagination.current)*10);
                            }
                        }                          
                        
                    },
                    beforeSend: function(){
                        jQuery('.content-wrap').show();
                    },
                    complete: function(){
                        jQuery('.content-wrap').hide();
                    },
                    error:function(xhr, ajaxOptions, thrownError){
                        
                    }
                });
            });
            jQuery('.labelWidget span').on('click',function(){
                jQuery('.labelWidget .attachments:last-child').trigger('click');
            });
            jQuery('.labelWidget .attachments:last-child').on('change',function(){
                var numItems = jQuery('.wk_file_attach').length+1;
            });
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
            // Prevent jQuery UI dialog from blocking focusin
    jQuery(document).on('focusin', function(e) {
        if (jQuery(e.target).closest(".mce-window, .moxman-window").length) {
            e.stopImmediatePropagation();
        }
        
    });
    //location.href = "<?php echo JURI::current()?> #thread-pagination";
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
        .wk_labelWidget{
            margin:20px;
        }
        .orderhistory_main_front{
            overflow:hidden;
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
                            <span class="badge priority" data-toggle="tooltip" data-placement="top" data-original-title="Priority"><?php echo "Priority - ".$data->ticket->priority->name; ?>
                            </span>
                            <span class="badge badge-success status" data-toggle="tooltip" data-placement="top" data-original-title="Status"><?php echo "Status - ".$data->ticket->status->name?></span>
                            <span class="badge type" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_TYPE')?>"><?php echo JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_TYPE')." - ".$data->ticket->type->name?></span>
                            <span class="badge" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_THREADS')?>"><?php echo JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_THREADS')." - ".$data->ticketTotalThreads?> Replies</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ticket-message-block">
                <div class="ticket-create">
                    <div class="thread-created-info">
                        <div class="thread-created-info_blocks" >
                        <span><strong><?php echo JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_CREATED_BY');?> -</strong></span>
                        <span><?php echo $data->createThread->fullname;?></span>
                    </div>
                    <div class="thread-created-info_blocks" >
                        <span><strong><?php echo JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_CREATED_AT');?> -</strong></span>
                        <span><?php echo $data->ticket->formatedCreatedAt?></span>
                    </div> 
                    
                </div>
                <hr/>
                    <div class="">
                    <?php
                    if (!isset($data->ticket->customer->smallThumbnail)) {
                        $thumbUrl="https://cdn.uvdesk.com/uvdesk/images/bcf2f42.png";
                    } else {
                        $thumbUrl=$data->ticket->customer->smallThumbnail;
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
                                    <strong><?php 
                                    if (isset($data->ticket->customer)) {
                                        echo $data->ticket->customer->detail->customer->name;
                                    }?></strong>
                                    <span style="display: inline-block; vertical-align: middle; margin-top: 2px; color: #314876;word-break: break-all;">
                                        <i class="fa fa-chevron-left"></i>
                                        <?php 
                                        if (isset($data->ticket->customer)) {
                                            echo $data->ticket->customer->email;
                                        }?>
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
                            if (count($data->createThread->attachments)) {
                                foreach ($data->createThread->attachments as $attachment) {
                                    ?>
                                    <div class="attachment">
                                        <a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=downloads&attachmentId='.$attachment->id.'&fileformat='.$attachment->contentType.'&filename='.$attachment->name)?>" target="_blank">
                                            <i class="fa fa-file zip"></i>
                                            <span><?php echo pathinfo($attachment->name, PATHINFO_EXTENSION); ?></span>
                                        </a>
                                    </div>
                                <?php

                                }
                            } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="horizontal-row"></div>
                <div class="ticket-thread">
                    <div class="thread-pagination">
                    <?php
                    if ($thread->pagination->pageCount>1) {
                        ?>
                        <div class="wk_thread_pagination"><button class="btn btn-sm btn-info wk_thread_pagination1" value="2">
                            <span class="pages" style="display: inline;">
                                <span class="count"><?php echo $thread->pagination->totalCount-$thread->pagination->numItemsPerPage ?></span><span><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_MORE_THREAD')?></span>
                                <!--<span class="caret"></span> -->
                            </span>
                            <i class="fa fa-spinner fa-spin" style="display: none;"></i> 
                        </button></div>
                        <span></span>
                    <?php

                    } ?>
                    </div>
                    <?php
                    foreach (array_reverse($thread->threads) as $key => $value) {
                        $attachmentDetail='';
                        $usert=$value->userType;
                        if (count($value->attachments)) {
                            $attachmentDetail=" and attached ".count($value->attachments)." file(s)";
                        } ?>
                        <div class="thread">
                            <div class="thread-created-info">
                                <span class="info">
                                    <a href="javascript:void(0)" id="thread<?php echo $data->ticket->id; ?>" class="copy-thread-link"></a>
                                    <?php echo $value->user->detail->{$usert}->name." ".JText::_('COM_UVDESKWEBKUL_REPLIED')." ".$attachmentDetail; ?>
                                </span>
                                <span class="text-right date"><?php echo $value->formatedCreatedAt?></span>
                            </div>
                            <?php
                            if (!isset($value->user->smallThumbnail)) {
                                $customerImgUrl="https://cdn.uvdesk.com/uvdesk/images/bcf2f42.png";
                            } else {
                                $customerImgUrl= $value->user->smallThumbnail;
                            }
                            ?>
                            <div class="thread-created-message">
                                <div class="pull-left">
                                    <span class="round-tabs three">
                                        <img class="border" src="<?php echo $customerImgUrl?>" alt="">
                                    </span>
                                
                                </div>
                                <div class="thread-body">
                                    <div class="thread-info">
                                        <div class="thread-info-row first">
                                            <strong><?php echo $value->user->detail->{$usert}->name?></strong>                                    
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
                                        if (count($value->attachments)) {
                                            foreach ($value->attachments as $attachment) {
                                                ?>
                                                <div class="attachment">
                                                    <a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=downloads&attachmentId='.$attachment->id)?>" target="_blank">
                                                        <i class="fa fa-file zip" title="" data-toggle="tooltip" data-original-title="<?php echo $attachment->name ?>">
                                                            <span><?php echo pathinfo($attachment->name, PATHINFO_EXTENSION); ?></span>
                                                        </i>
                                                    </a>
                                                </div>
                                            <?php	
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php

                    } ?>
                </div>
            </div>
            <?php
            if (isset($uvdeskCustomer->customers[0]->email)) {
                if (isset($uvdeskCustomer->customers[0]->smallThumbnail)) {
                    $replyThumbnail=$uvdeskCustomer->customers[0]->smallThumbnail;
                } else {
                    $replyThumbnail="https://cdn.uvdesk.com/uvdesk/images/bcf2f42.png";
                }

                ?>
            <div class="thread-created-message thread-created-message-reply">
                <div class="pull-left">
                    <span class="round-tabs three">
                        <img alt="" src="<?php echo $replyThumbnail;?>" class="border">
                    </span>                
                </div>
                <div class="thread-body">
                    <div class="thread-info">
                        <div class="thread-info-row first">
                            <strong><?php echo $uvdeskCustomer->customers[0]->name;?></strong> 
                            <span style="display: inline-block; vertical-align: middle; margin-top: 2px; color: #314876;word-break: break-all;">
                            <i class="fa fa-chevron-left"></i><?php  echo $uvdeskCustomer->customers[0]->email;?>
                            <i class="fa fa-chevron-right"></i>
                            </span>                                   
                        </div>
                        <div class="thread-info-row"></div>
                    </div>
                </div>
            </div>
            <form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewticket&id='.JFactory::getApplication()->input->get('id').'&task=viewticket.postReply', false)?>" method="POST" id="wk_postReply">
                <div class="edit" style="width: calc(100% - 20px);position: relative;margin:0 auto;">            
                    <textarea id="wkEditor" name="content">
                    </textarea>
                    <div class="form-group wk_labelWidget ">
                        <div class="labelWidget">
                            <div class="wk_file_attach1">
                                <input class="attachments" multiple="multiple" name="replyAttachments[]" type="file" />
                            </div>                            
                            <span class="icon-attachment"></span><span class="pointer" id="addFile"><?php echo JText::_('COM_UVDESKWEBKUL_ADD_ATTACHMENT')?></span>
                            <div class=""><?php echo JText::_('COM_UVDESKWEBKUL_MAX_UPLOAD_SIZE')?>:<?php echo $fileUploadInMb ?>MB</div>                      
                        </div>                    
                    </div>
                    <input name="image" type="file" id="wk_upload" style="display:none;" onchange="">
                    <input class="btn btn-success" type="submit" id="ticketReply" style="display:none" value="<?php echo JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_REPLY')?>">
                    <input type="hidden" name="ticketId" value="<?php echo $data->ticket->id ?>" />
                    <input type="hidden" name="replyStatus" id="replyStatus">
                </div>
                <div class="replyButton">
                    <div class="submitbutton">
                        <!--<div class="buttondevide">
                            <span class="icon-arrow-down-3"></span>
                        </div>-->
                        <input  class="btn btn-success" type="submit" id="ticketReply" value="<?php echo JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_REPLY')?>" >
                    </div>
                </div>
            </form>
            <?php

            }?>
        </div>
    </div>
    <?php
} else {
    JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets'), 'You are not authorised to view this resource', 'error');
}