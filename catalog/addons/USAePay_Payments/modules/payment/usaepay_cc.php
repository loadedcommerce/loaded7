<?php
/*
  $Id: authorizenet_cc.php v1.0 2013-04-20 datazen $

  Loaded Commerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     Loaded Commerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Payment_usaepay_cc extends lC_Payment {
                                   
  protected $_title,
            $_code = 'usaepay_cc',
            $_status = false,
            $_sort_order,
            $_transaction_response;

  public function lC_Payment_usaepay_cc() {
    global $lC_Database, $lC_Language, $lC_ShoppingCart;

    $this->_title = $lC_Language->get('payment_usaepay_title');
    $this->_method_title = $lC_Language->get('payment_usaepay_method_title');
    $this->_status = (ADDONS_PAYMENT_USAEPAY_PAYMENTS_STATUS == '1') ? true : false;
    $this->_sort_order = ADDONS_PAYMENT_USAEPAY_PAYMENTS_SORT_ORDER;


    switch (ADDONS_PAYMENT_USAEPAY_PAYMENTS_TRANSACTION_SERVER) {
      case 'Production':
        $this->form_action_url = 'https://www.usaepay.com/interface/epayform/';
        break;

      default:
        $this->form_action_url = 'https://sandbox.usaepay.com/interface/epayform/';
        break;
    }

    $Qcredit_cards = $lC_Database->query('select credit_card_name from :table_credit_cards where credit_card_status = :credit_card_status');
    $Qcredit_cards->bindRaw(':table_credit_cards', TABLE_CREDIT_CARDS);
    $Qcredit_cards->bindInt(':credit_card_status', '1');
    $Qcredit_cards->setCache('credit-cards');
    $Qcredit_cards->execute();

    while ($Qcredit_cards->next()) {
      $this->_card_images .= lc_image('images/cards/cc_' . strtolower(str_replace(" ", "_", $Qcredit_cards->value('credit_card_name'))) . '.png', null, null, null, 'style="vertical-align:middle; margin:0 2px;"');
    }

    $Qcredit_cards->freeResult();

    //$this->form_action_url = lc_href_link(FILENAME_CHECKOUT, 'payment_template', 'SSL', true, true, true) ;  

    if ($this->_status === true) {
      if ((int)ADDONS_PAYMENT_USAEPAY_PAYMENTS_ORDER_STATUS_ID > 0) {
        $this->order_status = ADDONS_PAYMENT_USAEPAY_PAYMENTS_ORDER_STATUS_ID;
      }

      if ((int)ADDONS_PAYMENT_USAEPAY_PAYMENTS_ZONE > 0) {
        $check_flag = false;

        $Qcheck = $lC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
        $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
        $Qcheck->bindInt(':geo_zone_id', ADDONS_PAYMENT_USAEPAY_PAYMENTS_ZONE);
        $Qcheck->bindInt(':zone_country_id', $lC_ShoppingCart->getBillingAddress('country_id'));
        $Qcheck->execute();

        while ($Qcheck->next()) {
          if ($Qcheck->valueInt('zone_id') < 1) {
            $check_flag = true;
            break;
          } elseif ($Qcheck->valueInt('zone_id') == $lC_ShoppingCart->getBillingAddress('zone_id')) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag === false) {
          $this->_status = false;
        }
      }
    }
  }

  public function customCss(){
    $cssString = '<style type=\'text/css\' media=\'all\'>
                    BODY {color: #544F4B;}
                    .Page {border: #828282 0px solid; padding: 5px; width:465px; text-align:left; margin-left:auto; margin-right:auto;}
                    #divTestMode, #tableOrderInformation, #divOrderDetailsTop, #divOrderDescr, #tableLineItems, .HrLineItem, #divOrderDetailsBottom { display: none; }
                    #btnSubmit{ color:#fff; font-size:14px; font-weight:bold; padding:8px 14px; background:#873b7a !important; border:0px; line-height:100%; cursor:pointer; vertical-align:middle;}
                    #btnSubmit:hover { background-color: #bf58ad !important; box-shadow: 0 0 0 #FFFFFF inset, 0 2px 1px rgba(204, 204, 204, 0.9); }
                    .HorizontalLine {  background-color: #E7DED5;  height: 1px;}
                    HR { border: 0px; border-top: 1px solid #E7DED5; height: 1px;}
                    HR.HrTop {border-top-color: #E7DED5;}
                    HR.HrLineItem {border-top-color: #E7DED5;}
                    .SectionHeadingBorder { margin-top: 15px; margin-bottom: 5px; border-bottom: #E7DED5 1px solid; width: 100%; }
                    .GrayBoxOuter { background-color: #FFFFFF; color: #544F4B;} 
                    .GrayBox { border: none; padding: 10px; } 
                    .LabelColCC { width: 20%; }
                    .DataColCC { width: 74%; }
                    #checkoutConfirmationDetails { width:99%; }
                    #tableCreditCardInformation TD { padding-left:10px; }
                    #tablePaymentMethodHeading TD { padding-bottom:10px; }
                    #divMerchantHeader { display:none; } 
                    #divBillingInformation { display:none; } 
                    #divShippingInformation { display:none; }
                    #hrDescriptionAfter { display:none; }
                    #hrButtonsBefore { display:none; }
                    @media only screen and (max-width: 479px) {
                      .LabelColCC { display:none; }
                      #tdSubmit { width:100%; text-align:left }
                    }
                  </style>';

    return $cssString;
  }

  public function getJavascriptBlock() {
    return false;
  }

  public function selection() {
    global $lC_Language;
    $selection = array('id' => $this->_code,
                       'module' => '<div class="payment-selection">' . $this->_method_title . '<span>' . $this->_card_images . '</span></div><div class="payment-selection-title">' . $lC_Language->get('payment_usaepay_method_blurb') . '</div>');

    return $selection;
  }

  public function pre_confirmation_check() {
    return false;
  }

  public function confirmation() {
    return false;
  }

  public function process_button() {
    global $lC_Customer, $lC_Language, $lC_Currencies, $lC_ShoppingCart;

    $order_id = lC_Order::insert();
    $invoice = $order_id ;

    $umcommand = (defined('ADDONS_PAYMENT_USAEPAY_PAYMENTS_TRXTYPE') && ADDONS_PAYMENT_USAEPAY_PAYMENTS_TRXTYPE == 'Authorization-Only') ? 'authonly' : 'sale';    
    $pin = (defined('ADDONS_PAYMENT_USAEPAY_PAYMENTS_TRANSACTION_SOURCE_PIN') && ADDONS_PAYMENT_USAEPAY_PAYMENTS_TRANSACTION_SOURCE_PIN != '') ? ADDONS_PAYMENT_USAEPAY_PAYMENTS_TRANSACTION_SOURCE_PIN : NULL;
    $amount = $lC_ShoppingCart->getTotal();
    $hashseed = mktime();   // mktime returns the current time in seconds since epoch.
    $hashdata = $umcommand . ":" . $pin . ":" . $amount . ":" . $invoice . ":" . $hashseed ; 
    $hash = md5( $hashdata );   // php includes a built-in md5 function that will create the hash
    $UMhash = "m/$hashseed/$hash/y";

    $data =  array(
            'UMkey' => ADDONS_PAYMENT_USAEPAY_PAYMENTS_TRANSACTION_SOURCE_KEY,            
            'UMcommand' => $umcommand,
            'UMamount' => $amount,
            'UMinvoice' => $invoice,
            'UMorderid' => $order_id,
            'UMcurrency' => $this->_getCurrencyNumericCode($lC_Currencies->getCode()),
            'UMname' => $lC_Customer->getName(),
            'UMstreet' => $lC_ShoppingCart->getBillingAddress('street_address'),
            'UMzip' => $lC_ShoppingCart->getBillingAddress('postcode'),
            'UMip' => lc_get_ip_address(),            
            'UMcustemail' => $lC_Customer->getEmailAddress(),
            'UMbillfname' => $lC_ShoppingCart->getBillingAddress('firstname'),
            'UMbilllname' => $lC_ShoppingCart->getBillingAddress('lastname'),
            'UMbillcompany' => $lC_ShoppingCart->getBillingAddress('company'),
            'UMbillstreet' => $lC_ShoppingCart->getBillingAddress('street_address'),
            'UMbillcity' => $lC_ShoppingCart->getBillingAddress('city'),
            'UMbillstate' => $lC_ShoppingCart->getBillingAddress('state'),
            'UMbillzip' => $lC_ShoppingCart->getBillingAddress('postcode'),
            'UMbillcountry' => $lC_ShoppingCart->getBillingAddress('country_iso_code_2'),
            'UMbillphone' => $lC_ShoppingCart->getBillingAddress('telephone_number'),
            'UMshipfname' => $lC_ShoppingCart->getShippingAddress('firstname'),
            'UMshiplname' => $lC_ShoppingCart->getShippingAddress('lastname'),
            'UMshipcompany' => $lC_ShoppingCart->getShippingAddress('company'),
            'UMshipstreet' => $lC_ShoppingCart->getShippingAddress('street_address'),
            'UMshipcity' => $lC_ShoppingCart->getShippingAddress('city'),
            'UMshipstate' => $lC_ShoppingCart->getShippingAddress('state'),
            'UMshipzip' => $lC_ShoppingCart->getShippingAddress('postcode'),
            'UMshipcountry' => $lC_ShoppingCart->getShippingAddress('country_iso_code_2'),
            'UMshipphone' => $lC_ShoppingCart->getShippingAddress('telephone_number') ,
            'UMsoftware' => 'Loaded Commerce v' . utility::getVersion(),
            'UMredirApproved' => lc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', true, true, true)
          );  

    if (defined('ADDONS_PAYMENT_USAEPAY_PAYMENTS_TRANSACTION_SERVER') && ADDONS_PAYMENT_USAEPAY_PAYMENTS_TRANSACTION_SERVER == 'Test') {
      $data['UMtestmode'] = '1';
    }
    
    foreach ($data as $data_key => $data_value) {
      $process_button_string .= lc_draw_hidden_field($data_key, $data_value) . "\n";
    }

    if(ADDONS_PAYMENT_USAEPAY_PAYMENTS_TRANSACTION_SOURCE_PIN != '') {
      $process_button_string .= lc_draw_hidden_field('UMhash', $UMhash) . "\n";
    }

    $i = 0;
    foreach ($lC_ShoppingCart->getProducts() as $product) {
      $process_button_string .= lc_draw_hidden_field('UMline' . $i . 'name', $product['name']) . "\n";
      $process_button_string .= lc_draw_hidden_field('UMline' . $i . 'cost', $product['price']) . "\n";
      $process_button_string .= lc_draw_hidden_field('UMline' . $i . 'qty', $product['quantity']) . "\n";
      $i++;
    }    

    return $process_button_string;
  }

  public function process() {
    global $lC_Database, $lC_MessageStack;
    $error = false;
    $status = (isset($_GET['UMstatus']) && $_GET['UMstatus'] != '') ? preg_replace('/[^a-zA-Z]/', '', $_GET['UMstatus']) : NULL;
    $code = (isset($_GET['UMauthCode']) && $_POST['UMauthCode'] != '') ? preg_replace('/[^a-zA-Z]/', '', $_GET['UMauthCode']) : NULL;
    $msg = (isset($_GET['UMerror']) && $_GET['UMerror'] != NULL) ? preg_replace('/[^a-zA-Z0-9]\:\|\[\]/', '', $_GET['UMerror']) : NULL;
    $order_id = (isset($_GET['UMinvoice']) && $_GET['UMinvoice'] != NULL) ? preg_replace('/[^0-9]\:\|\[\]/', '', $_GET['UMinvoice']) : 0;

    if ($status == 'Approved') { // success    
      lC_Order::process($order_id, $this->order_status);
    } else {
      $error = true;
      $error_code = (isset($_GET['UMerrorcode']) && $_GET['UMerrorcode'] != '') ? preg_replace('/[^0-9]/', '', $_GET['UMauthCode']) : NULL;      
      $lC_MessageStack->add('checkout_payment', $error_code . ' - ' . $msg);
      lC_Order::remove($order_id);
    } 
    
    // insert into transaction history
    $this->_transaction_response = $code;

    $response_array = array('root' => $_POST);
    $response_array['root']['transaction_response'] = trim($this->_transaction_response);
    $lC_XML = new lC_XML($response_array);
    
    $Qtransaction = $lC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
    $Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
    $Qtransaction->bindInt(':orders_id', $order_id);
    $Qtransaction->bindInt(':transaction_code', 1);
    $Qtransaction->bindValue(':transaction_return_value', $lC_XML->toXML());
    $Qtransaction->bindInt(':transaction_return_status', (strtoupper(trim($this->_transaction_response)) == '1') ? 1 : 0);
    $Qtransaction->execute();   
    
    if ($error) lc_redirect(lc_href_link(FILENAME_CHECKOUT, 'payment', 'SSL'));
  }

  private function _getCurrencyNumericCode($countries_iso_code_3 = 'USD') {
    $currency_numeric_code =  array(
      'AFA' => '971',
      'AWG' => '533',
      'AUD' => '036',
      'ARS' => '032',
      'AZN' => '944',
      'BSD' => '044',
      'BDT' => '050',
      'BBD' => '052',
      'BYR' => '974',
      'BOB' => '068',
      'BRL' => '986',
      'GBP' => '826',
      'BGN' => '975',
      'KHR' => '116',
      'CAD' => '124',
      'KYD' => '136',
      'CLP' => '152',
      'COP' => '170',
      'CRC' => '188',
      'HRK' => '191',
      'CPY' => '196',
      'CZK' => '203',
      'DKK' => '208',
      'DOP' => '214',
      'XCD' => '951',
      'EGP' => '818',
      'ERN' => '232',
      'EEK' => '233',
      'EUR' => '978',
      'GEL' => '981',
      'GHC' => '288',
      'GIP' => '292',
      'GTQ' => '320',
      'HNL' => '340',
      'HKD' => '344',
      'HUF' => '348',
      'ISK' => '352',
      'INR' => '356',
      'IDR' => '360',
      'ILS' => '376',
      'JMD' => '388',
      'JPY' => '392',
      'KZT' => '368',
      'KES' => '404',
      'KWD' => '414',
      'LVL' => '428',
      'LBP' => '422',
      'LTL' => '440',
      'MOP' => '446',
      'MKD' => '807',
      'MGA' => '969',
      'MYR' => '458',
      'MTL' => '470',
      'BAM' => '977',
      'MUR' => '480',
      'MXN' => '484',
      'MZM' => '508',
      'NPR' => '524',
      'ANG' => '532',
      'TWD' => '901',
      'TWD' => '901',
      'NZD' => '554',
      'NIO' => '558',
      'NGN' => '566',
      'KPW' => '408',
      'NOK' => '578',
      'OMR' => '512',
      'PKR' => '586',
      'PYG' => '600',
      'PEN' => '604',
      'PHP' => '608',
      'QAR' => '634',
      'RON' => '946',
      'RUB' => '643',
      'SAR' => '682',
      'CSD' => '891',
      'SCR' => '690',
      'SGD' => '702',
      'SKK' => '703',
      'SIT' => '705',
      'ZAR' => '710',
      'KRW' => '410',
      'LKR' => '144',
      'SRD' => '968',
      'SEK' => '752',
      'CHF' => '756',
      'TZS' => '834',
      'THB' => '764',
      'TTD' => '780',
      'TRY' => '949',
      'AED' => '784',
      'USD' => '840',
      'UGX' => '800',
      'UAH' => '980',
      'UYU' => '858',
      'UZS' => '860',
      'VEB' => '862',
      'VND' => '704',
      'AMK' => '894',
      'ZWD' => '716',
    );  

    if(array_key_exists($countries_iso_code_3,$currency_numeric_code)) {
      return $currency_numeric_code[$countries_iso_code_3];
    } 
    return $currency_numeric_code['USD'];   
  }

}
?>