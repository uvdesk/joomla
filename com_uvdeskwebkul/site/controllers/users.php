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
        $this->view_list = 'viewtickets';
        parent::__construct();
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
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/customer/'.$customerId.'.json';
        $ch = curl_init($url);
        $json_id=array("id"=>$customerId);
        $headers = array('Authorization: Bearer '.$access_token,);
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
        curl_close($ch);
        echo $customerId;
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
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $jInput=JFactory::getApplication()->input;
        $page=$jInput->get('page', 0, 'INT');        
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/customers.json?page='.$page;
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
