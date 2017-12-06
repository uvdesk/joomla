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
 * [UvdeskwebkulControllerViewticket controller class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class UvdeskwebkulControllerViewticket extends JControllerForm
{
    /**
     * Constructor
     *
     * @throws Exception
     */
    function __construct()
    {
        $this->view_list = 'viewticket';        
        parent::__construct();
        $model=$this->getModel('viewticket');
        $uvdeskCustomer=$model->getMember();
        if (!isset($uvdeskCustomer->customers[0]->email)) {
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
     * Method to get Thread
     *
     * @return Object
     *
     * @since 3.3
     */
    function getThread()
    {
        $jInput=JFactory::getApplication()->input;
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $ticketId=$jInput->get('ticketId', 0, 'INT');  
        $page=$jInput->get('page', 1, 'INT');
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/ticket/'.$ticketId.'/threads.json';
        if (isset($page)&&is_numeric($page)) {
            $url.="?page=".$page;
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
    /**
     * Method to get single ticket
     *
     * @return Object
     *
     * @since 1.0
     */
    function updateTicketSingle()
    {
        $jInput=JFactory::getApplication()->input;
        $apiUrl=$jInput->post->get('apiurl', '', 'STR');
        $agentId=$jInput->post->get('selected', '', 'STR');
        $forselected=$jInput->post->get('forselect', '', 'STR');
        $ticketId=$jInput->get('ticketId', 0, 'INT');
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/'.$apiUrl;
        $ch = curl_init($url);
        if ($forselected=='assignment') {
            $json_id=array("id"=>$agentId);
        } elseif ($forselected=='deleted') {
            $json_id=array("id"=>$agentId);
        }
        $headers = array('Authorization: Bearer '.$access_token,);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
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
        $response->message;
        echo json_encode($response);
        JFactory::getApplication()->close();
    }
    /**
     * Method to update ticket
     *
     * @return Object
     *
     * @since 1.0
     */
    function updatePatch()
    {
        $jInput=JFactory::getApplication()->input;
        $apiUrl=$jInput->post->get('apiurl', '', 'STR');
        $agentId=$jInput->post->get('selected', '', 'STR');
        $forselected=$jInput->post->get('forselect', '', 'STR');
        $ticketId=$jInput->get('ticketId', 0, 'INT');
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/'.$apiUrl;
        $ch = curl_init($url);
        if ($forselected=='starred') {
            $json_id=array("editType"=>"star");
        } elseif ($forselected=='groups') {
            $json_id=array("editType"=>"group","value"=>$agentId);

        } elseif ($forselected=='labeled') {
            $json_id=array("editType"=>"label","value"=>$agentId);

        } elseif ($forselected=='priority') {
            $json_id=array("editType"=>"priority","value"=>$agentId);

        } elseif ($forselected=='status') {
            $json_id=array("editType"=>"status","value"=>$agentId);
        }
        $headers = array('Authorization: Bearer '.$access_token,);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
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
        $response->id=$ticketId;       
        echo json_encode($response);
        JFactory::getApplication()->close();        
    }
    /**
     * Method to reply a ticket
     *
     * @return Object
     *
     * @since 1.0
     */
    function postReply()
    {
        $jInput=JFactory::getApplication()->input;
        $ticketId=$jInput->get('id', 0, 'INT');
        $model=$this->getModel();
        $ticketData=$model->getThread($ticketId);
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $postFiles=$_FILES['replyAttachments'];
        $lineEnd = "\r\n";
        $mime_boundary = md5(time());
        $data='';
        $data = '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="threadType"' . $lineEnd . $lineEnd;
        $data .= "reply". $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="reply"' . $lineEnd . $lineEnd;
        $data .= $jInput->post->get('content', '', 'RAW'). $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="status"' . $lineEnd . $lineEnd;
        $data .= $jInput->post->get('replyStatus', 1, 'INT'). $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="actAsType"' . $lineEnd . $lineEnd;
        $data .='customer'. $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="actAsEmail"' . $lineEnd . $lineEnd;
        $data .= JFactory::getUser()->email. $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        for ($i=0;$i<count($postFiles['name']);$i++) {
            if (strlen($postFiles['name'][$i])&&$postFiles['error'][$i]==0&&$postFiles['size'][$i]>0) {
                $fileType = $postFiles['type'][$i];
                $fileName = $postFiles['name'][$i];
                $fileTmpName =$postFiles['tmp_name'][$i];
                $data .= 'Content-Disposition: form-data; name="attachments[]"; filename="' .$fileName . '"' . $lineEnd;
                $data .= "Content-Type: $fileType" . $lineEnd . $lineEnd;
                $data .= file_get_contents($fileTmpName) . $lineEnd;
                $data .= '--' . $mime_boundary . $lineEnd;                
            }
        }
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/ticket/'.$jInput->post->get('ticketId', 0, 'INT').'/threads.json';
        $ch = curl_init($url);
        $headers = array(
            "Authorization: Bearer ".$access_token,
            "Content-type: multipart/form-data; boundary=" . $mime_boundary,
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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
        $this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewticket&id='.$ticketId, false), $response->message);
    }
    
}
