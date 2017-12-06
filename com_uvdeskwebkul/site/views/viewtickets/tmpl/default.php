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
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
$document = JFactory::getDocument();
?>
<style type="text/css">
   .wkTicketHeader{
       min-height:100px;
   }
   #wkTabContent{
       overflow-x:auto;
   }
   #tabsContainer *{
       font-size:14px!important;
       font-family: 'Open Sans', sans-serif;
   }
</style>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">  
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
<?php
$document->addStyleSheet(JURI::root().'administrator/components/com_uvdeskwebkul/assets/css/uvdesk.css');
$document->addStyleSheet(JURI::root().'/components/com_uvdeskwebkul/assets/pagination.css');
$model=$this->getModel();
$tickets=$model->getTickets();
$customerId=$this->get('customerId');
$this->setDocumentTitle('Tickets');
?>
<script type="text/javascript">
    jQuery(function(){
        jQuery('.attach-file').click(function(){
            jQuery('#attachments').trigger('click');
        });        
        jQuery('body').append('<div class="content-wrap"><img class="ajax-loader-img ajax-loader" style="" src="https://cdn.uvdesk.com/uvdesk/images/aa1b406.gif" alt=""></div>');
        var status=jQuery('#filterticket').val();
        globalStatus=status;
        var page=1;
        callApi(globalStatus);
        jQuery('#filterticket').on('change',function(){
            callApi(jQuery(this).val());
            globalStatus=jQuery(this).val();
        });
        jQuery('#sortticket').on('change',function(){
            callApi(globalStatus);
        });
        jQuery('.wk_selectbox #search-ticket').on('keyup',function(){
            callApi(globalStatus);
        });
    });

    function callApi(status,page=1){
        var sort=jQuery('#sortticket').val();
        var search='';
        //var page=1;
        search=jQuery('.wk_selectbox #search-ticket').val();
        jQuery.ajax({
            url:"index.php?option=com_uvdeskwebkul&task=viewtickets.apiCall",
            data:{
                status:status,
                cid:'<?php echo $customerId?>',
                sort:sort,
                search:search,
                page:page,
            },
            dataType:'json',
            method:'POST',
            success: function(result){
                var bodyString='';
                result=JSON.parse(JSON.stringify(result));       
                var pagination='';
                if(result.pagination.totalCount>15){
                    var pageCount=result.pagination.pageCount;
                    pagination='<tr><td colspan="6"><ul class = "pagination">';
                    if(result.pagination.current==1){
                        pagination+='<li class="disabled"><a href = "javascript:void(0)">&laquo;</a></li>';
                    } else{
                            pagination+='<li><a href = "javascript:void(0)">&laquo;</a></li>';
                    }
                    for(var i=1;i<=pageCount;i++){
                        if(result.pagination.current==i){
                            pagination+='<li class="active"><a href = "javascript:void(0)">'+i+'</a></li>';
                        } else{
                            pagination+='<li ><a href = "javascript:void(0)">'+i+'</a></li>';
                        }
                    }
                    if(result.pagination.current==pageCount){
                        pagination+='<li class="disabled"><a href = "javascript:void(0)">&raquo;</a></li></ul></td></tr>';
                    } else {
                        pagination+='<li ><a href = "javascript:void(0)">&raquo;</a></li></ul></td></tr>';
                        
                    }
                }                         
                jQuery('#wkTabContent tfoot').html(pagination); 
                jQuery('#wkTabContent tfoot .pagination li a').on('click', function(){
                    if (jQuery(this).text()=="»") {
                        var nextPage=parseInt(jQuery('.pagination .active').children('a').text())+1;
                        if (nextPage<=pageCount) {
                            callApi(globalStatus, nextPage);
                        }                                    
                    } else if(jQuery(this).text()=="«"){
                        var prevPage=parseInt(jQuery('.pagination .active').children('a').text())-1;
                        if (prevPage>0) {
                            callApi(globalStatus, prevPage);
                        }    
                    } else if(Number.isInteger(parseInt(jQuery(this).text()))){
                        callApi(globalStatus, jQuery(this).text());
                    }
                //}
                    //
                });
                
                if(Object.keys(result.tickets).length>0){
                    if (globalStatus!=0) {
                        for (var i =0; i<Object.keys(result.status).length; i++){
                            if(result.status[i].id==globalStatus){
                                globalStatusName=result.status[i].name;
                                globalStatus=result.status[i].id;
                            }                            
                        }
                    }
                    for (var i =0; i<Object.keys(result.tickets).length; i++) {
                        status='';
                        var unassigned='';
                        var unassignedName='<?php echo Jtext::_("COM_UVDESKWEBKUL_TICKETS_UNASSIGNED");?>';
                        var status=result.tickets[0]
                        if(globalStatus==0){
                            globalStatusName=result.tickets[i].status.name;
                        }
                        if(result.tickets[i].agent==null){
                            unassigned='<?php echo Jtext::_("COM_UVDESKWEBKUL_TICKETS_UNASSIGNED");?>';
                        }
                        else{
                            unassigned=result.tickets[i].agent.name;
                            unassignedName=result.tickets[i].agent.name;
                        }
                            var q=result.tickets[i].id;
                            var imageUrl='https://cdn.uvdesk.com/uvdesk/images/163b0ed.png';
                            if (result.tickets[i].agent!=null&&result.tickets[i].agent.smallThumbnail!=null) {
                                imageUrl=result.tickets[i].agent.smallThumbnail;
                            } else if (result.tickets[i].agent==null) {
                                imageUrl='';
                            }
                        bodyString+='<tr id='+result.tickets[i].incrementId+'><td class="id" title="<?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_NO');?>"><a href="'+q+'">#'+result.tickets[i].incrementId+'</a></td ><td class="subject" title="<?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_SUBJECT');?>"><a href="'+q+'">'+result.tickets[i].subject+'</a></td><td title="<?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_STATUS');?>" >'+globalStatusName+'</td><td class="details" title="<?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_CREATED_DATE');?>"><a href="'+q+'"><span class="date">'+result.tickets[i].formatedCreatedAt+'</span><span class="badge badge-lg">'+result.tickets[i].totalThreads+'</span></a></td><td class="agent-name" title="<?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_AGENT');?>"><a href="'+q+'"><div><image class="wkAgentImage" alt="" src="'+imageUrl+'" /></div><div>'+unassignedName+'</div></a></td><td title="<?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_ACTION');?>"><a href="'+q+'"><div class="btn btn-success"><?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_VIEW_TICKETS');?></div></a></td></tr>';                        
                    }
                }
                else{
                    bodyString="<tbody><tr><td colspan='6'><p style='text-align:center'><?php Jtext::_("COM_UVDESKWEBKUL_TICKETS_NO_RECORD_FOUND");?></p></td></tr><tr></tr></tbody>"
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
                   
            }
        });
    }
</script>
<div class="orderhistory_main_front">
    <div id="j-main-container"  class="span12 wk_block-container_main">
        <!--<div> Ticket Requests</div>-->
        <div id="tabsContainer">
            <div class="wkTicketHeader">
                <div class="wk_selectbox">
                    <span>Sort By</span>
                    <select id="sortticket" style="width: 120px">
                        <option value="t.id desc" selected="true"><?php echo Jtext::_("COM_UVDESKWEBKUL_TICKETS_SORT_TICKET_ID_DESC");?></option>
                        <option value="t.id asc"><?php echo Jtext::_("COM_UVDESKWEBKUL_TICKETS_SORT_TICKET_ID_DESC");?></option>
                        <option value="t.updatedAt Desc"><?php echo Jtext::_("COM_UVDESKWEBKUL_TICKETS_SORT_LAST_REPLIED_DESC");?></option>
                        <option value="t.updatedAt asc"><?php echo Jtext::_("COM_UVDESKWEBKUL_TICKETS_SORT_LAST_REPLIED_ASC");?></option>
                    </select>
                </div>
                <div class="wk_selectbox">
                    <span><?php echo Jtext::_("COM_UVDESKWEBKUL_TICKETS_FILTER_STATUS");?></span>
                    <select id="filterticket" style="width: 120px">
                    <option value="0"><?php echo Jtext::_("COM_UVDESKWEBKUL_TICKETS_FILTER_BY_STATUS");?></option>
                        <?php
                        foreach ($tickets->status as $value) {
                            $defaultStatus=1;
                            ?>
                            <option value="<?php echo $value->id?>"><?php echo $value->name?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="wk_selectbox">
                    <div class="form-search search-only" style="width: 200px;position:relative">
                        <span class="icon-search"></span>
                        <input class="form-control search-query" id="search-ticket" placeholder="<?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_SEARCH_TICKETS');?>" type="text">
                    </div>
                </div>
            </div>            
            <div id = "wkTabContent" class = "tab-content">
                <div class = "" id="open">
                    <div class="ticket-table wkViewAllticket">
                        <table class="table table-bordered">
                            <thead style="background-color:#EEEEEE">
                                <tr>
                                    <th><?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_NO');?></th>
                                    <th><?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_SUBJECT');?></th>
                                    <th><?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_STATUS');?></th>
                                    <th><?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_CREATED_DATE');?></th>
                                    <th><?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_AGENT');?></th>
                                    <th><?php echo Jtext::_('COM_UVDESKWEBKUL_TICKETS_TICKET_ACTION');?></th>
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
    </div>
</div>

