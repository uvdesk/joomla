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
 * Viewticket controller class.
 *
 * @since 1.6
 */
jimport('joomla.application.component.model');
/**
 * [UvdeskwebkulControllerLogin controller class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class UvdeskwebkulControllerLogin extends JControllerLegacy
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
     * Method to Signup
     *
     * @return null
     *
     * @since 3.3
     */
    function signUp()
    {
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
            "email" =>$user->email,)
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
        $this->setRedirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets', false), $response->message);
    }
    /**
     * Method to Login
     *
     * @return null
     *
     * @since 3.3
     */
    function signIn()
    {
        $app    = JFactory::getApplication();
        $input  = $app->input;
        $method = $input->getMethod();
        $data = array();
        $data['username']  = $input->$method->get('uname', '', 'USERNAME');
        $data['password']  = $input->$method->get('password', '', 'RAW');
        $data['return'] = '';
        if (empty($data['return'])) {
            $data['return'] = 'index.php?option=com_uvdeskwebkul&view=viewtickets';
        }
        $app->setUserState('users.login.form.return', $data['return']);
        $options = array();
        $options['remember'] = $this->input->getBool('remember', false);
        $options['return']   = $data['return'];
        $credentials = array();
        $credentials['username']  = $data['username'];
        $credentials['password']  = $data['password'];
        $credentials['secretkey'] = '';
        if (true !== $app->login($credentials, $options)) {
            $data['username'] = '';
            $data['password'] = '';
            $data['secretkey'] = '';
            $app->setUserState('users.login.form.data', $data);
            //$app->enqueueMessage("Wrong UserName Or Password", 'error');
            $app->redirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=login', false));
        }
        $app->setUserState('users.login.form.data', array());
        $app->redirect(JRoute::_('index.php?option=com_uvdeskwebkul&view=viewtickets', false));
    }

}