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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
/**
 * [UvdeskwebkulControllerUsers controller class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
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
        $this->view_list = 'users';
        parent::__construct();
        $model=$this->getModel();
        $checkCustomer=$model->getMember();
        if (!isset($checkCustomer->customers[0]->email)) {
            $params= JComponentHelper::getParams('com_uvdeskwebkul');
            $accessToken=$params->get('accesstoken');
            $subDomain=$params->get('wksubdomain');
            $access_token =$accessToken;
            $company_domain =$subDomain;
            $user=JFactory::getUser();
            $url = 'https://'.$company_domain.'.uvdesk.com/en/api/customers.json';
            $data = json_encode(
                array(
                "firstName" => $user->name,
                "lastName" => '',
                "email" =>$user->email)
            );
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
        }
    }
    /**
     * Method to Delete Customer
     *
     * @return boolean
     */
    function deleteCustomer()
    {
        $jInput=JFactory::getApplication()->input;
        $customerId=$jInput->get('customerId', 0, 'INT');
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $status=$jInput->get('status', '', 'STR');
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/customer/'.$customerId.'.json';
        $ch = curl_init($url);
        $json_id=array("id"=>$customerId);
        $headers = array('Authorization: Bearer '.$access_token,);
        $headers = array('Authorization: Bearer '.$access_token,);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_id));
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
        JFactory::getApplication()->close();
    }
    /**
     * Method to star Customer
     *
     * @return boolean
     */
    function starCustomer()
    {
        $jInput=JFactory::getApplication()->input;
        $customerId=$jInput->get('customerId', 0, 'INT');
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $status=$jInput->get('status', '', 'STR');
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/customer/'.$customerId.'.json';
        $ch = curl_init($url);
        $json_id=array("editType"=>"star");
        $headers = array('Authorization: Bearer '.$access_token,);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_id));
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
        curl_close($ch);
        $response->customerId=$customerId;
        echo json_encode($response);
        JFactory::getApplication()->close();
    }
    /**
     * Method to get customers.
     * 
     * @return object
     *
     * @throws Exception
     */
    public function getCustomers()
    {
        $jInput=JFactory::getApplication()->input;
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $page=$jInput->post->get('page', 1, 'INT');
        $sort=$jInput->post->get('sort', '', 'STR');
        $sort=explode(' ', $sort);
        $direction=$sort[1];
        $sort=$sort[0];
        $search=$jInput->post->get('search', '', 'STR');
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/customers.json?page='.$page;
        if (JString::strlen(trim($search))) {
            $url.='&search='.$search;
        } 
        if (isset($sort) && isset($direction)) {
            $url.='&sort='.$sort.'&direction='.$direction;
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
        JFactory::getApplication()->close();
    }

}
