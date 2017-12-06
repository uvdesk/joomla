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
 * [UvdeskwebkulControllerCreateticket controller class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
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
    /**
     * Method to create ticket
     *
     * @return null
     *
     * @since 3.3
     */
    function createTicket()
    {
        JSession::checkToken() or jexit('Invalid Token');
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $jInput=JFactory::getApplication()->input;
        $request=$jInput->post->getArray();
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
                $file .= file_get_contents($fileTmpName) . $lineEnd;
                $customField[$key]=$file;
                $customField['filename']=$fileName;
                $customField['filetype']=$fileType;
            }
        }
        if (count($request['customFields'])) {
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
        $data .=$jInput->post->get('subject', '', 'SAFE_HTML'). $lineEnd;
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
                    $data.="Content-Type:'".$customField['filetype']."'" . $lineEnd . $lineEnd;
                }
                if (is_numeric($key)) {
                    $data.= $lineEnd . $lineEnd;
                    $data .= $cust. $lineEnd;
                    $data .= '--' . $mime_boundary . $lineEnd;
                }
            }
        }
        for ($i=0;$i<count($fileObject['name']);$i++) {
            if (strlen($fileObject['name'][$i])&&$fileObject['error'][$i]==0&&$fileObject['size'][$i]>0) {
                $fileType = $fileObject['type'][$i];
                $fileName = $fileObject['name'][$i];
                $fileTmpName =$fileObject['tmp_name'][$i];
                $data .= 'Content-Disposition: form-data; name="attachments[]"; filename="' .$fileName . '"' . $lineEnd;
                $data .= "Content-Type: $fileType" . $lineEnd . $lineEnd;
                $data .= file_get_contents($fileTmpName) . $lineEnd;
                $data .= '--' . $mime_boundary . $lineEnd;
            }
        }        
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
            if (JFactory::getUser()->id!=0) {
                $this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets', false), $response->message);
            } else {
                $this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=createticket', false), $response->message);
            }
            
        }
            
    }
}
