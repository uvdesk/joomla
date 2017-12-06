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

jimport('joomla.application.component.modeladmin');
/**
 * [UvdeskwebkulModelDownloads Model class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class UvdeskwebkulModelDownloads extends JModelLegacy
{
    /**
     * Method to get data.
     *
     * @return object
     *
     * @throws Exception
     */
    public function getData()
    {
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $jInput=JFactory::getApplication()->input;
        $attachment=$jInput->get('attachmentId', 0, 'INT');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $format=$jInput->get('fileformat', '', 'STR');
        $filename=$jInput->get('filename', '', 'STR');
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/ticket/attachment/'.$attachment.'.json';
        header('Location: '.$url.'?access_token='.$accessToken);
       
    }
    /**
     * Method to get data.
     *
     * @param int $ticketId ticket id
     * 
     * @return object
     *
     * @throws Exception
     */
    public function getThread($ticketId='')
    {
        $jInput=JFactory::getApplication()->input;
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/ticket/'.$ticketId.'/threads.json';
        $ch = curl_init($url);
        $headers = array('Authorization: Bearer '.$access_token,);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($output, 0, $header_size);
        $response = substr($output, $header_size);
        curl_close($ch);
        echo $response;
        //return $response;
    }
}
