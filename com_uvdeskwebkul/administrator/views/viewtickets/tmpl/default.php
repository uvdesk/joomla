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
JHtml::_('dropdown.init');
JHtml::_('script', 'system/core.js', false, true);
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
$document = JFactory::getDocument();
$jInput=JFactory::getApplication()->input;
$sizeHelper=new JFilesystemHelper();
$maxUpoloadSize=$sizeHelper->fileUploadMaxSize();
$fileUploadInMb=filter_var($maxUpoloadSize, FILTER_SANITIZE_NUMBER_INT);
$label='';
$new=$jInput->get('new', 0, 'INT');
$unassigned=$jInput->get('unassigned', 0, 'INT');
$mine=$jInput->get('mine', 0, 'INT');
$starred=$jInput->get('starred', 0, 'INT');
$trashed=$jInput->get('trashed', 0, 'INT');
if ($new==1) {
    $label="new=1";
} else if ($unassigned==1) {
    $label="unassigned=1";
} else if ($mine==1) {
    $label="mine=1";
} else if ($starred==1) {
    $label="starred=1";
} else if ($trashed==1) {
    $label="trashed=1";
}
?>
<style type="text/css">
    #wkTabs li{
        width: 16.56%;
        float: left;
    }
    #wkTabContent{
        overflow-y:auto!important;
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
    .wk_radioWrapper{
        input lable{
            display:block;
            float:left;
        }
    }
    .wkagentimg{
        float:left;
    }
</style>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">  
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
$document->addStyleSheet(JURI::base().'components/com_uvdeskwebkul/assets/css/uvdesk.css');
$document->addStyleSheet(JURI::root().'administrator/components/com_uvdeskwebkul/assets/css/createticket.css');
$document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css');
$document->addScript('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js');
$inputCookie  = JFactory::getApplication()->input->cookie;
$status=$inputCookie->get('globalTicketStatus', 1, 'INT');
//$status=$jInput->get('status', 1, "INT");

$model=$this->getModel();
$uvDeskCutomer=$model->getMember();
$members=json_decode($model->getMembers());
if (isset($members->users)) {
    $members=$members->users;
    $tickets=json_decode($model->getTickets());
    $customFields=json_decode($model->customFields());
    $membersArray=array();
    if ($status==2) {
        $sname="Pending";
    } elseif ($status==3) {
        $sname="Resolved";
    } elseif ($status==4) {
        $sname="Closed";
    } elseif ($status==6) {
        $sname="Spam";
    } elseif ($status==5) {
        $sname="Answered";
    } else {
        $sname="Open";
    }
    ?>
    <script type="text/javascript">
        jQuery(function(){
            jQuery('#create-ticket-form').on('submit',function(){
                var name=jQuery('#name').val();
                name=name.trim();
                if (jQuery('#name').val()==0) {
                    swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_NAME_IS_REQUIRED')?>" ,  "error" );                    
                    return false;
                }
                if(jQuery('.wkselectpicker').val()==0){
                    swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_SELECT_TICKET_TYPE')?>" ,  "error" );                    
                    return false;
                }
                var subject=jQuery('#subject').val();
                subject=subject.trim();
                if (jQuery('#subject').val()==0) {
                    swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_SUBJECT_IS_REQUIRED')?>" ,  "error" );                    
                    return false;
                }
                var reply=jQuery('#reply').val();
                reply=reply.trim();
                if (jQuery('#reply').val()==0) {
                    swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_MESSAGE_IS_REQUIRED')?>" ,  "error" );                    
                    return false;
                }
            });
        saveResult='';
        jQuery('.ticket-table').on('click','.fa-star', function(){
            var ticketId=jQuery(this).attr('data');
            jQuery.ajax({
                url:"<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&task=viewticket.updatePatch', false);?>",
                type:'POST',
                data:{forselect:"starred",apiurl:"ticket/"+ticketId+".json",ticketId:ticketId,globalStatus:globalStatus},
                beforeSend: function(){
                    jQuery('.content-wrap').show();
                },
                complete: function(){                                   
                    jQuery('.content-wrap').hide();
                },
                success:function(data){
                    data=JSON.parse(data);
                    var totalStarred=parseInt(jQuery('.wk_starrer').text());
                    if (jQuery('.ticket-table .fa-star[data="'+data.id+'"]').hasClass('mark-star-gray')) {
                        jQuery('.ticket-table .fa-star[data='+data.id+']').removeClass('mark-star-gray');
                        jQuery('.ticket-table .fa-star[data='+data.id+']').addClass('mark-star-yellow');
                        jQuery('.wk_starrer').text(totalStarred+1);
                    } else {                       
                        jQuery('.ticket-table .fa-star[data='+data.id+']').removeClass('mark-star-yellow');
                        jQuery('.ticket-table .fa-star[data='+data.id+']').addClass('mark-star-gray');
                         jQuery('.wk_starrer').text(totalStarred-1);
                        
                    }
                
                }
            });
        });
       
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
                        data:{'apiurl':'tickets/trash.json','params':selected,"forselect":"deleted",globalStatus:globalStatus},
                        beforeSend: function(){
                            jQuery('.content-wrap').show();
                        },
                        complete: function(){                                   
                            jQuery('.content-wrap').hide();
                        },
                        success:function(data){
                            location.reload();                            
                        }

                    });
                }
                else{
                    swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_SELECT_ATLEASE_ONE_TICKET')?>" ,  "error" );                    
                }
            });
            jQuery('.deleteMultipleForever').on('click',function(){
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
                        data:{'apiurl':'tickets/trash.json','params':selected,"forselect":"deleted",'globalStatus':globalStatus,'deleteForever':'1'},
                        beforeSend: function(){
                            jQuery('.content-wrap').show();
                        },
                        complete: function(){                                   
                            jQuery('.content-wrap').hide();
                        },
                        success:function(data){
                                location.reload();                           
                        }

                    });
                }
                else{
                    swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_SELECT_ATLEASE_ONE_TICKET')?>" ,  "error" );
                }
            });
             jQuery('.restoreMultipleTicket').on('click',function(){
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
                        data:{'apiurl':'tickets/restore.json','params':selected,"forselect":"restore",'globalStatus':globalStatus},
                        beforeSend: function(){
                            jQuery('.content-wrap').show();
                        },
                        complete: function(){                                   
                            jQuery('.content-wrap').hide();
                        },
                        success:function(data){
                                location.reload();                           
                        }

                    });
                }
                else{
                    swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_SELECT_ATLEASE_ONE_TICKET')?>" ,  "error" );
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
                        data:{'apiurl':'tickets/agent.json ','params':selected,'selected':jQuery(this).val(),"forselect":"assignment",globalStatus:globalStatus},
                        beforeSend: function(){
                            jQuery('.content-wrap').show();
                        },
                        complete: function(){                                   
                            jQuery('.content-wrap').hide();
                        },
                        success:function(data){
                                location.reload();
                          
                        }

                    });
                }
                else{
                    swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_SELECT_ATLEASE_ONE_TICKET')?>" ,  "error" );
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
                        data:{'apiurl':'tickets/status.json','params':selected,'selected':jQuery(this).val(),"forselect":"status",globalStatus:globalStatus},
                        beforeSend: function(){
                            jQuery('.content-wrap').show();
                        },
                        complete: function(){                                   
                            jQuery('.content-wrap').hide();
                        },
                        success:function(data){
                                location.reload();
                        }

                    });
                }
                else{

                    swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_SELECT_ATLEASE_ONE_TICKET')?>" ,  "error" );
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
                        data:{'apiurl':'tickets/group.json','params':selected,'selected':jQuery(this).val(),"forselect":"groups",globalStatus:globalStatus},
                        beforeSend: function(){
                            jQuery('.content-wrap').show();
                        },
                        complete: function(){                                   
                            jQuery('.content-wrap').hide();
                        },
                        success:function(data){
                                location.reload();
                        }

                    });
                }
                else{
                    swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_SELECT_ATLEASE_ONE_TICKET')?>" ,  "error" );
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
                        data:{'apiurl':'tickets/priority.json','params':selected,'selected':jQuery(this).val(),"forselect":"priority",globalStatus:globalStatus},
                        beforeSend: function(){
                            jQuery('.content-wrap').show();
                        },
                        complete: function(){                                   
                            jQuery('.content-wrap').hide();
                        },
                        success:function(data){
                                location.reload();
                        }

                    });
                }
                else{
                     swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_SELECT_ATLEASE_ONE_TICKET')?>" ,  "error" );
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
                        data:{'apiurl':'tickets/label.json','params':selected,'selected':jQuery(this).val(),"forselect":"label",globalStatus:globalStatus},
                        beforeSend: function(){
                            jQuery('.content-wrap').show();
                        },
                        complete: function(){                                   
                            jQuery('.content-wrap').hide();
                        },
                        success:function(data){
                            location.reload();
                           
                        }

                    });
                }
                else{
                     swal ( "<?php echo Jtext::_('COM_UVDESKWEBKUL_ERROR_TYPE_REQUIRED')?>" ,  "<?php echo Jtext::_('COM_UVDESKWEBKUL_VIEW_TICKETS_SELECT_ATLEASE_ONE_TICKET')?>" ,  "error" );
                    jQuery('.assignment').prop('selectedIndex',0);
                }
            });
        });
        jQuery(function(){
            jQuery('body').append('<div class="content-wrap"><img class="ajax-loader-img ajax-loader" style="" src="https://cdn.uvdesk.com/uvdesk/images/aa1b406.gif" alt=""></div>');
            jQuery("#wkTabs li:nth-child(<?php echo $status?>)").addClass('active');
            jQuery("#wkTabContent div:nth-child(<?php echo $status?>)" ).addClass('in active');
            agent='<select id="agent" name="agent" class="inputbox wkAgent"> <option value="0"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_AGENT')?></option>';
            <?php
            foreach ($members as $value) {
                ?>
                    agent+='<option value="<?php echo $value->id?>"><?php echo $value->name?></option>';
                <?php
            }
            ?>
            agent+="</select></div>";
                var yourobject=(<?php echo json_encode($customFields)?>);
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
        jQuery(function(){
            globalStatus="<?php echo $status;?>";
            var page=jQuery('.ticket-table .pagination .active').children('a').text();
            jQuery('.wk_label_remove .label-name').text('<?php echo $jInput->get("label", '', 'STR');?>');
            callApi('<?php echo $status;?>');
            jQuery("#wkTabs li").on('click',function(){
                globalStatus=jQuery(this).index()+1;
                if(!jQuery(this).hasClass('active')){
                    var search=jQuery('#wksearchfilter').val();
                    search=search.trim();
                    var agent=jQuery('#wkagentfilter').val();
                    var group=jQuery("#wkgroupfilter").val();
                    var type=jQuery("#wktypefilter").val();
                    var filter={};
                    if(agent!=0){
                        filter.agent=agent;
                    }
                    if(group!=0){
                        filter.group=group;             
                    }
                    if(type!=0){
                        filter.type=type;                
                    }
                    if(search.length) {
                        filter.search=search;
                    }         
                    callApi(jQuery(this).index()+1,1,filter);
                }
            });
            jQuery('#wkagentfilter,#wkgroupfilter,#wktypefilter').on('change',function(){            
                var search=jQuery('#wksearchfilter').val();
                search=search.trim();
                var agent=jQuery('#wkagentfilter').val();
                var group=jQuery("#wkgroupfilter").val();
                var type=jQuery("#wktypefilter").val();
                var filter={};
                if(agent!=0){
                    filter.agent=agent;
                }
                if(group!=0){
                    filter.group=group;             
                }
                if(type!=0){
                    filter.type=type;                
                }
                if(search.length) {
                    filter.search=search;
                }         
                callApi(jQuery('#wkTabs li.active').index()+1,1,filter);
            });
            function getFilter(){
                var search=jQuery('#wksearchfilter').val();
                search=search.trim();
                var agent=jQuery('#wkagentfilter').val();
                var group=jQuery("#wkgroupfilter").val();
                var type=jQuery("#wktypefilter").val();
                var filter={};
                if(agent!=0){
                    filter.agent=agent;
                }
                if(group!=0){
                    filter.group=group;             
                }
                if(type!=0){
                    filter.type=type;                
                }
                if(search.length) {
                    filter.search=search;
                }
                return filter;     
            }
            jQuery('#wksearchfilterbutton').on('click',function(){
                var search=jQuery('#wksearchfilter').val();
                search=search.trim();
                var agent=jQuery('#wkagentfilter').val();
                var group=jQuery("#wkgroupfilter").val();
                var type=jQuery("#wktypefilter").val();
                var filter={};
                if(agent!=0){
                    filter.agent=agent;
                }
                if(group!=0){
                    filter.group=group;             
                }
                if(type!=0){
                    filter.type=type;                
                }
                if(search.length) {
                    filter.search=search;
                }         
                callApi(1,1,filter);
            });
            jQuery('#wk_clear_filter').on('click',function(){
                if (jQuery('#wkagentfilter').val()!=0||jQuery("#wkgroupfilter").val()!=0||jQuery("#wktypefilter").val()!=0||jQuery('#wksearchfilter').val().length) {
                    jQuery('#wkagentfilter').val("0");
                    jQuery("#wkgroupfilter").val("0");
                    jQuery("#wktypefilter").val("0");
                    jQuery('#wksearchfilter').val('');
                    callApi(jQuery('#wkTabs li.active').index()+1, 1, {});
                    jQuery('#wkagentfilter,#wktypefilter,#wkgroupfilter').trigger("liszt:updated");
                }
            });
            jQuery('#wkTabContent').on('click','tfoot .pagination li a', function(){
                if (jQuery(this).text()=="»") {
                    if (typeof(saveResult.pagination)!=='undefined'&&typeof(saveResult.pagination.next)!=='undefined'&&saveResult.pagination.next!=null&&saveResult.pagination.pageCount>saveResult.pagination.current-1) {
                        callApi(globalStatus,saveResult.pagination.next,getFilter());
                    }                                    
                } else if(jQuery(this).text()=="«"){
                    if (typeof(saveResult.pagination)!=='undefined'&&typeof(saveResult.pagination.previous)!=='undefined'&&saveResult.pagination.previous!=null&&saveResult.pagination.current>1) {
                        callApi(globalStatus,saveResult.pagination.previous,getFilter());
                    }    
                } else if(Number.isInteger(parseInt(jQuery(this).text()))){
                    callApi(globalStatus,jQuery(this).text(),getFilter());
                }
            saveResult='';
            });
        });
        function callApi(status,page=1,filter={}){
            jQuery.ajax({
                url:"index.php?option=com_uvdeskwebkul&task=viewtickets.apiCall",
                data:{
                        status:status,
                        filter:JSON.stringify(filter),
                        page:page,
                        label:"<?php echo $label?>"
                },
                dataType:'json',
                method:'POST',
                success: function(result){
                    //jQuery('#wkTabs li').
                    result=JSON.parse(JSON.stringify(result));
                    if(typeof(result.error)=='undefined'||result.error==null){
                        saveResult=result;
                        var pagination='';
                        // var count=result.pagination.totalCount;
                        var pageCount=result.pagination.pageCount;
                        if(Object.keys(result.pagination.pagesInRange).length>0&&result.pagination.totalCount>15){
                            pagination='<tr><td colspan="10"><ul class = "pagination">';
                            if(result.pagination.current==1){
                                pagination+='<li class="disabled"><a href = "javascript:void(0)">&laquo;</a></li>';
                            } else{
                                    pagination+='<li><a href = "javascript:void(0)">&laquo;</a></li>';
                            }
                            for(var i=0;i<Object.keys(result.pagination.pagesInRange).length;i++){
                                if(result.pagination.current==result.pagination.pagesInRange[i]){
                                    pagination+='<li class="active"><a href = "javascript:void(0)">'+result.pagination.pagesInRange[i]+'</a></li>';
                                } else{
                                    pagination+='<li ><a href = "javascript:void(0)">'+result.pagination.pagesInRange[i]+'</a></li>';
                                }
                            }
                            if(result.pagination.current==pageCount){
                                pagination+='<li class="disabled"><a href = "javascript:void(0)">&raquo;</a></li></ul></td></tr>';
                            } else {
                                pagination+='<li ><a href = "javascript:void(0)">&raquo;</a></li></ul></td></tr>';
                                
                            }
                        }
                    
                    jQuery('#wkTabContent tfoot').html(pagination);                
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
                    if(Object.keys(result.tickets).length>0){
                            for (var i =0; i<Object.keys(result.tickets).length; i++) {
                                var star='gray';
                                if(result.tickets[i].isStarred!=null&&result.tickets[i].isStarred!=false){
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
                                    unassignedName=result.tickets[i].agent.name;
                                    if(typeof(result.tickets[i].agent.smallThumbnail)=='undefined'||result.tickets[i].agent.smallThumbnail==null){
                                        result.tickets[i].agent.smallThumbnail='https://cdn.uvdesk.com/uvdesk/images/163b0ed.png';
                                    }
                                    var agentImage=result.tickets[i].agent.smallThumbnail;
                                }                                
                                    bodyString+='<tr><td><span class="icon-circle" title="'+result.tickets[i].priority.name+'" style="color:'+result.tickets[i].priority.color+'"></span></td><td class="quick-link"><div class="icheckbox_square-blue" style="position:relative;"><input class="mass-action-checkbox checkbox" value="'+result.tickets[i].id+'" style="" type="checkbox"/></td><td class="center"><i data="'+result.tickets[i].id+'" class="fa fa-star mark-star-'+star+'" aria-hidden="true"></i></td> <td class="id" class="center"><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'");?>">'+result.tickets[i].source+'</a></td><td class="id"><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'");?>"> #'+result.tickets[i].incrementId+'</a></td><td class="customer-name"><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'");?>" title="'+result.tickets[i].customer.name+'">'+result.tickets[i].customer.name+'</a></td><td class="subject"><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'");?>">'+result.tickets[i].subject+'</a><span class="fade-subject"></span></td><td class="details"><a href="<?php echo JRoute::_("index.php?option=com_uvdeskwebkul&view=viewticket&id='+result.tickets[i].incrementId+'");?>"><span class="date">'+result.tickets[i].formatedCreatedAt+'</span><span class="badge badge-lg">'+result.tickets[i].totalThreads+'</span></a></td><td>'+group+'</td><td class="agent-name"><a href="#" class="edit-ticket-agent"></a><span></span><a class="semibold" href="javascript:void(0)" title="'+unassigned+'">';
                                    if(unassigned!='Unassigned') {
                                        bodyString+='<img alt="" class="wkagentimg" src="'+agentImage+'"/>';
                                    }
                                    bodyString+='<span class="wk_agent_name_text">'+unassignedName+'</span></a></td></tr>';
                                }
                        }
                        else{
                            bodyString="<tr><td colspan='10'><p style='text-align:center' class='alert alert-info'><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_NO_RECORD_FOUND')?></p></td></tr><tr></tr>"
                        }
                        jQuery('.wktable').empty();
                        jQuery('.wktable').append(bodyString);
                    } else {
                         Joomla.renderMessages({'error': [result.error] });
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
    <div class="orderhistory_main">
            <div id="j-sidebar-container" class="span3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_TICKETS');?></h4>
                    </div>
                    <div class="panel-body">
                        <!--<strong>
                            <i class="fa fa-inbox"></i>
                        </strong>-->
                        <div class="ticket-label-block">
                            <ul class="list-block predefined-label-list">                            
                            </ul>
                            <span class="clearfix"></span>
                            <?php if (isset($uvDeskCutomer->customers[0]->email)) {?>
                            <button class="btn btn-default" style="margin-top: 10px; float: left; color: #617d8a; border: 1px dashed #617d8a; padding: 4px 10px;" data-target="#createTicketModal" data-toggle="modal"> 
                                <i class="fa fa-plus-circle"></i><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CREATE_TICKETS');?>
                            </button>
                            <?php }?>
                        </div>
                    </div>
                    
                </div>
                <div class="wksearchpanel-body">
                    <div class="wkheader">
                        <strong>
                            <i class="fa fa-search" aria-hidden="true"></i><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SEARCH');?>
                        </strong>
                    </div>
                    <div class="ticket-filter-block">
                        <div class="searchfilter">
                            <input id="wksearchfilter" placeholder="<?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SEARCH');?>" type="text"/>
                        </div>
                        <div id="wksearchfilterbutton" class="btn btn-primary"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SEARCH');?></div>
                    </div>
                </div>
                <div class="wkpanel-body">
                    <div class="wkheader">
                        <strong>
                            <i class="fa fa-filter" aria-hidden="true"></i><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_FILTER');?>
                        </strong>
                    </div>
                        <div class="ticket-filter-block">
                            <div class="agentfilter">
                                <select id="wkagentfilter">
                                    <option value="0" selected="selected"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SELECT_AGENT');?></option>
                                    <?php 
                                    foreach ($members as $value) {?>
                                        <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                                    <?php 
                                    }?>
                                </select>
                            </div>
                            <div class="typefilter">
                                <select id="wktypefilter">
                                    <option value="0" selected="selected"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_TICKET_TYPE');?></option>
                                    <?php 
                                    foreach ($tickets->type as $value) {
                                        ?>
                                    <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="groupfilter">
                                <select id="wkgroupfilter">
                                    <option value="0" selected="selected"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_GROUP');?></option>
                                    <?php 
                                    foreach ($tickets->group as $value) {?>
                                        <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                                    <?php
                                    }?>
                                </select>
                            </div>
                        </div>
                        <div class="thread-pagination1 btn btn-primary" id="wk_clear_filter"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_CLEAR_FILTER');?></div>
                    </div>
                    <?php if (isset($uvDeskCutomer->customers[0]->email)) {?>
                <div class="modal fade" id="createTicketModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 id="myModalLabel" class="modal-title text-center"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_CREATE_A_TICKET');?></h4>
                            </div>
                            <div class="modal-body">
                                <form name="" method="post" action="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&task=viewtickets.createTicket')?>" enctype="multipart/form-data" id="create-ticket-form">
                                    <div id="create-ticket-form1">
                                        <div class="">
                                            <div class="form-group required ">
                                                <label for="name" class="required"><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_NAME');?></label>
                                                <input id="name" name="name" required="required" placeholder="<?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_NAME');?>" class="form-control" type="text">
                                            </div>
                                        </div> 
                                        <div class="">
                                            <div class="form-group required ">
                                                <label for="from" class="required"><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_EMAIL');?></label>
                                                <input id="from" name="from" required="required" placeholder="<?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CUSTOMER_EMAIL');?>" class="form-control" type="email">
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="form-group required ">
                                                <label for="type" class="required"><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_TYPE');?></label>
                                                
                                                <select id="type" name="type" data-role="tagsinput" class="wkselectpicker form-control" tabindex="-98">
                                                    <option value="0" selected="selected"><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CHOOSE_TICKET_TYPE');?></option>
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
                                                <label for="subject" class="required"><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CHOOSE_TICKET_SUBJECT');?></label>
                                                <input id="subject" name="subject" required="required" placeholder="<?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CHOOSE_TICKET_SUBJECT');?>" class="form-control" type="text">
                                            <!-- </div> -->
                                        </div>
                                        <div class="">
                                            <div class="form-group required ">
                                                <label for="reply" class="required"><?php echo JText::_('COM_UVDESKWEBKUL_CREATE_A_TICKET_CHOOSE_TICKET_MESSAGE');?></label>
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
                                        <input id="token" name="token" value="<?php  ?>" type="hidden">
                                    </div> 
                                    <div class="wk-custom-fields">
                                            
                                    </div>
                                    <div class="">
                                        <button type="submit" id="submit1" name="submit1" class="btn btn-md btn-info"><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CREATE_TICKETS');?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                
                </div>
                    
            </div>
                    <?php } ?>
            </div>
            <div id="j-main-container"  class="span9">
                <div class="wkTopBar">
                    <div class="withoutSelection">
                        <div class="agents wk_mass_selection" style="float: left;margin-right: 5px;">
                            <select class="assignment">
                            <option value="0" selected="selected"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_AGENT')?></option>
                                <?php 
                                foreach ($members as $value) {?>
                                    <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                                <?php 
                                }?>
                            </select>
                        </div>
                        <div class="mass-status wk_mass_selection" style="float: left;margin-right: 5px;">
                            <select class="status">
                            <option value="0" selected="selected"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_STATUS')?></option>
                                <?php 
                                foreach ($tickets->status as $value) {?>
                                    <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                                <?php
                                }?>
                            </select>
                        </div>
                        <div class="mass-group wk_mass_selection" style="float: left;margin-right: 5px;">
                            <select class="groups">
                            <option value="0" selected="selected"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_GROUP')?></option>
                                <?php 
                                foreach ($tickets->group as $value) {?>
                                    <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                                <?php
                                }?>
                            </select>
                        </div>
                        <div class="mass-priority wk_mass_selection" style="float: left;margin-right: 5px;">
                            <select class="priority">
                            <option value="0" selected="selected"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_PRIORITY')?></option>
                                <?php 
                                foreach ($tickets->priority as $value) {?>
                                    <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                                <?php
                                }?>
                            </select>
                        </div>
                        <div class="mass-label wk_mass_selection" style="float: left;margin-right: 5px;">
                            <select class="label">
                            <option value="0" selected="selected"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_LABEL')?></option>
                                <?php 
                                foreach ($tickets->labels->custom as $value) {?>
                                    <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                                <?php
                                }?>
                            </select>
                        </div>
                        <div class="mass-label wk_mass_selection" style="float: left;margin-right: 5px;">
                            <?php
                            if ($trashed) {?>
                                <div class="deleteMultipleForever btn btn-default"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_DELETE_FOREVER')?></div>
                            <?php
                            } else {
                                ?>
                            <div class="deleteMultiple btn btn-default"><i class="fa fa-trash-o"></i><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_DELETE')?></div>
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                        if ($trashed) {
                            ?>
                        <div class="mass-label wk_mass_selection" style="float: left;margin-right: 5px;">
                            <div class="restoreMultipleTicket btn btn-default"><?php echo JText::_('COM_UVDESKWEBKUL_TICKET_RESTORE')?></div>                            
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="withSelection">
                        <div></div>
                    </div>
                    <div class="filter-header form-horizontal">
                        <div class="filter-action"></div>
                        <div class="filter-single status hide-remove wk_status_remove" data-filter="status">
                            <span class="filter-label"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_STATUS')?> : </span>
                                <span class="label-name"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_OPEN')?></span>
                        </div>
                        
                    </div>
                </div>
                <div id="tabsContainer">
                    <ul id = "wkTabs" class = "nav nav-tabs">
                        <li class = "open">
                            <a href = "#open" data-toggle = "tab">
                                <i class="fa fa-inbox"></i>
                                <p class="label-text"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_OPEN')?></p>
                            </a>
                        </li>
                        <li class="pending">
                            <a href = "#pending" data-toggle = "tab">
                            <i class="fa fa-exclamation-triangle"></i>
                            <p class="label-text"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_PENDING')?></p>
                            </a>
                        </li>
                        
                        <li class="resolved">
                            <a href = "#resolved" data-toggle = "tab">
                            <i class="fa fa-check-circle"></i>
                            <p class="label-text"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_RSOLVED')?></p>
                            </a>
                        </li>
                        <li class="closed">
                            <a href = "#closed" data-toggle = "tab">
                            <i class="fa fa-minus-circle"></i>
                            <p class="label-text"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_CLOSED')?></p>
                            </a>
                        </li>
                        <li class="spam">
                            <a href = "#spam" data-toggle = "tab">
                            <i class="fa fa-lightbulb-o"></i>
                            <p class="label-text"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_SPAM')?></p>
                            </a>
                        </li>
                        <li class="answered"> 
                            <a href = "#answered" data-toggle = "tab">
                            <i class="fa fa-ban"></i>
                            <p class="label-text"><?php echo JText::_('COM_UVDESKWEBKUL_CUSTOMERS_ANSWERED')?></p>
                            </a>
                        </li>
                    </ul>
                    <div id = "wkTabContent" class = "tab-content">
                        <div class = "tab-pane fade" id = "open">
                            <div class="ticket-table">
                                <table class="table table-bordered">
                                    <thead style="background-color:#EEEEEE">
                                        <tr>
                                            <th><span class="icon-circle" style="color:#EEE;"></span></th>
                                            <th><input name="checkall-toggle" class="wk-checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_STARRED')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SOURCE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_TICKET_NO')?>.</th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CUSTOMER_NAME')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SUBJECT')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CREATED_DATE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_GROUP')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_AGENT')?></th>
                                        </tr>
                                    </thead>
                                        <tbody class="wktable">
                                        
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class = "tab-pane fade" id = "pending">
                            <div class="ticket-table">
                                    <table class="table table-bordered">
                                    <thead style="background-color:#EEEEEE">
                                        <tr>
                                            <th><span class="icon-circle" style="color:#EEE;"></span></th>
                                            <th><input name="checkall-toggle" class="wk-checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_STARRED')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SOURCE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_TICKET_NO')?>.</th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CUSTOMER_NAME')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SUBJECT')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CREATED_DATE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_GROUP')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_AGENT')?></th>
                                        </tr>
                                    </thead>
                                        <tbody class="wktable">
                                        
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class = "tab-pane fade" id = "answered">
                            <div class="ticket-table">
                                <table class="table table-bordered">
                                    <thead style="background-color:#EEEEEE">
                                        <tr>
                                            <th><span class="icon-circle" style="color:#EEE;"></span></th>
                                            <th><input name="checkall-toggle" class="wk-checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_STARRED')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SOURCE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_TICKET_NO')?>.</th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CUSTOMER_NAME')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SUBJECT')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CREATED_DATE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_GROUP')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_AGENT')?></th>
                                        </tr>
                                    </thead>
                                        <tbody class="wktable">
                                        
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class = "tab-pane fade" id = "resolved">
                            <div class="ticket-table">
                                <table class="table table-bordered">
                                    <thead style="background-color:#EEEEEE">
                                        <tr>
                                            <th><span class="icon-circle" style="color:#EEE;"></span></th>
                                            <th><input name="checkall-toggle" class="wk-checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_STARRED')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SOURCE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_TICKET_NO')?>.</th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CUSTOMER_NAME')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SUBJECT')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CREATED_DATE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_GROUP')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_AGENT')?></th>
                                        </tr>
                                    </thead>
                                        <tbody class="wktable">
                                        
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class = "tab-pane fade" id = "closed">
                            <div class="ticket-table">
                                <table class="table table-bordered">
                                    <thead style="background-color:#EEEEEE">
                                        <tr>
                                            <th><span class="icon-circle" style="color:#EEE;"></span></th>
                                            <th><input name="checkall-toggle" class="wk-checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
                                           <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_STARRED')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SOURCE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_TICKET_NO')?>.</th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CUSTOMER_NAME')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SUBJECT')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CREATED_DATE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_GROUP')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_AGENT')?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="wktable">
                                        
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class = "tab-pane fade" id = "spam">
                            <div class="ticket-table">
                                    <table class="table table-bordered">
                                    <thead style="background-color:#EEEEEE">
                                        <tr>
                                            <th><span class="icon-circle" style="color:#EEE;"></span></th>
                                            <th><input name="checkall-toggle" class="wk-checkall-toggle" value="" class="hasTooltip" title="" onclick="Joomla.checkAll(this)" data-original-title="Check All" type="checkbox"></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_STARRED')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SOURCE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_TICKET_NO')?>.</th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CUSTOMER_NAME')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_SUBJECT')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_CREATED_DATE')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_GROUP')?></th>
                                            <th><?php echo JText::_('COM_UVDESKWEBKUL_TICKETS_AGENT')?></th>
                                        </tr>
                                    </thead>
                                        <tbody class="wktable">
                                        
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </form> -->
    </div>
<?php
} else {
    JFactory::getApplication()->redirect('index.php?option=com_config&view=component&component=com_uvdeskwebkul', 'Please fill correct API key and sub domain', 'error');
}

