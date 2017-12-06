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
 * [UvdeskwebkulModelUser Model class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class UvdeskwebkulModelUser extends JModelForm
{
    /**
     * Method to get customers.
     *
     * @param mixed $customerId customerId
     * 
     * @return object
     *
     * @throws Exception
     */
    public function getCustomer($customerId)
    {
        $params= JComponentHelper::getParams('com_uvdeskwebkul');
        $accessToken=$params->get('accesstoken');
        $subDomain=$params->get('wksubdomain');
        $access_token =$accessToken;
        $company_domain =$subDomain;
        $url = 'https://'.$company_domain.'.uvdesk.com/en/api/customer/'.$customerId.'.json';
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
    /**
     * Method to get customers.
     *
     * @param array $data     data
     * @param mixed $loadData loadData
     * 
     * @return object
     *
     * @throws Exception
     */
    public function getForm($data = array(), $loadData = true)
    {

        $form = $this->loadForm('com_uvdeskwebkul.user', 'customer', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

}
