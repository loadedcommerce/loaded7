<?php
/*
  $Id: specials.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Specials {

    /* Private variables */
    var $_specials = array();

    /* Class constructor */
    function lC_Specials() {
    }

    /* Public methods */
    function activateAll() {
      global $lC_Database;

      $Qspecials = $lC_Database->query('select specials_id from :table_specials where status = 0 and now() >= start_date and start_date > 0 and now() < expires_date');
      $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
      $Qspecials->execute();

      while ($Qspecials->next()) {
        $this->_setStatus($Qspecials->valueInt('specials_id'), true);
      }

      $Qspecials->freeResult();
    }

    function expireAll() {
      global $lC_Database;

      $Qspecials = $lC_Database->query('select specials_id from :table_specials where status = 1 and now() >= expires_date and expires_date > 0');
      $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
      $Qspecials->execute();

      while ($Qspecials->next()) {
        $this->_setStatus($Qspecials->valueInt('specials_id'), false);
      }

      $Qspecials->freeResult();
    }

    function isActive($id) {
      global $lC_Database;

      if (!isset($this->_specials[$id])) {
        $this->_specials[$id] = $this->getPrice($id);
      }

      return is_numeric($this->_specials[$id]);
    }

    function getPrice($id) {
      global $lC_Database;

      if (!isset($this->_specials[$id])) {
        $Qspecial = $lC_Database->query('select specials_new_products_price from :table_specials where products_id = :products_id and status = 1');
        $Qspecial->bindTable(':table_specials', TABLE_SPECIALS);
        $Qspecial->bindInt(':products_id', $id);
        $Qspecial->execute();

        if ($Qspecial->numberOfRows() > 0) {
          $this->_specials[$id] = $Qspecial->valueDecimal('specials_new_products_price');
        } else {
          $this->_specials[$id] = null;
        }

        $Qspecial->freeResult();
      }

      return $this->_specials[$id];
    }

    function &getListing() {
      global $lC_Database, $lC_Language, $lC_Image;

      $Qspecials = $lC_Database->query('select p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, pd.products_keyword, pd.products_description, s.specials_new_products_price, i.image from :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd, :table_specials s where p.products_status = 1 and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = :language_id and s.status = 1 order by s.specials_date_added desc');
      $Qspecials->bindTable(':table_products', TABLE_PRODUCTS);
      $Qspecials->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qspecials->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qspecials->bindTable(':table_specials', TABLE_SPECIALS);
      $Qspecials->bindInt(':default_flag', 1);
      $Qspecials->bindInt(':language_id', $lC_Language->getID());
      $Qspecials->setBatchLimit((isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1), MAX_DISPLAY_SPECIAL_PRODUCTS);
      $Qspecials->execute();

      return $Qspecials;
    }

    /* Private methods */
    function _setStatus($id, $status) {
      global $lC_Database;

      $Qstatus = $lC_Database->query('update :table_specials set status = :status, date_status_change = now() where specials_id = :specials_id');
      $Qstatus->bindTable(':table_specials', TABLE_SPECIALS);
      $Qstatus->bindInt(':status', ($status === true) ? '1' : '0');
      $Qstatus->bindInt(':specials_id', $id);
      $Qstatus->execute();

      $Qstatus->freeResult();
    }
  }
?>
