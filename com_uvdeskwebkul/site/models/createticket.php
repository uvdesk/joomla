<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Uvdeskwebkul
 * @author     webkul <support@webkul.com>
 * @copyright  Copyright (C) 2010 webkul.com. All Rights Reserved
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Uvdeskwebkul records.
 *
 * @since  1.6
 */
class UvdeskwebkulModelCreateticket extends JModelList
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}
	function getTicketType(){
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$status=JRequest::getVar('status');
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/ticket-types.json';
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
		return $response;
	}
	function customFields(){
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$status=JRequest::getVar('status');
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/custom-fields.json';
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
		return $response;
	}
	function getApiUser($email){
		$params= JComponentHelper::getParams('com_uvdeskwebkul');
		$accessToken=$params->get('accesstoken');
		$subDomain=$params->get('wksubdomain');
		$access_token =$accessToken;
		$company_domain =$subDomain;
		$status=JRequest::getVar('status');
		$url = 'https://'.$company_domain.'.uvdesk.com/en/api/customers.json?email='.$email.'&isActive=1';
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
		return json_decode($response);
	}


}
