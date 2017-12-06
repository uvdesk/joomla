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
defined('_JEXEC') or die('');
jimport('joomla.installer.installer');
/**
 * [Com_UvdeskwebkulInstallerScript script class]
 *
 * @category Component
 * @package  Joomla
 * @author   WebKul software private limited <support@webkul.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link     Technical Support:  webkul.uvdesk.com
 */
class Com_UvdeskwebkulInstallerScript
{
    /**
     * Method trigger after install.
     *
     * @param string $type   type
     * @param string $parent parent
     *
     * @return JController This object to support chaining.
     *
     * @since 1.5
     */
    public function postflight($type, $parent)
    {
        ob_start();
        ?>
        <style type="text/css">
            table{
                border-collapse: separate !important;
            }
            div#wk-installer * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            div#wk-installer{
                width: 90%;
            }
            div#wk-installer,
            div#wk-installer p,
            div#wk-installer div
            {
                font-family: 'Lucida Grande', 'Gisha', 'Lucida Sans Unicode', 'Lucida Sans', Lucida, Arial, Verdana, sans-serif;
                font-size: 11px;
            }

            div#wk-installer .clearfix,
            div#wk-installer .box-hd,
            div#wk-installer .box-bd {
                clear:none;display:block;
            }
            div#wk-installer .clearfix:after,
            div#wk-installer .box-hd,
            div#wk-installer .box-bd {
                content:"";display:table;clear:both;
            }

            div#wk-installer .box
            {
                background: #F9FAFC;
                border: 1px solid #D3D3D3;
                padding: 0px;
                margin-bottom: 20px;
                color: #777;

                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                border-radius: 3px;
            }
            div#wk-installer .box-hd {
                background: #F6F7F9;
                border-bottom: 1px solid #d3d3d3;
                width: 100%;
                padding: 8px 15px 3px;

                -webkit-border-radius: 3px 3px 0 0;
                -moz-border-radius: 3px 3px 0 0;
                border-radius: 3px 3px 0 0;
            }
            div#wk-installer .box-hd .wk-title {
                float: left;
            }
            div#wk-installer .box-hd .wk-logo {
                float: right;
            }
            div#wk-installer .box-hd .wk-logo img {
                width: 100%!important;
            }
            div#wk-installer .box-hd .wk-social {
                float: right;
            }

            div#wk-installer .box-bd {
                padding: 16px !important;
            }
            div#wk-installer .box-bd b{
                color: #333;
            }
            div#wk-installer h1.wk-title {
                font-size: 22px;
                line-height: 24px;
                color: #333;
            }
            div#wk-installer .btn-install {
                font-size: 12px;
                padding: 6px 16px;
                background-color: #2196F3;
                background-image: linear-gradient(to bottom, #2196F3, #0C89ED);
                background-repeat: repeat-x;
                border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
                color: #FFFFFF;
                text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
            }
            div#wk-installer .btn-install:hover {
                background-position: 0 0;
            }
            div#wk-installer .box p
            {
                font-weight: normal;
                text-align: left;
            }
            div#wk-installer .box p img
            {
                padding: 0 25px 0 0;
            }
            div#wk-installer .fb-like,
            div#wk-installer .fb-like iframe{
                width: 85px !important;
                max-width: 85px !important;
            }
            div#wk-installer .twitter-follow-button{
                margin-left: 5px;
                margin-bottom: -5px;
            }
            div#wk-installer .actions{
                text-align: left !important;
                display: inline-block;
            }
            .wk-logo-img
            {
                display: inline-block;
                background-color: #2196F3;
                padding: 1px;
                width: 40%;
            }
            .wk-productby
            {
                float: left;
                margin-top: 7px;
            }
            .helpdesk
            {
                display: inline-block;
            }
            .set-ats
            {
                margin-top: 15px;
                float: right;
            }
        </style>
         <div id="wk-installer">
            <div class="box">
                <div class="box-hd">
                    <div class="wk-title">
                        <b>UVdesk-Joomla Helpdesk Ticket System</b> Successfully Installed.
                    </div>
                    <div class="wk-social socialize">
                        <div id="fb-root"></div>
                        <script>(function(d, s, id) {
                          var js, fjs = d.getElementsByTagName(s)[0];
                          if (d.getElementById(id)) return;
                          js = d.createElement(s); js.id = id;
                          js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5&appId=406369119482668";
                          fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>
                        <div class="fb-like" data-href="http://www.facebook.com/webkul" data-layout="button_count" data-show-faces="false" data-send="false"></div>
                        <a href="https://twitter.com/webkul" class="twitter-follow-button" data-show-count="false">Follow @webkul</a>
                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    </div>
                    <div class="wk-logo">
                        <div class="wk-productby">
                            Another product by&nbsp;
                        </div>
                        <div class="wk-logo-img">
                            <a href="https://store.webkul.com/" target="_blank"><img src="components/com_uvdeskwebkul/assets/images/webkul.png" alt=""></a>
                        </div>
                    </div>
                </div>
                <div class="box-bd">
                    <h1 class="wk-title">
                        Thank you for your recent use of UVdesk-Joomla Helpdesk Ticket System.
                    </h1>
                    <p>
                        Thank you for your recent use of UVdesk-Joomla Helpdesk Ticket System and congratulations on making the choice to use one of this extension available for Joomla! Please read out documentation and Configure the package.<b> Kindly save configurations before setting up and testing the whole extension.</b>
                    </p>
                    <div class="helpdesk">
                        <p>
                            Shoot a ticket on our helpdesk for any support.
                        </p>
                        <div class="actions">
                        <a href="mailto:support@webkul.com" target="_top">support@webkul.com</a></p>
                          
                        </div>
                    </div>
                    <div class="actions set-ats">
                        <a href="index.php?option=com_config&view=component&component=com_uvdeskwebkul" class="btn btn-success btn-install"><span class="icon-cogs icon-large"></span> &nbsp;&nbsp;Go To Component Setting &raquo;</a>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
        <?php
        $contents   = ob_get_contents();
        ob_end_clean();
        echo $contents;
    }
    /**
     * Method trigger install an extension
     *
     * @param string $parent parent
     *
     * @return JController This object to support chaining.
     *
     * @since 1.5
     */
    public function uninstall($parent)
    {
        ob_start();
        ?>
        <style type="text/css">
            table{
                border-collapse: separate !important;
            }
            div#wk-installer * {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            div#wk-installer{
                width: 95%;
            }
            div#wk-installer,
            div#wk-installer p,
            div#wk-installer div
            {
                font-family: 'Lucida Grande', 'Gisha', 'Lucida Sans Unicode', 'Lucida Sans', Lucida, Arial, Verdana, sans-serif;
                font-size: 11px;
            }

            div#wk-installer .clearfix,
            div#wk-installer .box-hd,
            div#wk-installer .box-bd {
                clear:none;display:block;
            }
            div#wk-installer .clearfix:after,
            div#wk-installer .box-hd,
            div#wk-installer .box-bd {
                content:"";display:table;clear:both;
            }

            div#wk-installer .box
            {
                background: #F9FAFC;
                border: 1px solid #D3D3D3;
                padding: 0px;
                margin-bottom: 20px;
                color: #777;

                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                border-radius: 3px;
            }
            div#wk-installer .box-hd {
                background: #F6F7F9;
                border-bottom: 1px solid #d3d3d3;
                width: 100%;
                padding: 8px 15px 3px;

                -webkit-border-radius: 3px 3px 0 0;
                -moz-border-radius: 3px 3px 0 0;
                border-radius: 3px 3px 0 0;
            }
            div#wk-installer .box-hd .wk-title {
                float: left;
            }
            div#wk-installer .box-hd .wk-logo {
                float: right;
            }
            div#wk-installer .box-hd .wk-logo img {
                width: 100%!important;
            }
            div#wk-installer .box-hd .wk-social {
                float: right;
            }

            div#wk-installer .box-bd {
                padding: 16px !important;
            }
            div#wk-installer h1.wk-title {
                font-size: 22px;
                line-height: 24px;
                color: #333;
            }

            div#wk-installer .btn-install {
                font-size: 12px;
                padding: 6px 16px;
                background-color: #2196F3;
                background-image: linear-gradient(to bottom, #2196F3, #0C89ED);
                background-repeat: repeat-x;
                border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
                color: #FFFFFF;
                text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
            }
            div#wk-installer .btn-install:hover {
                background-position: 0 0;
            }
            div#wk-installer .box p
            {
                font-weight: normal;
                text-align: left;
            }
            div#wk-installer .box p img
            {
                padding: 0 25px 0 0;
            }
            div#wk-installer .twitter-follow-button{
                margin-left: 5px;
                margin-bottom: -5px;
            }
            div#wk-installer .actions{
                margin-top: 15px;
                text-align: left !important;
            }
            .wk-logo-img
            {
                display: inline-block;
                background-color: #2196F3;
                padding: 1px;
                width: 40%;
            }
            .wk-productby
            {
                float: left;
                margin-top: 7px;
            }
        </style>
         <div id="wk-installer">
            <div class="box">
                <div class="box-hd">
                    <div class="wk-title">
                        <b>UVdesk-Joomla Helpdesk Ticket System</b> Successfully Uninstalled.
                    </div>
                    <div class="wk-social socialize">
                        <div id="fb-root"></div>
                        <script>(function(d, s, id) {
                          var js, fjs = d.getElementsByTagName(s)[0];
                          if (d.getElementById(id)) return;
                          js = d.createElement(s); js.id = id;
                          js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5&appId=406369119482668";
                          fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>
                        <div class="fb-like" data-href="http://www.facebook.com/webkul" data-layout="button_count" data-show-faces="false" data-send="false"></div>
                        <a href="https://twitter.com/webkul" class="twitter-follow-button" data-show-count="false">Follow @webkul</a>
                        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    </div>
                </div>
                <div class="box-bd">
                    <h1 class="wk-title">
                        Thank you for using UVdesk-Joomla Helpdesk Ticket System.
                    </h1>
                    <p>
                        Thank you for using UVdesk-Joomla Helpdesk Ticket System. It's sad to see you going away. If you faced any trouble in using the extension or unsatisfied by any other functionality, you can tell us by shooting a ticket on our helpdesk.
                    </p>
                    <div class="actions">
                        <a href="https://webkul.com/ticket/" target="_blank" class="btn btn-success btn-install">Helpdesk &raquo;</a>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
        <?php
        $contents   = ob_get_contents();
        ob_end_clean();
        echo $contents;
    }
    /**
     * Method to show installation result
     *
     * @param string $status status
     *
     * @return JController This object to support chaining.
     *
     * @since 1.5
     */
    public function installationResults($status)
    {

        $comparams='{"paypal_shop_mode":"s","commision_option":"f","commision":"10","commision_amount":"500","appid":"","secretkey":"","recaptcha_enable":"0","captcha_site_key":"","wk_addproduct_imgsize":"104857667888","product_action_seller_delete":"pd","create_product":"1","create_seller":"1","wk_fbloginselect":"0","wk_feedback_approv":"n","wk_price_discount":"1","wk_price_tax":"1","wk_lazy_load_limit":"5"}';
        $db = JFactory::getDBO();
        $componentid = JComponentHelper::getComponent('com_uvdeskwebkul')->id;
        $table = JTable::getInstance('extension');
        $table->load($componentid);
        $table->bind(array('params' => $comparams));
        if (!$table->store()) {
           
        }
        $rows = 0; ?>
        <h2><?php echo JText::_('UVdesk-Joomla Helpdesk Ticket System Install Status'); ?></h2>
        <table class="adminlist table table-striped">
            <thead>
                <tr>
                    <th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
                    <th width="30%"><?php echo JText::_('Status'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            <tbody>
                <tr class="row0">
                    <td class="key" colspan="2">
                    

                    </td>
                    <td><strong><?php echo JText::_('Installed'); ?></strong></td>
                </tr>
              
            </tbody>
        </table>
    <?php
    }
    /**
     * Method to show installation result
     *
     * @param string $status status
     *
     * @return JController This object to support chaining.
     *
     * @since 1.5
     */
    public function uninstallationResults($status)
    {   
        $rows = 0;
    ?>
        <h2><?php echo JText::_('UVdesk-Joomla Helpdesk Ticket System Removal Status'); ?></h2>
        <table class="adminlist table table-striped">
            <thead>
                <tr>
                    <th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
                    <th width="30%"><?php echo JText::_('Status'); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            <tbody>
                <tr class="row0">
                    <td class="key" colspan="2"><?php echo JText::_('UVdesk-Joomla Helpdesk Ticket System'); ?></td>
                    <td><strong><?php echo JText::_('UnInstalled'); ?></strong></td>
                </tr>
              
            </tbody>
        </table>
    <?php
    }
}
        