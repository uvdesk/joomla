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
class UvdeskwebkulControllerViewtickets extends JControllerAdmin
{
    /**
     * Constructor
     *
     * @throws Exception
     */
    function __construct()
    {
        $this->view_list = 'viewtickets';        
        parent::__construct();
        $model=$this->getModel('viewtickets');
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
            $response=json_decode($response);
        }
    }
    /**
     * Method to clone existing Viewtickets
     *
     * @return void
     */
    public function duplicate()
    {
        // Check for request forgeries
        Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $pks = $this->input->post->get('cid', array(), 'array');
        try
        {
            if (empty($pks)) {
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
     * @param string $name   Optional. Model name
     * @param string $prefix Optional. Class prefix
     * @param array  $config Optional. Configuration array for model
     *
     * @return object The Model
     *
     * @since 1.6
     */
    public function getModel($name = 'viewticket', $prefix = 'UvdeskwebkulModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }
    /**
     * Proxy for create Ticket.
     *
     * @return object The Model
     *
     * @since 1.0
     */
    public function createTicket()
    {
        //JSession::checkToken() or jexit('Invalid Token');
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $jInput=JFactory::getApplication()->input;
        $fileId=$jInput->post->get('fileid', array(), 'ARRAY');
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/tickets.json';
        $fileObject = $_FILES['ticketAttachments'];
        $customField=array();
        $lineEnd = "\r\n";
        $mime_boundary = md5(time());
        $fileRequest=$_FILES;
        foreach ($fileRequest['customFields']['name'] as $key=>$value) {
            if (strlen($value)) {
                $file='';
                $fileType = $fileRequest['customFields']['type'][$key];
                $fileName =$fileRequest['customFields']['name'][$key];
                $fileTmpName =$fileRequest['customFields']['tmp_name'][$key];
                //$file='--' . $mime_boundary . $lineEnd;
                // $file .= 'Content-Disposition: form-data; name="customFields[]"; filename="' .$fileName . '"' . $lineEnd;
                // $file .= "Content-Type: $fileType" . $lineEnd . $lineEnd;
                $file .= file_get_contents($fileTmpName) . $lineEnd;
                // $file .= '--' . $mime_boundary . $lineEnd;
                //echo ;
                $customField[$key]=$file;
                $customField['filename']=$fileName;
                $customField['filetype']=$fileType;
            }
        }
        if (count($jInput->post->get('customFields', '', 'array()'))) {
            foreach ($request['customFields'] as $key=>$value) {              
                $customField[$key]=$value;              
            }
        }
        
        $data = '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="type"' . $lineEnd . $lineEnd;
        $data .= $jInput->post->get('type', '', 'STR'). $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="name"' . $lineEnd . $lineEnd;
        $data .=$jInput->post->get('name', '', 'STR') . $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="from"' . $lineEnd . $lineEnd;
        $data .= $jInput->post->get('from', '', 'STR'). $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="subject"' . $lineEnd . $lineEnd;
        $data .=$jInput->post->get('subject', '', 'STR'). $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        $data .= 'Content-Disposition: form-data; name="reply"' . $lineEnd . $lineEnd;
        $data .= $jInput->post->get('reply', '', 'STR'). $lineEnd;
        $data .= '--' . $mime_boundary . $lineEnd;
        
        foreach ($customField as $key=>$cust) {
            if (strlen($cust)) {
                if (is_numeric($key)) {
                    $data .= 'Content-Disposition: form-data; name="customFields['.$key.']"';
                }
                if ($key=='filename') {
                        $data.'filename="' .$customField['filename']. '"';
                }
                if ($key=='filetype') {
                    $data.="Content-Type: '".$customField['filetype']."'" . $lineEnd . $lineEnd;
                }
                if (is_numeric($key)) {
                    $data.= $lineEnd . $lineEnd;
                    $data .= $cust. $lineEnd;
                    $data .= '--' . $mime_boundary . $lineEnd;
                }
            }
        }
        $maxFileUploads=0;
        $maxPostSize=0;
        $maxPostSize=filter_var(ini_get('post_max_size'), FILTER_SANITIZE_NUMBER_INT);
        $maxFileUploads=filter_var(ini_get('max_file_uploads'), FILTER_SANITIZE_NUMBER_INT);
        $size=0;        
        if (count($fileObject['name'])<=$maxFileUploads) {
            
            for ($i=0;$i<count($fileObject['name']);$i++) {
                if (strlen($fileObject['name'][$i])&&$fileObject['error'][$i]==0&&$fileObject['size'][$i]>0) {
                    $size+=$fileObject['size'][$i];
                    $fileType = $fileObject['type'][$i];
                    $fileName = $fileObject['name'][$i];
                    $fileTmpName =$fileObject['tmp_name'][$i];
                    $data .= 'Content-Disposition: form-data; name="attachments[]"; filename="' .$fileName . '"' . $lineEnd;
                    $data .= "Content-Type: $fileType" . $lineEnd . $lineEnd;
                    $data .= file_get_contents($fileTmpName) . $lineEnd;
                    $data .= '--' . $mime_boundary . $lineEnd;
                }
            }
            $size=$size/1000000;
            if ($size>$maxPostSize) {
                $this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets', false), JText::_('COM_UVDESKWEBKUL_MAX_POST_SIZE'), 'error');
            }
        } else {
             $this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets', false), JText::sprintf('COM_UVDESKWEBKUL_MAX_FILE_UPLOAD', $maxFileUploads), 'error');
        }
        // print_r($data);die;
        
        $headers = array(
            "Authorization: Bearer ".$access_token,
            "Content-type: multipart/form-data; boundary=" . $mime_boundary,
        );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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
        //}
        if ($err) {
            $this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets', false), print_r($err, true));
        } else {
            $this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets', false), $response->message);
        }
            
    }
    /**
     * Proxy for create Ticket.
     *
     * @return object The Model
     *
     * @since 1.0
     */
    function apiCall()
    {
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $jInput=JFactory::getApplication()->input;
        $status=$jInput->get('status', 1, 'STR');
        $label=$jInput->get('label', null, 'STR');
        $inputCookie  = JFactory::getApplication()->input->cookie;
        $inputCookie->set('globalTicketStatus', $status, 0);
        $customerId=$jInput->get('customerId', null, 'INT');
        $page='';
        $filter=$jInput->get('filter', '', 'JSON');
        $page=$jInput->post->get('page', 0, 'INT');        
        if (!isset($label)) {
            $label='all';
        }
        if (isset($customerId)&&strlen($customerId)) {
            $url = 'https://'.$company_domain.'.uvdesk.com/en/api/tickets.json?'.$label.'&status='.$status.'&customer='.$customerId;
        } else {
            $url = 'https://'.$company_domain.'.uvdesk.com/en/api/tickets.json?'.$label.'&status='.$status.'&direction=desc';
        }
        if (strlen($filter)>2) {
            $filter=json_decode($filter);
        }        
        if (isset($filter)&&count($filter)) {
            if (isset($filter->agent)) {
                $url.="&agent=".$filter->agent;
            }
            if (isset($filter->group)) {
                $url.="&group=".$filter->group;
            }
            if (isset($filter->type)) {
                $url.="&type=".$filter->type;
            }
            if (isset($filter->search)) {
                $url.="&search=".$filter->search;
            }
            
        }
        if (is_numeric($page)) {
            $url.="&page=".$page;
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
     * Method for updateTicket
     *  
     * @return string
     */
    function updateTicket()
    {
        $jInput=JFactory::getApplication()->input;
        $apiParams=$jInput->post->get('params');
        $apiParams=array_unique($apiParams);
        $apiUrl=$jInput->post->get('apiurl', '', 'STR');
        $deleteForever=$jInput->post->get('deleteForever', 0, 'INT');
        $agentId=$jInput->post->get('selected', '', 'STR');
        $globalStatus=$jInput->post->get('globalStatus', 1, 'INT');
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
        } elseif ($forselected=='restore') {
            $json_id=array("ids"=>$apiParams);
        }
        $headers = array('Authorization: Bearer '.$access_token,);
        if ($deleteForever) {
            $apiUrl="tickets.json";
            $url = 'https://'.$company_domain.'.uvdesk.com/en/api/'.$apiUrl;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        }        
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
}
