<?php
/**
  @package    admin::modules
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: newsletter.php v1.0 2013-08-08 datazen $
*/
class lC_Newsletter_newsletter {

  /* Private methods */
  var $_title,
      $_has_audience_selection = false,
      $_newsletter_title,
      $_newsletter_content,
      $_newsletter_id,
      $_audience_size = 0;

  /* Class constructor */
  public function __construct($title = '', $content = '', $newsletter_id = '') {
    global $lC_Language;

    $this->_title = $lC_Language->get('newsletter_newsletter_title');

    $this->_newsletter_title = $title;
    $this->_newsletter_content = $content;
    $this->_newsletter_id = $newsletter_id;
  }

  /* Public methods */
  public function getTitle() {
    return $this->_title;
  }

  public function hasAudienceSelection() {
    if ($this->_has_audience_selection === true) {
      return true;
    }

    return false;
  }
  
  public function hasAudienceSize() {
    global $lC_Database;

    $Qrecipients = $lC_Database->query('select count(*) as total from :table_customers c left join :table_newsletters_log nl on (c.customers_email_address = nl.email_address and nl.newsletters_id = :newsletters_id) where c.customers_newsletter = 1 and nl.email_address is null');
    $Qrecipients->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qrecipients->bindTable(':table_newsletters_log', TABLE_NEWSLETTERS_LOG);
    $Qrecipients->bindInt(':newsletters_id', $this->_newsletter_id);
    $Qrecipients->execute();

    if ($Qrecipients->valueInt('total') > 0) {
      return true;
    }
    
    $Qrecipients->freeResult();

    return false;
  }    

  public function showAudienceSelectionForm() {
    return false;
  }

  public function showConfirmation() {
    global $lC_Database, $lC_Language, $lC_Template;

    $Qrecipients = $lC_Database->query('select count(*) as total from :table_customers c left join :table_newsletters_log nl on (c.customers_email_address = nl.email_address and nl.newsletters_id = :newsletters_id) where c.customers_newsletter = 1 and nl.email_address is null');
    $Qrecipients->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qrecipients->bindTable(':table_newsletters_log', TABLE_NEWSLETTERS_LOG);
    $Qrecipients->bindInt(':newsletters_id', $this->_newsletter_id);
    $Qrecipients->execute();

    $this->_audience_size = $Qrecipients->valueInt('total');

    $confirmation_string = '<p><font color="#ff0000"><b>' . sprintf($lC_Language->get('newsletter_newsletter_total_recipients'), $this->_audience_size) . '</b></font></p>' .
                           '<p><b>' . $this->_newsletter_title . '</b></p>' .
                           '<p>' . nl2br(lc_output_string_protected($this->_newsletter_content)) . '</p>' .
                           '<form name="executeNewsletter" id="executeNewsletter" action="#" method="post"><input type="hidden" name="audienceSize" id="audienceSize" value="' . $this->_audience_size . '">';
//                             '<p align="right">';

    /*
    if ($this->_audience_size > 0) {
      $confirmation_string .= lc_draw_hidden_field('subaction', 'execute') .
                              '<input type="submit" value="' . $lC_Language->get('button_send') . '" class="operationButton" />&nbsp;' .
                              '<input type="button" value="' . $lC_Language->get('button_cancel') . '" onclick="document.location.href=\'' . lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&page=' . $_GET['page']) . '\'" class="operationButton" />';
    } else {
      $confirmation_string .= '<input type="button" value="' . $lC_Language->get('button_back') . '" onclick="document.location.href=\'' . lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&page=' . $_GET['page']) . '\'" class="operationButton" />';
    }
    */

    $confirmation_string .= '</form>';

    return $confirmation_string;
  }

  public function sendEmail() { 
    global $lC_Database, $lC_Language, $lC_Template;

    $max_execution_time = 0.8 * (int)ini_get('max_execution_time');
    $time_start = explode(' ', PAGE_PARSE_START_TIME);

    $Qrecipients = $lC_Database->query('select c.customers_firstname, c.customers_lastname, c.customers_email_address from :table_customers c left join :table_newsletters_log nl on (c.customers_email_address = nl.email_address and nl.newsletters_id = :newsletters_id) where c.customers_newsletter = 1 and nl.email_address is null');
    $Qrecipients->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qrecipients->bindTable(':table_newsletters_log', TABLE_NEWSLETTERS_LOG);
    $Qrecipients->bindInt(':newsletters_id', $this->_newsletter_id);
    $Qrecipients->execute();

    if ( $Qrecipients->numberOfRows() > 0 ) {
      $lC_Mail = new lC_Mail(null, null, null, EMAIL_FROM, $this->_newsletter_title);
      $lC_Mail->setBodyPlain($this->_newsletter_content);

      while ( $Qrecipients->next() ) {
        $lC_Mail->clearTo();
        $lC_Mail->addTo($Qrecipients->value('customers_firstname') . ' ' . $Qrecipients->value('customers_lastname'), $Qrecipients->value('customers_email_address'));
        $lC_Mail->send();

        $Qlog = $lC_Database->query('insert into :table_newsletters_log (newsletters_id, email_address, date_sent) values (:newsletters_id, :email_address, now())');
        $Qlog->bindTable(':table_newsletters_log', TABLE_NEWSLETTERS_LOG);
        $Qlog->bindInt(':newsletters_id', $this->_newsletter_id);
        $Qlog->bindValue(':email_address', $Qrecipients->value('customers_email_address'));
        $Qlog->execute();

        $time_end = explode(' ', microtime());
        $timer_total = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

        if ( $timer_total > $max_execution_time ) {
          echo '<p><font color="#38BB68"><b>' . $lC_Language->get('sending_refreshing_page') . '</b></font></p>' .
               '<form name="execute" action="' . lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&page=' . $_GET['page'] . '&nID=' . $this->_newsletter_id . '&action=send') . '" method="post">' .
               '<p>' . lc_draw_hidden_field('subaction', 'execute') . '</p>' .
               '</form>' .
               '<script language="javascript">' .
               'var counter = 3;' .
               'function counter() {' .
               '  count--;' .
               '  if (count > 0) {' .
               '    Id = window.setTimeout("counter()", 1000);' .
               '  } else {' .
               '    document.execute.submit();' .
               '  }' .
               '}' .
               '</script>';

          exit;
        }
      }

      $Qrecipients->freeResult();
    }

    $Qupdate = $lC_Database->query('update :table_newsletters set date_sent = now(), status = 1 where newsletters_id = :newsletters_id');
    $Qupdate->bindTable(':table_newsletters', TABLE_NEWSLETTERS);
    $Qupdate->bindInt(':newsletters_id', $this->_newsletter_id);
    $Qupdate->execute();
  }  
}
?>