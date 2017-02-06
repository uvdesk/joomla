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
class UvdeskwebkulControllerCreateticket extends JControllerlegacy
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
	function createTicket(){
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$request=JRequest::get('post');
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/tickets.json';
		$fileObject = $_FILES['ticketAttachments'];
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
			    "actAsType"=>"customer",
			    "actAsEmail"=>JRequest::getVar('from'),

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
			$this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets'),$response->message);
		}
		else{
			$lineEnd = "\r\n";
			$mime_boundary = md5(time());
			$data = '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="type"' . $lineEnd . $lineEnd;
			$data .= $request['type']. $lineEnd;
			$data .= '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="name"' . $lineEnd . $lineEnd;
			$data .=$request['name'] . $lineEnd;
			$data .= '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="from"' . $lineEnd . $lineEnd;
			$data .= $request['from']. $lineEnd;
			$data .= '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="subject"' . $lineEnd . $lineEnd;
			$data .=$request['subject']. $lineEnd;
			$data .= '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="reply"' . $lineEnd . $lineEnd;
			$data .= $request['reply']. $lineEnd;
			$data = '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="actAsType"' . $lineEnd . $lineEnd;
			$data .= 'customer'. $lineEnd;
			$data = '--' . $mime_boundary . $lineEnd;
			$data .= 'Content-Disposition: form-data; name="actAsEmail"' . $lineEnd . $lineEnd;
			$data .= $request['from']. $lineEnd;
			$data .='--' . $mime_boundary . $lineEnd;
			foreach($customField as $cust) {
				$data .= 'Content-Disposition: form-data; name="customFields"' . $lineEnd . $lineEnd;
				$data .= $cust. $lineEnd;
				$data .= '--' . $mime_boundary . $lineEnd;
			}
			/*	$data .= 'Content-Disposition: form-data; name="customFields"' . $lineEnd . $lineEnd;
			$data .= $customField. $lineEnd;*/
			/*$data .= '--' . $mime_boundary . $lineEnd;*/
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
			$err = curl_error($ch);
			$response=json_decode($response);
			curl_close($ch);
		}

			if($err){
				$this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets'),print_r($err,true));
			}
			else{
				$this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets'),$response->message);
			}
			
	}
}
