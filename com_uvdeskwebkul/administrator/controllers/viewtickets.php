<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Uvdeskwebkul
 * @author     webkul <support@webkul.com>
 * @copyright  Copyright (C) 2010 webkul.com. All Rights Reserved
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Viewtickets list controller class.
 *
 * @since  1.6
 */
class UvdeskwebkulControllerViewtickets extends JControllerAdmin
{
	/**
	 * Method to clone existing Viewtickets
	 *
	 * @return void
	 */
	//public $app=JFactory::getApplication();
	public function duplicate()
	{
		// Check for request forgeries
		Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$pks = $this->input->post->get('cid', array(), 'array');
		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_UVDESKWEBKUL_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Jtext::_('COM_UVDESKWEBKUL_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_uvdeskwebkul&view=viewtickets');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since    1.6
	 */
	public function getModel($name = 'viewticket', $prefix = 'UvdeskwebkulModel', $config = array()){
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	function createTicket(){
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$request=JRequest::get('post');
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/tickets.json';
		$fileObject = $_FILES['attachments'];
		$customField=array();
		if(count($request['customFields'])){
			foreach ($request['customFields'] as $key=>$value) {
				$customField[$key]=$value;
			}
		}
		if(!strlen($fileObject['name'][0])){
			$data = json_encode(array(
			    "name" => JRequest::getVar('name'),
			    "from" => JRequest::getVar('from'),
			    "subject" => JRequest::getVar('subject'),
			    "reply" => JRequest::getVar('reply'),
			    "type" => JRequest::getVar('type'),
			    "customFields"=>$customField,

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
		}
		else{
			$lineEnd = "\r\n";
			$mime_boundary = md5(time());
			$data = '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="type"' . $lineEnd . $lineEnd;
			$data .= JRequest::getVar('type'). $lineEnd;
			$data .= '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="name"' . $lineEnd . $lineEnd;
			$data .=JRequest::getVar('name') . $lineEnd;
			$data .= '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="from"' . $lineEnd . $lineEnd;
			$data .= JRequest::getVar('from'). $lineEnd;
			$data .= '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="subject"' . $lineEnd . $lineEnd;
			$data .=JRequest::getVar('subject'). $lineEnd;
			$data .= '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="reply"' . $lineEnd . $lineEnd;
			$data .= JRequest::getVar('reply'). $lineEnd;
			/*foreach ($customField as $cust) {
				$data .= '--' . $mime_boundary . $lineEnd;
				$data .= 'Content-Disposition: form-data; name="customFields"' . $lineEnd . $lineEnd;
				$data .= $cust. $lineEnd;	
			}*/
			$data .= '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="customFields"' . $lineEnd . $lineEnd;
			$data .= $customField. $lineEnd;
			$data .= '--' . $mime_boundary . $lineEnd;
			for($i=0;$i<count($fileObject['name']);$i++) {
				$fileType = $fileObject['type'][$i];
				$fileName = $fileObject['name'][$i];
				$fileTmpName =$fileObject['tmp_name'][$i];
				$data .= 'Content-Disposition: form-data; name="attachments[]"; filename="' .$fileName . '"' . $lineEnd;
				$data .= "Content-Type: $fileType" . $lineEnd . $lineEnd;
				$data .= file_get_contents($fileTmpName) . $lineEnd;
				$data .= '--' . $mime_boundary . $lineEnd;
			}
 			$headers = array(
				"Authorization: Bearer ".$access_token,
				"Content-type: multipart/form-data; boundary=" . $mime_boundary,
			);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$server_output = curl_exec($ch);
			$info = curl_getinfo($ch);
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$headers = substr($server_output, 0, $header_size);
			$response = substr($server_output, $header_size);
			$err = curl_error($curl);
			$response=json_decode($response);
			curl_close($ch);
		}

			if($err){
				$this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets'),$err);
			}
			else{
				$this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets'),$response->message);
			}
			
	}
	//uvdesk.com/en/api/filters.json?group=1&status=1&priority=1 
	function apiCall(){
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$status=JRequest::getVar('status');
		$label=JRequest::getVar('label');
		$customerId=JRequest::getVar('customerId');
		if(!isset($label)){
			$label='all';
		}
		if(isset($customerId)&&strlen($customerId)){
			$url = 'https://'.$company_domain.'.uvdesk.com/en/api/tickets.json?'.$label.'&status='.$status.'&customer='.$customerId;
		}else{
			$url = 'https://'.$company_domain.'.uvdesk.com/en/api/tickets.json?'.$label.'&status='.$status;
		}
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
	function updateTicket(){
		$apiParams=JRequest::getVar('params');
		$apiUrl=JRequest::getVar('apiurl');
		$agentId=JRequest::getVar('selected');
		$forselected=JRequest::getVar('forselect');
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/'.$apiUrl;
		$ch = curl_init($url);
		if($forselected=='assignment'){
			$json_id=array("ids"=>$apiParams,"agentId"=>$agentId);
		}
		elseif($forselected=='status'){
			$json_id=array("ids"=>$apiParams,"statusId"=>$agentId);
		}
		elseif($forselected=='groups'){
			$json_id=array("ids"=>$apiParams,"groupId"=>$agentId);
		}
		elseif($forselected=='priority'){
			$json_id=array("ids"=>$apiParams,"priorityId"=>$agentId);
		}
		elseif($forselected=='label'){
			$json_id=array("ids"=>$apiParams,"labelId"=>$agentId);
		}
		elseif($forselected=='deleted'){
			$json_id=array("ids"=>$apiParams);
		}
		$headers = array('Authorization: Bearer '.$access_token,);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
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
		echo $response->message;
		exit();
	}
	

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		$input = JFactory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);
		$model = $this->getModel();
		$return = $model->saveorder($pks, $order);
		if ($return)
		{
			echo "1";
		}

		JFactory::getApplication()->close();
	}
}
