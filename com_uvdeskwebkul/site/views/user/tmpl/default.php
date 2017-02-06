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
$model=$this->getModel();
$customerId=JRequest::getVar('userId');
$data=json_decode($model->getCustomer($customerId));
echo "<pre>";
print_r($data);die;
?>
 <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
 <script type="text/javascript">
	jQuery(function(){
		/*jQuery.ajax({
			url:"index.php?option=com_uvdeskwebkul&task=viewtickets.apiCall",
			data:{
					ticketId:"<?php echo $data->ticket->id ?>"
			},
			dataType:'json',
			method:'POST',
			success: function(result){
				console.log(result);
			}
				});*/
	});
</script>
<div class="orderhistory_main">
	<!-- <div id="j-sidebar-container" class="span2"></div> -->
	<div id="wk_block-container" class="span12" style="padding-left:5%;">
    	<div class="main no-right-bar">
            <div class="wk_upperpart">
                <div class="block-container">
                    <div class="">
                        <div class="block-title">
                            <h3>Edit Customer</h3>
                        </div>
                    </div>
                    <div class="">
                        <form name="user_form" method="post" action="" id="test_id" novalidate="novalidate" enctype="multipart/form-data">
                            <div class="">
                                <div class="form-group ">
                                    <div class="profile-image-block">
                                        <div class="profile-image">
                                            <img src="https://s3-ap-southeast-1.amazonaws.com/cdn.uvdesk.com/website/217/1.jpg">
                                        </div>
                                        <div class="profile-image-input">
                                            <p>Upload .png or .jpg file with
                                                <br> dimensions 100x100 px or more.</p>
                                            <label for="user_form_profileImage">Upload Now</label>
                                            <input id="user_form_profileImage" name="user_form[profileImage]" style="display:none" class="" isimage="isImage" info="(300px x 300px)" accept="image/*" type="file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
         <div class="wk_lowerpart">
            <ul class="nav nav-tabs" role="">
                <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a>
                </li>
            </ul>
            <br>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div id="user_form">
                    <div class="">
                        <div class="form-group required ">
                            <label for="user_form_firstName" class="required">First Name</label>
                            <input id="user_form_firstName" name="user_form[firstName]" required="required" placeholder="Enter first name" parent-div-class="" clearfix="clearfix" class="form-control" value="kailash" type="text">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="">
                        <div class="form-group required ">
                            <label for="user_form_lastName" class="required">Last Name</label>
                            <input id="user_form_lastName" name="user_form[lastName]" required="required" placeholder="Enter last name" parent-div-class="" clearfix="clearfix" class="form-control" value="Chandra" type="text">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="">
                        <div class="form-group required ">
                            <label for="user_form_email" class="required">Email</label>
                            <input id="user_form_email" name="user_form[email]" required="required" placeholder="Enter email address" parent-div-class="" clearfix="clearfix" class="form-control" value="kcd.bca91@gmail.com" type="email">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="">
                        <div class="form-group ">
                            <label for="user_form_jobTitle">Designation</label>
                            <input id="user_form_jobTitle" name="user_form[jobTitle]" placeholder="Enter designation" parent-div-class="" clearfix="clearfix" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="">
                        <div class="form-group required ">
                            <label for="user_form_contactNumber" class="required">Contact Number</label>
                            <input id="user_form_contactNumber" name="user_form[contactNumber]" required="required" placeholder="Enter contact number" parent-div-class="" clearfix="clearfix" class="form-control" value="8802014150" type="text">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="">
                        <div class="form-group required ">
                            <?php echo $this->form->getLabel('offset'); ?>
                            <?php echo $this->form->getInput('offset');?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="">
                        <div class="form-group ">
                            <label for="user_form_signature">Signature</label>
                            <textarea id="user_form_signature" name="user_form[signature]" placeholder="Enter signature" parent-div-class="" clearfix="clearfix" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="">
                        <div class="form-group required ">
                            <label for="user_form_role" class="required">Role</label>
                            <div class="btn-group bootstrap-select disabled">
                                <button type="button" class="btn dropdown-toggle disabled btn-default" data-toggle="dropdown" role="button" data-id="user_form_role" tabindex="-1" title="Account Owner"><span class="filter-option pull-left">Account Owner</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span>
                                </button>
                                <div class="dropdown-menu open" role="combobox">
                                    <ul class="dropdown-menu inner" role="listbox" aria-expanded="false">
                                        <li data-original-index="0" class="selected"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="true"><span class="text">Account Owner</span><span class="glyphicon glyphicon-ok check-mark"></span></a>
                                        </li>
                                        <li data-original-index="1"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">Administrator</span><span class="glyphicon glyphicon-ok check-mark"></span></a>
                                        </li>
                                        <li data-original-index="2"><a tabindex="0" class="" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">Agent</span><span class="glyphicon glyphicon-ok check-mark"></span></a>
                                        </li>
                                    </ul>
                                </div>
                                <select id="user_form_role" name="user_form[role]" disabled="disabled" class="selectpicker" parent-div-class="" clearfix="clearfix" tabindex="-98">
                                    <option value="ROLE_SUPER_ADMIN" selected="selected">Account Owner</option>
                                    <option value="ROLE_ADMIN">Administrator</option>
                                    <option value="ROLE_AGENT">Agent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <input id="user_form__token" name="user_form[_token]" value="4ushXTyL4t25IyfPnqz3G7x6sRWjWt-EXGId0lFObxE" type="hidden">
                </div>
            </div>
        </div>
    <div class="">
        <br>
        <button type="submit" id="user_form_save" name="user_form[save]" class="btn btn-info btn-md pull-left">Save</button>
    </div>
</div>
    </div>
</div>