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
$uvdeskCustomer=$model->getMember();
$data=json_decode($model->getData());
$jInput=JFactory::getApplication()->input;
$sizeHelper=new JFilesystemHelper();
$maxUpoloadSize=$sizeHelper->fileUploadMaxSize();
$fileUploadInMb=filter_var($maxUpoloadSize, FILTER_SANITIZE_NUMBER_INT);
if (isset($data->ticket->id)) {
    $tickets=json_decode($model->getTickets());
    $members=json_decode($model->getMembers());
    $thread=json_decode($model->getThread($data->ticket->id));
    $document->addStyleSheet(JURI::base().'components/com_uvdeskwebkul/assets/css/uvdesk.css');
    $document->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    $document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css'); 
    $document->addScript('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.js'); 
    $attachments='';
    if (count($data->createThread->attachments)) {
        $attachments=JText::_('COM_UVDESKWEBKUL_AND_ATTACHED').count($data->createThread->attachments).JText::_('COM_UVDESKWEBKUL_AND_ATTACHED_FILES');
    }
    $usertype=$data->createThread->userType;
    $ticketId=$data->ticket->id;
    $params= JComponentHelper::getParams('com_uvdeskwebkul');
    $apiKey=$params->get('tinymce');
?>
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=<?php echo $apiKey;?>"></script>
<style type="text/css">
.withoutSelection div[class^="mass"]{/*	position: relative;*/}
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
        jQuery('#wkPostReply').on('submit', function(){
            var editorData=jQuery('#wkEditor').val();
            editorData=editorData.trim();
            if(editorData.length==0){
                return false;
            }
        });
         jQuery('body').append('<div class="content-wrap"><img class="ajax-loader-img ajax-loader" style="" src="https://cdn.uvdesk.com/uvdesk/images/aa1b406.gif" alt=""></div>');
        jQuery('.wk_thread_pagination1').on('click',function(){
            var page=jQuery(this).val();
            jQuery.ajax({
                url:"index.php?option=com_uvdeskwebkul&task=viewticket.getThread",
                data:{
                    ticketId:'<?php echo $ticketId;?>',
                    page:page,
                },
                dataType:'json',
                type:'POST',
                success: function(result){
                    for (var i=0;i<Object.keys(result.threads).length;i++) {
                        var bodyString='';
                        if (typeof(result.threads[i].user.smallThumbnail)=='undefined'||result.threads[i].user.smallThumbnail==null) {
                            result.threads[i].user.smallThumbnail='https://cdn.uvdesk.com/uvdesk/images/bcf2f42.png'
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
                        bodyString='<div class="thread"><div class="thread-created-info"><span class="info"><a class="copy-thread-link" id="thread'+result.threads[i].id+'" href="javascript:void(0)"></a> '+customerName+' <?php echo JText::_('COM_UVDESKWEBKUL_REPLIED')?>  </span><span class="text-right date">'+result.threads[i].formatedCreatedAt+'</span></div><div class="thread-created-message"><div class="pull-left"><span class="round-tabs three"><img alt="" src="'+result.threads[i].user.smallThumbnail+'" class="border"></span></div><div class="thread-body"><div class="thread-info"><div class="thread-info-row first"><strong>'+customerName+'</strong></div><div class="thread-info-row"></div></div><div class="message reply agent"><div class="main-reply">'+result.threads[i].reply+'</div></div><div class="thread-attachments">';
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
                            jQuery('.wk_thread_pagination span').css({'border-bottom-color':'#EEE'});
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
        jQuery('#wkPostReply').on('submit',function(){
            var editorContent = tinyMCE.activeEditor.getContent();
            if (editorContent == ''){
                return false;
            }
        });
      
        jQuery('.labelWidget span').on('click',function(){
            jQuery('.labelWidget .attachments:last-child').trigger('click');
        });
        jQuery('.labelWidget .attachments:last-child').on('change',function(){
            var numItems = jQuery('.wk_file_attach').length+1;
        });
        jQuery('.buttondevide').on('click',function(){
            if(jQuery(this).hasClass('active')){
                jQuery(this).removeClass('active');
                jQuery('#wk_drop_up').css('display','none');
            }else{
                jQuery(this).addClass('active');
                jQuery('#wk_drop_up').css('display','block');
            }
        });
        jQuery('body').on('click',function(e){
            if(jQuery(e.target).hasClass('buttondevide')||jQuery(e.target).hasClass('icon-arrow-down-3')){
                 jQuery('#wk_drop_up').css('display','block');
            } else{
                jQuery('#wk_drop_up').css('display','none');
            }
        });
        jQuery('#wk_drop_up li').on('click',function(){
            jQuery('#replyStatus').val(jQuery(this).attr('data'));
            jQuery('#ticketReply').trigger('click');
        });
         tinymce.init({
    selector: "#wkEditor",
    mode : "textareas",
    theme: "modern",
    paste_data_images: true,
    height: 300,
    menubar:false,
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
        jQuery('body').append('<div class="content-wrap"><img class="ajax-loader-img ajax-loader" style="" src="https://cdn.uvdesk.com/uvdesk/images/aa1b406.gif" alt=""></div>');
        var ticketId="<?php echo $data->ticket->id ?>";
        jQuery.ajax({
            url:"index.php?option=com_uvdeskwebkul&task=viewtickets.apiCall",
            data:{
                    ticketId:ticketId
            },
            dataType:'json',
            type:'POST',
            beforeSend: function(){
                jQuery('.content-wrap').show();
            },
            complete: function(){
                jQuery('.content-wrap').hide();
            },
            success: function(result){
                
            }
        });
        jQuery('#ticketReply').on('click',function(){
            var editorContent = tinyMCE.activeEditor.getContent();
            if (editorContent == ''){
                alert("<?php echo JText::_('COM_UVDESKWEBKUL_YOUR_MESSAGE_IS_EMPTY')?>");
                return false;
            }
            jQuery.ajax({
                url:"index.php?option=com_uvdeskwebkul&task=viewticket.postReply",
                data:{
                        ticketId:"<?php echo $data->ticket->id ?>"
                },
                dataType:'json',
                type:'POST',
                beforeSend: function(){
                    jQuery('.content-wrap').show();
                },
                complete: function(){
                    jQuery('.content-wrap').hide();
                },
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
                beforeSend: function(){
                    jQuery('.content-wrap').show();
                },
                complete: function(){
                    jQuery('.content-wrap').hide();
                },
                success:function(data){
                    data=JSON.parse(data);
                    console.log(typeof(data.error)!='undefined'||data.error!=null);return;
                    if(typeof(data.error)!='undefined'||data.error!=null){
                        Joomla.renderMessages({'error': [data.error] });
                    } else{
                        location.reload(); 
                    }
                }
            });        
    });
    jQuery('.assignment').on('change',function(){
        if(jQuery(this).val()!=0){
            jQuery.ajax({
                url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updateTicketSingle"?>',
                type:"POST",
                data:{'apiurl':'ticket/'+ticketId+'/agent.json','selected':jQuery(this).val(),"forselect":"assignment",ticketId:ticketId},
                beforeSend: function(){
                    jQuery('.content-wrap').show();
                },
                complete: function(){
                    jQuery('.content-wrap').hide();
                },
                success:function(data){
                    data=JSON.parse(data);
                    if(typeof(data.error)!='undefined' ||data.error!=null){
                        Joomla.renderMessages({'error': [data.error] });
                    } else{
                        location.reload();
                    }
                }

            });
        }
        
    });
    jQuery('.withoutSelection .isStarred').on('click',function(){
            jQuery.ajax({
                url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updatePatch"?>',
                type:"POST",
                data:{'apiurl':'ticket/'+ticketId+'.json','selected':jQuery(this).val(),"forselect":"starred"},
                beforeSend: function(){
                    jQuery('.content-wrap').show();
                },
                complete: function(){
                    jQuery('.content-wrap').hide();
                },
                success:function(data){
                    Joomla.renderMessages({'success': [data] });
                    if(jQuery('.withoutSelection .isStarred i').hasClass('mark-star-yellow')){
                        jQuery('.withoutSelection .isStarred i').removeClass('mark-star-yellow');
                    }
                    else{
                        jQuery('.withoutSelection .isStarred i').addClass('mark-star-yellow');

                    }
                    var data=JSON.parse(data);
                    if(typeof(data.error)!='undefined' ||data.error!=null){
                        Joomla.renderMessages({'error': [data.error] });
                    } else{
                       Joomla.renderMessages({'success': [data.message] });
                    }
                    
                }
                ,error:function(error){
                }

            });
    })
    jQuery('.status').on('change',function(){
        if(jQuery(this).val()!=0){
            jQuery.ajax({
                url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updatePatch"?>',
                type:"POST",
                data:{'apiurl':'ticket/'+ticketId+'.json','selected':jQuery(this).val(),"forselect":"status"},
                beforeSend: function(){
                    jQuery('.content-wrap').show();
                },
                complete: function(){
                    jQuery('.content-wrap').hide();
                },
                success:function(data){
                   var data=JSON.parse(data);
                    if(typeof(data.error)!='undefined' ||data.error!=null){
                        Joomla.renderMessages({'error': [data.error] });
                    } else{
                        location.reload();
                    }
                }
            });
        }
    });
    jQuery('.group').on('change',function(){
        jQuery.ajax({
                url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updatePatch"?>',
                type:"POST",
                data:{'apiurl':'ticket/'+ticketId+'.json','selected':jQuery(this).val(),"forselect":"groups"},
                beforeSend: function(){
                    jQuery('.content-wrap').show();
                },
                complete: function(){
                    jQuery('.content-wrap').hide();
                },
                success:function(data){
                    var data=JSON.parse(data);
                    if(typeof(data.error)!='undefined' ||data.error!=null){
                        Joomla.renderMessages({'error': [data.error] });
                    } else{
                        location.reload();
                    }                    
                }

            });
    });
    jQuery('.priority').on('change',function(){
        jQuery.ajax({
                url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updatePatch"?>',
                type:"POST",
                data:{'apiurl':'ticket/'+ticketId+'.json','selected':jQuery(this).val(),"forselect":"priority"},
                beforeSend: function(){
                    jQuery('.content-wrap').show();
                },
                complete: function(){
                    jQuery('.content-wrap').hide();
                },
                success:function(data){
                   var data=JSON.parse(data);
                    if(typeof(data.error)!='undefined' ||data.error!=null){
                        Joomla.renderMessages({'error': [data.error] });
                    } else{
                        location.reload();
                    }
                    
                }

            });
        });
    jQuery('.label').on('change',function(){
        alert();
        jQuery.ajax({
                url:'<?php echo JURI::base()."index.php?option=com_uvdeskwebkul&view=viewticket&task=viewticket.updatePatch"?>',
                type:"POST",
                data:{'apiurl':'ticket/'+ticketId+'.json','selected':jQuery(this).val(),"forselect":"labeled"},
                beforeSend: function(){
                    jQuery('.content-wrap').show();
                },
                complete: function(){
                    jQuery('.content-wrap').hide();
                },
                success:function(data){
                    var data=JSON.parse(data);
                    if(typeof(data.error)!='undefined' ||data.error!=null){
                        Joomla.renderMessages({'error': [data.error] });
                    } else{
                        location.reload();
                    }
                                  
                }

            });
    });
});
jQuery(function(){
    jQuery('.wk_label_remove .label-name').text('<?php echo $jInput->get("label", '', 'STR'); ?>');
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
                label:"<?php echo JText::_('COM_UVDESKWEBKUL_STATUS_ALL')?>",
        },
        dataType:'json',
        type:'POST',
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
                labels='<li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets")?>"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_ALL')?><span class="badge badge-success">'+result.labels.predefind.all+'</span></a></li><li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets&new=1")?>"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_NEW')?><span class="badge badge-success">'+result.labels.predefind.new+'</span></a></li><li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets&unassigned=1")?>"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_UNASSIGNED')?><span class="badge badge-danger">'+result.labels.predefind.unassigned+'</span></a></li><li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets&mine=1")?>"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_MY_TICKETS')?><span class="badge badge-success">'+result.labels.predefind.mine+'</span></a></li><li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets&starred=1")?>"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_STARRED')?><span class="badge badge-success">'+result.labels.predefind.starred+'</span></a></li><li><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewtickets&trashed=1")?>"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_TRAHSED')?><span class="badge badge-success">'+result.labels.predefind.trashed+'</span></a></li>';
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
        }
    });
}
</script>
<div class="orderhistory_main_front">
    <div id="j-sidebar-container" class="span3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS')?></h4>
            </div>
            <div class="panel-body">
                <div class="ticket-label-block">
                    <ul class="list-block predefined-label-list">                        
                    </ul>
                    <span class="clearfix"></span>
                </div>
            </div>
        </div>
        <div class="wk_ticket_customfield">
            <?php        
            if (count($data->ticket->customFieldValues)) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CUSTOM_FIELDS')?></h4>
                    </div>
                    <?php
                    foreach ($data->ticket->customFieldValues as $value) {
                        ?>
                        <div class="panel-body">
                            <div class="custom-field">
                                <div><label><?php echo $value->ticketCustomFieldsValues->name?></label></div>
                                <?php 
                                if ($value->ticketCustomFieldsValues->fieldType=="select"||$value->ticketCustomFieldsValues->fieldType=="radio"||    $value->ticketCustomFieldsValues->fieldType=="checkbox") {
                                    foreach ($value->ticketCustomFieldsValues->customFieldValues as $cust) {
                                        $string = $value->value;
                                        if ($cust->id==preg_replace("/[^0-9]/", '', $string)) {
                                            echo $cust->name;
                                        }
                                    }
                                } elseif ($value->ticketCustomFieldsValues->fieldType=="file") {
                                    $fileCustom=json_decode($value->value);
                                    $fileInfo = pathinfo($fileCustom->path);
                                    echo '<a href="'.JRoute::_('index.php?option=com_uvdeskwebkul&view=downloads&attachmentId='.$fileCustom->id.'&fileformat=image/'.$fileInfo['extension'].'&filename=attachment', false).'target="_blank">'.$fileInfo['basename'].'</a>';
                                } else {
                                    echo str_replace('"', "", $value->value);
                                } ?>
                            </div>
                        </div>
                    <?php

                    } ?>
                </div>
            <?php
            } ?>
        </div>
        <div class="wk_ticket_detail">
            <table class="table">
                <tbody>
                    <tr><td><span class="wk_ticket_single_status"><strong><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_AGENT')?></strong></span></td><td><?php 
                    if (isset($data->ticket->agent->detail->agent->name)) {
                        echo $data->ticket->agent->detail->agent->name;
                    } else {
                        echo 'Unassigned';
                    }
                    ?></td></tr>
                    <tr><td class="wk_ticket_single_status"><strong><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_TYPE')?></strong></td><td><?php if(isset($data->ticket->type->name)) echo $data->ticket->type->name;?></td></tr>
                    <tr><td class="wk_ticket_single_status"><strong><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_STATUS')?></strong></td><td><?php echo $data->ticket->status->name;?></td></tr>
                    <tr><td class="wk_ticket_single_status"><strong><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_PRIORITY')?></strong></td><td><?php echo $data->ticket->priority->name;?></td></tr>
                    <tr><td class="wk_ticket_single_status"><strong><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_TOTAL_THREADS')?></strong></td><td><?php echo $data->ticketTotalThreads;?></td></tr>
                </tbody>                
            </table>
        </div>
    </div>
    <div id="wk_block-container" class="span9">
        <div id="sticky-header-sticky-wrapper" class="sticky-wrapper" style="height: 54px;">
            <div class="withoutSelection">
                <div class="isStarred" style="float: left;margin-right: 5px;">
                    <?php
                    if (isset($data->ticket->isStarred)) {
                        ?>
                        <a href="javascript:void(0)" class="mark-star">
                            <i class="fa fa-star mark-star-yellow"></i>
                        </a>
                        <?php

                    } else {
                        ?>
                        <a href="javascript:void(0)" class="mark-star">
                            <i class="fa fa-star"></i>
                        </a>
                        <?php

                    } ?>
                </div>
                <div class="source" style="float: left;margin-right: 5px;">
                    <?php
                    if ($data->ticket->source=="website") {
                        ?>
                        <a href="javascript:void(0)" class="mark-star">
                            <i class="fa fa-television source" aria-hidden="true"></i>
                        </a>
                    <?php

                    } elseif ($data->ticket->source=="api") {
                        ?>
                        <a href="javascript:void(0)" class="mark-star">
                            <i class="fa fa-code" aria-hidden="true"></i>
                        </a>
                        <?php

                    } else {
                        ?>
                        <a href="javascript:void(0)" class="mark-star">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </a>
                        <?php

                    } ?>
                </div>
                <div class="mass-agents agents" style="float: left;margin-right: 5px;">
                    <!-- <span title="Agent" class="wk_user"></span> -->
                    <select class="assignment chosen" style="width:130px">
                    <option value="0"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_AGENT')?></option>
                        <?php 
                        foreach ($members->users as $value) {
                            ?>
                                <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                            <?php 
                        } ?>
                    </select>
                </div>
                <div class="mass-status" style="float: left;margin-right: 5px;">
                    <!-- <span title="status" class="wk_status"></span> -->
                    <select class="status" style="width:130px">
                    <option value="0"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_STATUS')?></option>
                        <?php 
                        foreach ($tickets->status as $value) {
                            ?>
                                <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                            <?php 
                        } ?>
                    </select>
                </div>
                <div class="mass-group" style="float: left;margin-right: 5px;">
                    <!-- <span title="groups" class="wk_group"></span> -->
                    <select class="group" style="width:130px">
                        <option value="0"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_GROUP')?></option>
                        <?php 
                        foreach ($tickets->group as $value) {
                            ?>
                                <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                            <?php 
                        } ?>
                    </select>
                </div>
                <div class="mass-priority" style="float: left;margin-right: 5px;">
                    <!-- <span title="Priority" class="wk_priority"></span> -->
                    <select class="priority" style="width:130px">
                            <option value="0"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_PRIORITY')?></option>
                        <?php 
                        foreach ($tickets->priority as $value) {
                            ?>
                                <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                            <?php 
                        } ?>
                    </select>
                </div>                 
                <div class="mass-delete" style="float: left;margin-right: 5px;">
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
            </div>
        </div>
        <div class="ticket-message-block">
            <div class="ticket-create">
                <div class="thread-created-info">
                    <div class="thread-created-info_blocks" >
                        <span><strong><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_CREATED_BY')?> -</strong></span>
                        <!--<div><?php echo $data->ticket->customer->detail->customer->name;?></div>-->
                        <span><?php echo $data->createThread->fullname;?></span>
                    </div>
                    <div class="thread-created-info_blocks" >
                        <span><strong><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_CREATED_AT')?> -</strong></span>
                        <span><?php echo $data->ticket->formatedCreatedAt?></span>
                    </div>                                    
                </div>
                <hr/>  
                <?php
                    // echo "<pre>";
                    // print_r($data);die;
                ?>
                <div class="">
                    <?php
                    if (!isset($data->ticket->customer->smallThumbnail)) {
                        $thumbUrl="https://cdn.uvdesk.com/uvdesk/images/bcf2f42.png";
                    } else {
                        $thumbUrl=$data->ticket->customer->smallThumbnail;
                    } ?>
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
                                }
                                ?></strong>
                                <span style="display: inline-block; vertical-align: middle; margin-top: 2px; color: #314876;word-break: break-all;">
                                    <i class="fa fa-chevron-left"></i><?php 
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
                            /*echo "<pre>";
                            print_r($data->createThread->attachments);die;*/
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
                                <span class="count"><?php echo $thread->pagination->totalCount-$thread->pagination->numItemsPerPage ?></span><span><?php echo JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_MORE_THREADS');?></span>
                                <!--<span class="caret"></span> -->
                            </span>
                            <i class="fa fa-spinner fa-spin" style="display: none;"></i> 
                        </button></div>
                        <span></span>                    
                <?php
                }
                ?>
                </div>
                <?php 

                foreach (array_reverse($thread->threads) as $key => $value) {
                    $attachmentDetail='';
                    $usert=$value->userType;
                    if (count($value->attachments)) {
                        $attachmentDetail=JText::_('COM_UVDESKWEBKUL_AND_ATTACHED').count($value->attachments).JText::_('COM_UVDESKWEBKUL_AND_ATTACHED_FILES');
                    } ?>
                    <div class="thread">
                        <div class="thread-created-info">
                            <span class="info">
                                <a href="javascript:void(0)" id="thread<?php echo $data->ticket->id; ?>" class="copy-thread-link"></a>
                                <?php 
                                if (isset($value->user->detail->{$usert})) {
                                    echo $value->user->detail->{$usert}->name;
                                }
                                echo JText::_('COM_UVDESKWEBKUL_REPLIED')." ".$attachmentDetail; ?>
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
                                        <strong>
                                        <?php
                                        if (isset($value->user->detail->{$usert})) {
                                            echo $value->user->detail->{$usert}->name;
                                        } ?>
                                        </strong>                                    
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
        <form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewticket&id='.$jInput->get('id', 0, 'INT').'&task=viewticket.postReply')?>" method="POST" id="wkPostReply" >
            <div class="edit" style="width: calc(100% - 20px);position: relative;margin:0 auto;">            
                <textarea id="wkEditor" required="true" name="content">
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
                <input name="image" type="file" id="wk_upload" class="hidden" onchange="">
                <input type="hidden" name="ticketId" value="<?php echo $data->ticket->id ?>" />
                <input type="hidden" name="replyStatus" id="replyStatus">
            </div>
            <div class="replyButton">
                <div class="submitbutton">
                    <!--<div class="buttondevide">
                        <span class="icon-arrow-down-3"></span>
                    </div>-->                    
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
                                } ?>
                                </ul>
                            </div>
                            <span class="icon-arrow-down-3" style="width: 25px;text-align:center;"></span>
                        </div>
                        <input  class="btn btn-success" type="submit" id="ticketReply" value="<?php echo JText::_('COM_UVDESKWEBKUL_VIEW_TICKET_REPLY')?>" />
                    </div>
                </div>
                </div>
            </div>
        </form>
        <?php
        } else {
            echo "<h3 class='alert alert-danger'>Please register ".JFactory::getUser()->email." as Customer in UvDesk</h3>";
        }
        ?>
    </div>
</div>
<?php
}
?>