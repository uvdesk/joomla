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
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_uvdeskwebkul/assets/css/uvdesk.css');
$document->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
$document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css');
$document->addScript('https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js');
$model=$this->getModel();
$customerId=JRequest::getVar('userId');
$count=JRequest::getVar('page');
/*if(!isset($count)){
    $count=1;
}*/
$data=json_decode($model->getCustomer());
?>
<style type="text/css">
    .sweet-alert fieldset{
        display: none!important;
    }
</style>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
 <script type="text/javascript">
	jQuery(function(){
        jQuery('body').append('<div class="content-wrap"><img class="ajax-loader-img ajax-loader" style="" src="https://cdn.uvdesk.com/uvdesk/images/aa1b406.gif" alt=""></div>');
	   jQuery('.delete-user').on('click',function(){
        var cid=jQuery(this).attr('id');
        swal({
              title: "Are you sure?",
              text: "You will not be able to recover this User!",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Yes, delete it!",
              closeOnConfirm: false
            },
             function(isConfirm) {
                if (isConfirm) {
                    jQuery.ajax({
                        url:"<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=users&task=users.deleteCustomer',false)?>",
                        type:"POST",
                        data:{"customerId":cid},
                        success:function(data){
                            console.log(data);
                            swal({
                                title: 'Deleted!',
                                text: 'Customer Deleted successfully!',
                                type: 'success'
                             });
                            location.reload();
                        }

                        
                    });                 
                    
                } else {
                    //swal("Cancelled", "", "error");
                }
                
            });
        });
        jQuery('#user-table .mark-star').on('click',function(){
            var cid=jQuery(this).attr('webkul');
              jQuery.ajax({
                    url:"<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=users&task=users.starCustomer',false)?>",
                    type:"POST",
                    data:{"customerId":cid},
                    success:function(data){
                        if(jQuery('.'+data+' i').hasClass('mark-star-yellow')){
                            jQuery('.'+data+' i').removeClass('mark-star-yellow');
                        }else{
                             jQuery('.'+data+' i').addClass('mark-star-yellow');
                        }
                                             
                },
                beforeSend: function(){
                    jQuery('.content-wrap').show();
                },
                complete: function(){                                   
                    jQuery('.content-wrap').hide();
                }
            });                 
        });
    });
</script>
<div class="orderhistory_main" style="padding: 10px">
	<div id="wk_block-container_customer" class="span12">
        <div class="block-title">
            <h3>Customers</h3>
        </div>
    	<div class="panel panel-default table-container" style="" id="user-table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Open Tickets</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th class="action">Actions</th>
                        <th class="last">Star</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data->customers as $value) {?>
                        <tr>
                            <td>
                                <?php
                                 $starred='';
                                if(isset($value->isStarred)){
                                    $starred="mark-star-yellow";
                                }/*

                                echo "<pre>";
                                print_r($value);die;*/
                                $customerDetail=json_decode($model->getCustomerDetail($value->id));
                                 
                                if($customerDetail->data[0]->isActive){
                                    $active="success";
                                    $activeText="Active";
                                }else{
                                    $active="danger";
                                    $activeText="Disabled";

                                }
                                if(isset($customerDetail->data[0]->profileImage)){
                                    $profileImage=$customerDetail->data[0]->profileImage;
                                }else{
                                    $profileImage="https://cdn.uvdesk.com/uvdesk/images/d94332c.png";
                                }
                               
                                ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets&customerId='.$value->id,false)?>">
                                    <span class="round-tabs two">          
                                        <img src="<?php echo $profileImage?>">
                                    </span><?php echo $value->name?>
                                </a>
                            </td>
                            <td><?php echo $value->email?></td>
                            <td> <?php echo $value->count?> Open Tickets </td>
                            <td class="text-center"><i class="fa fa-television source" aria-hidden="true"></i></td>
                            <td><span class="badge badge-<?php echo  $active;?>"><?php echo $activeText?></span></td>
                            <td class="action">
                               <!--  <a href="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=user&userId='.$value->id)?>">
                                    <span class="badge badge-primary edit">
                                        <i class="fa fa-pencil"></i>
                                    </span>
                                </a> -->
                                <span class="delete-user" id="<?php echo $value->id?>">
                                    <span class="badge badge-danger delete">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </span>
                            </td>
                            <td class="last">
                                <a href="javascript:void(0)" class="mark-star <?php echo $value->id?>" webkul="<?php echo $value->id?>">
                                    <i class="fa fa-star <?php echo $starred?>"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                        ?>
                </tbody>
                <tfoot class="footer" style="overflow: hidden">
                  <!--   <tr>
                        <td colspan="7">
                            <div class="navigation">
                                <ul class="pagination">
                                    <li class="disabled">
                                        <span>«&nbsp;Previous</span>
                                    </li>
                                    <?php
                                    for($i=$data->pagination_data->first;$i<=$data->pagination_data->last;$i++) {
                                    ?>
                                    <li>
                                        <a class="page-number" data-page="<?php echo $i?>" href="#page=<?php echo $i?>"><?php echo $i?></a>
                                    </li>
                                    <?php
                                    }?>
                                    <li>
                                        <a href="javascript:void(0)" data-page="" id="next">Next&nbsp;»</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr> -->
                </tfoot>
            </table>
        </div>
    </div>
</div>