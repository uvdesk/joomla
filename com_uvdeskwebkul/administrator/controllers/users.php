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
class UvdeskwebkulControllerUsers extends JControllerForm
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
	function deleteCustomer(){
		$customerId=JRequest::getVar('customerId');
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$status=JRequest::getVar('status');
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/customer/'.$customerId.'.json';
		$ch = curl_init($url);
		$json_id=array("id"=>$customerId);
		$headers = array('Authorization: Bearer '.$access_token,);
		$headers = array('Authorization: Bearer '.$access_token,);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($json_id));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($output, 0, $header_size);
		$response = substr($output, $header_size);
		curl_close($ch);
		echo $response;
		exit();
	}
	function starCustomer(){
		$customerId=JRequest::getVar('customerId');
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$status=JRequest::getVar('status');
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/customer/'.$customerId.'.json';
		$ch = curl_init($url);
		$json_id=array("id"=>$customerId);
		$headers = array('Authorization: Bearer '.$access_token,);
		$headers = array('Authorization: Bearer '.$access_token,);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
		curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($json_id));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($output, 0, $header_size);
		$response = substr($output, $header_size);
		curl_close($ch);
		echo $customerId;
		exit();
	}

}
