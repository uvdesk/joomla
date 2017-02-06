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
$document->addStyleSheet(JURI::root().'administrator/components/com_uvdeskwebkul/assets/css/uvdesk.css');
$model=$this->getModel();
$user=JFactory::getUser();
$customerId=$this->get('customerId');
?>
<style type="text/css">
	.orderhistory_main_front *{
		font-family: Open Sans;
		font-size: 14px;
		box-sizing: border-box;
	}
</style>
<script type="text/javascript">
	jQuery(function(){
		jQuery('.wk_login_register').on('click',function(){
			if(!jQuery(this).hasClass('active')){
				jQuery('.wk_login_register').removeClass('active');
				jQuery(this).addClass('active');
				var index=jQuery(this).index()+1;
				jQuery('.wk_login_content').removeClass('active');
				jQuery('.wk_login_content:nth-child('+index+')').addClass('active');
			}
		});
	});
</script>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
<div class="orderhistory_main_front">
	<div class="wk_login_main">
		<div class="wk_login_header">
		<?php
		if(!isset($customerId)&& !isset(JFactory::getUser()->email)){?>
			<div class="wk_login_register active">
				<span>Log In</span>
			</div>
		<?php
		} else{?>
			<div class="wk_login_register active">
				<span>Register For UvDesk</span>
			</div>
		<?php }?>
		</div>
		<div class="wk_login_content_main">
		<?php
		if(!isset($customerId)&&!isset(JFactory::getUser()->email)){?>
			<div class="wk_login_content active">
				<form action="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=login&task=login.signIn',false)?>" method="post" class="form-validate form-horizontal">			
					<div class="wk_login_content_signin">
						<div class="wk_login_input">
							<div class="wk_input_title">
								<label for="uname">UserName</label>
							</div>
							<div class="wk_input_type">
								<input name="uname" type="text" id="wk_uname" required="true"/>
							</div>
							<div class="wk_input_title">
								<label for="password">Password</label>
							</div>
							<div class="wk_input_type">
								<input name="password" type="password" id="wk_password" required="true"/>
							</div>
							<div class="wk_login_input">
								<div class="wk_input_type">
									<input name="wk_login" type="submit" id="wk_login" class="btn btn-success" value="Sign In" />
								</div>
							</div>		
						</div>
					</div>
				</form>
			</div>
			<?php
		} else{?>
			<div class="wk_login_content active">
				<form action="<?php echo JRoute::_('index.php?option=com_uvdeskwebkul&view=login&task=login.signUp')?>" method="post" class="form-validate form-horizontal" >
					<div class="wk_login_content_register">
						<!-- <div class="wk_login_input">
							<div class="wk_input_title">
								<label for="fname">First Name</label>
							</div>
							<div class="wk_input_type">
								<input name="fname" type="text" id="wk_fname" required="true"/>
							</div>
							<div class="wk_input_title">
								<label for="lname">Last Name</label>
							</div>
							<div class="wk_input_type">
								<input name="lname" type="text" id="wk_lname" required="true"/>
							</div>
							<div class="wk_input_title">
								<label for="email">Email</label>
							</div>
							<div class="wk_input_type">
								<input name="email" type="email" id="wk_lname" required="true"/>
							</div>
							<div class="wk_input_title">
								<label for="captcha"><?php echo $this->form->getLabel('captcha')?></label>
							</div>
							<div class="wk_input_type">
								<?php echo $this->form->getInput('captcha')?>
							</div>
						</div> -->
						<div class="wk_login_input">
							If you want to become a UvDesk customer then click on register button
							<div class="wk_input_type">
								<input title="click here to register as customer of UvDesk" name="wk_register" type="submit" id="wk_register" class="btn" value="Register" />
							</div>
						</div>				
					</div>
				</form>
			</div>
		<?php }?>
		</div>
	</div>	
</div>