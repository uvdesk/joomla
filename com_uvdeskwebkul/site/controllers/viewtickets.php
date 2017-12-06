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
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;
/**
 * [UvdeskwebkulControllerViewtickets controller class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class UvdeskwebkulControllerViewtickets extends JControllerLegacy
{
    /**
     * Method to get model
     *  
     * @param string $name   name of model
     * @param string $prefix prefix of model
     * @param array  $config Config of model
     *
     * @return void
     */
    public function getModel($name = 'viewticket', $prefix = 'UvdeskwebkulModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }
    /**
     * Method for api call
     *  
     * @return string
     */
    function apiCall()
    {
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $jInput=JFactory::getApplication()->input;
        $status=$jInput->post->get('status', 0, 'INT');
        $customerId=$jInput->post->get('cid', 0, 'INT');
        $sort=$jInput->post->get('sort', '', 'STR');        
        $search=$jInput->post->get('search', '', 'STR');
        $page=$jInput->post->get('page', 1, 'INT');
        $sort=explode(' ', $sort);
        if ($status==0) {
            $url = 'https://'.$company_domain.'.uvdesk.com/en/api/tickets.json?actAsType=customer&actAsEmail='.JFactory::getUser()->email.'&customer='.$customerId.'&sort='.$sort[0].'&direction='.$sort[1].'&page='.$page.'&search='.$search;
        } else {
            $url = 'https://'.$company_domain.'.uvdesk.com/en/api/tickets.json?customer='.$customerId.'&status='.$status.'&sort='.$sort[0].'&direction='.$sort[1].'&page='.$page.'&search='.$search;
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
        $result=json_decode($response);
        $i=0;
        foreach ($result->tickets as $res) {
            $result->tickets{$i}->id=JRoute::_('index.php?option=com_uvdeskwebkul&view=viewticket&id='.$res->incrementId, false);
            $i++;
        }
        echo json_encode($result);
        JFactory::getApplication()->close();

    }
    
    /**
     * Method for updateTicket
     *  
     * @return string
     */
    function updateTicket()
    {
        $jInput=JFactory::getApplication()->input;
        $apiParams=$jInput->post->get('params', '', 'STR');
        $apiUrl=$jInput->post->get('apiurl', '', 'STR');
        $agentId=$jInput->post->get('selected', 0, 'INT');
        $forselected=$jInput->post->get('forselect', '', 'STR');
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/'.$apiUrl;
        $ch = curl_init($url);
        if ($forselected=='assignment') {
            $json_id=array("ids"=>$apiParams,"agentId"=>$agentId);
        } elseif ($forselected=='status') {
            $json_id=array("ids"=>$apiParams,"statusId"=>$agentId);
        } elseif ($forselected=='groups') {
            $json_id=array("ids"=>$apiParams,"groupId"=>$agentId);
        } elseif ($forselected=='priority') {
            $json_id=array("ids"=>$apiParams,"priorityId"=>$agentId);
        } elseif ($forselected=='label') {
            $json_id=array("ids"=>$apiParams,"labelId"=>$agentId);
        } elseif ($forselected=='deleted') {
            $json_id=array("ids"=>$apiParams);
        }
        $headers = array('Authorization: Bearer '.$access_token,);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($json_id));
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
        JFactory::getApplication()->close();
    }

    /**
     * Method for saveOrderAjax
     *  
     * @return string
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
        if ($return) {
            echo "1";
        }

        JFactory::getApplication()->close();
    }
    /**
     * Method to get Status of ticket
     *  
     * @return string
     */
    public function getTicketStatus()
    {
        $jInput = JFactory::getApplication()->input;
        $ticketId=$jInput->get('ticketId', 0, 'INT');
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/ticket/'.$ticketId.".json";
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
        echo $response;
        JFactory::getApplication()->close();
    }
}
