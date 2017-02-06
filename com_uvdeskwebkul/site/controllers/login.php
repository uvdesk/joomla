<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Uvdeskwebkul
 * @author     webkul <support@webkul.com>
 * @copyright  Copyright (C) 2010 webkul.com. All Rights Reserved
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Viewticket controller class.
 *
 * @since  1.6
 */
jimport( 'joomla.application.component.model'); 
class UvdeskwebkulControllerLogin extends JControllerLegacy
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'viewtickets';
		parent::__construct();
	}

	function signUp(){
	/*	$app   = JFactory::getApplication();
		$model = $this->getModel('Registration', 'UsersModel');
		JModelLegacy::addIncludePath (JPATH_SITE .'/components/com_users/models');
        $model =JModelLegacy::getInstance('Registration', 'UsersModel');
		$requestData = $this->input->post->get('jform', array(), 'array');
		$form = $model->getForm();
		$postData=JRequest::get('POST');
		$parts = explode("@", $postData['email']);
		$username = $parts[0];
		//$data = $model->validate($form, $requestData);
		$data=array();
		$salt = JUserHelper::genRandomPassword(32);
		$wk_random_password_final=JUserHelper::genRandomPassword(8);
		$crypt = JUserHelper::getCryptedPassword($wk_random_password_final, $salt);
		$password = $crypt.':'.$salt;
		//$wk_random_password_final for mail
		$data['password1']=$password;
		$data['password1']=$password;
		$data['email1']=$postData['email'];
		$data['email2']=$postData['email'];
		$return = $model->register($data);
		if ($return === false){
			$app->setUserState('com_users.registration.data', $data);
			$this->setMessage($model->getError(), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=login', false));
			return false;
		}
	*/
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$user=JFactory::getUser();
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/customers.json';
		$data = json_encode(array(
		    "firstName" => $user->name,
		    "lastName" => $user->name,
		    "email" =>$user->email,
		   ));
		$ch = curl_init($url);
		$headers = array(
		    'Authorization: Bearer '.$access_token,
		    'Content-type: application/json'
		);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$server_output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($server_output, 0, $header_size);
		$response = substr($server_output, $header_size);
		curl_close($ch);
		$response=json_decode($response);
		$this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets',false),$response->message);
	}
	function signIn(){
		$app    = JFactory::getApplication();
		$input  = $app->input;
		$method = $input->getMethod();
		$data = array();
		$data['username']  = $input->$method->get('uname', '', 'USERNAME');
		$data['password']  = $input->$method->get('password', '', 'RAW');
		$data['return'] = '';
		if (empty($data['return']))
		{
			$data['return'] = 'index.php?option=com_uvdeskwebkul&view=viewtickets';
		}
		$app->setUserState('users.login.form.return', $data['return']);
		$options = array();
		$options['remember'] = $this->input->getBool('remember', false);
		$options['return']   = $data['return'];
		$credentials = array();
		$credentials['username']  = $data['username'];
		$credentials['password']  = $data['password'];
		$credentials['secretkey'] = '';
		if (true !== $app->login($credentials, $options))
		{
			$data['username'] = '';
			$data['password'] = '';
			$data['secretkey'] = '';
			$app->setUserState('users.login.form.data', $data);
			$app->enqueueMessage("Wrong UserName Or Password",'error');
			$app->redirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=login',false));
		}
		$app->setUserState('users.login.form.data', array());
		$app->redirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets', false));
	}

}