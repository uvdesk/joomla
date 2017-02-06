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
class UvdeskwebkulControllerViewticket extends JControllerForm
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
	function getThread(){
		$ticketId=JRequest::getVat('ticketId');
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$status=JRequest::getVar('status');
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/ticket/'.$ticketId.'/threads.json';
		$ch = curl_init($url);
		$headers = array('Authorization: Bearer '.$access_token,);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$headers = substr($output, 0, $header_size);
		$response = substr($output, $header_size);
		curl_close($ch);
		echo $response;
		exit();
	}
	function updateTicketSingle(){
		$apiParams=JRequest::getVar('params');
		$apiUrl=JRequest::getVar('apiurl');
		$agentId=JRequest::getVar('selected');
		$forselected=JRequest::getVar('forselect');
		$ticketId=JRequest::getVar('ticketId');
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/'.$apiUrl;
		$ch = curl_init($url);
		if($forselected=='assignment'){
			$json_id=array("id"=>$agentId);
		}
		elseif($forselected=='deleted'){
			$json_id=array("id"=>$agentId);
		}
		$headers = array('Authorization: Bearer '.$access_token,);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
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
		$response=json_decode($response); 
		print_r($response);die;
		echo $response->message;
		exit();
	}
	function updatePatch(){
		$apiParams=JRequest::getVar('params');
		$apiUrl=JRequest::getVar('apiurl');
		$agentId=JRequest::getVar('selected');
		$forselected=JRequest::getVar('forselect');
		$ticketId=JRequest::getVar('ticketId');
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/'.$apiUrl;
		$ch = curl_init($url);
		if($forselected=='starred'){
			$json_id=array("editType"=>"star");
		}
		elseif($forselected=='groups'){
			$json_id=array("editType"=>"group","value"=>$agentId);

		}
		elseif($forselected=='labeled'){
			$json_id=array("editType"=>"label","value"=>$agentId);

		}
		elseif($forselected=='priority'){
			$json_id=array("editType"=>"priority","value"=>$agentId);

		}
		elseif($forselected=='status'){
			$json_id=array("editType"=>"status","value"=>$agentId);

		}
		/*echo json_encode($json_id);die;*/
		$headers = array('Authorization: Bearer '.$access_token,);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($json_id));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE); 
		$headers = substr($output, 0, $header_size);
		$response = substr($output, $header_size);   
		$response=json_decode($response); 
		print_r($response);die;
		
	}
	function postReply(){
		$ticketId=JRequest::getVar('id');
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$postData=JRequest::get('POST');
		$postFiles=JRequest::get('FILES');
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/ticket/'.$postData['ticketId'].'/threads.json';
		$ch = curl_init($url);
		$json_id=array("threadType"=>"reply", "reply"=>$postData['content'],"status"=>$postData['replyStatus'],"files"=>'phpmSywKJ');
		$headers = array('Authorization: Bearer '.$access_token,);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // note Assignement here
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($json_id));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE); 
		$headers = substr($output, 0, $header_size);
		$response = substr($output, $header_size);   
		$response=json_decode($response); 
		$this->setRedirect('index.php?option=com_uvdeskwebkul&view=viewtickets',$response->message);
	}
}
