<?php
/*
  $Id: reviews.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/

  class lC_Reviews {
     var $is_enabled = false,
         $is_moderated = false;

    // class constructor
    function lC_Reviews() {

    	$this->enableReviews();
    	$this->enableModeration();
    }

    function enableReviews() {
    	global $lC_Database, $lC_Customer;

      switch (SERVICE_REVIEW_ENABLE_REVIEWS) {
        case 0:
          $this->is_enabled = true;
          break;
        case 1:
          if ($lC_Customer->isLoggedOn()) {
            $this->is_enabled = true;
          } else {
            $this->is_enabled = false;
          }
          break;
        case 2:
          if ($this->hasPurchased() == true) {
            $this->is_enabled = true;
          } else {
            $this->is_enabled = false;
          }
          break;
        default:
          $this->is_enabled = false;
          break;
        }
      }

    function hasPurchased() {
      global $lC_Database, $lC_Customer;

      $Qhaspurchased = $lC_Database->query('select count(*) as total from :table_orders o, :table_orders_products op, :table_products p where o.customers_id = :customers_id and o.orders_id = op.orders_id and op.products_id = p.products_id and op.products_id = :products_id');
      $Qhaspurchased->bindRaw(':table_orders', TABLE_ORDERS);
      $Qhaspurchased->bindRaw(':table_orders_products', TABLE_ORDERS_PRODUCTS);
      $Qhaspurchased->bindRaw(':table_products', TABLE_PRODUCTS);
      $Qhaspurchased->bindInt(':customers_id', $lC_Customer->getID());
      $Qhaspurchased->bindInt(':products_id', $_GET['products_id']);
      $Qhaspurchased->execute();

      if ($Qhaspurchased->valueInt('total') >= '1') {
      	return true;
      } else {
      	return false;
      }
    }

    function enableModeration() {
    	global $lC_Database, $lC_Customer;

      switch (SERVICE_REVIEW_ENABLE_MODERATION) {
      case -1: // no moderation
        $this->is_moderated = false;
        break;
      case 0: // only moderate reviews from guests
        if ($lC_Customer->isLoggedOn()) {
          $this->is_moderated = false;
        } else {
          $this->is_moderated = true;
        }
        break;
      case 1:  // moderate all reviews
        $this->is_moderated = true;
        break;
      default:
        $this->is_moderated = true;
        break;
      }
    }

    function getTotal($id) {
      global $lC_Database, $lC_Language;

      $Qcheck = $lC_Database->query('select count(*) as total from :table_reviews where products_id = :products_id and languages_id = :languages_id and reviews_status = 1 limit 1');
      $Qcheck->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qcheck->bindInt(':products_id', $id);
      $Qcheck->bindInt(':languages_id', $lC_Language->getID());
      $Qcheck->execute();

      return $Qcheck->valueInt('total');
    }

    function exists($id = null, $groupped = false) {
      global $lC_Database, $lC_Language;

      $Qcheck = $lC_Database->query('select reviews_id from :table_reviews where');

      if (is_numeric($id)) {
        if ($groupped === false) {
          $Qcheck->appendQuery('reviews_id = :reviews_id and');
          $Qcheck->bindInt(':reviews_id', $id);
        } else {
          $Qcheck->appendQuery('products_id = :products_id and');
          $Qcheck->bindInt(':products_id', $id);
        }
      }

      $Qcheck->appendQuery('languages_id = :languages_id and reviews_status = 1 limit 1');
      $Qcheck->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qcheck->bindInt(':languages_id', $lC_Language->getID());
      $Qcheck->execute();

      if ($Qcheck->numberOfRows() === 1) {
        return true;
      }

      return false;
    }

    function getProductID($id) {
      global $lC_Database;

      $Qreview = $lC_Database->query('select products_id from :table_reviews where reviews_id = :reviews_id');
      $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qreview->bindInt(':reviews_id', $id);
      $Qreview->execute();

      return $Qreview->valueInt('products_id');
    }

    function &getListing($id = null) {
      global $lC_Database, $lC_Language, $lC_Image;

      if (is_numeric($id)) {
        $Qreviews = $lC_Database->query('select reviews_id, reviews_text, reviews_rating, date_added, customers_name from :table_reviews where products_id = :products_id and languages_id = :languages_id and reviews_status = 1 order by reviews_id desc');
        $Qreviews->bindInt(':products_id', $id);
        $Qreviews->bindInt(':languages_id', $lC_Language->getID());
      } else {
        $Qreviews = $lC_Database->query('select r.reviews_id, left(r.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, r.customers_name, p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, pd.products_keyword, i.image from :table_reviews r, :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd where r.reviews_status = 1 and r.languages_id = :languages_id and r.products_id = p.products_id and p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = :language_id order by r.reviews_id desc');
        $Qreviews->bindTable(':table_products', TABLE_PRODUCTS);
        $Qreviews->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qreviews->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qreviews->bindInt(':default_flag', 1);
        $Qreviews->bindInt(':languages_id', $lC_Language->getID());
        $Qreviews->bindInt(':language_id', $lC_Language->getID());
      }
      $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qreviews->setBatchLimit((isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1), MAX_DISPLAY_NEW_REVIEWS);
      $Qreviews->execute();

      return $Qreviews;
    }

    function &getEntry($id) {
      global $lC_Database, $lC_Language;

      $Qreviews = $lC_Database->query('select reviews_id, reviews_text, reviews_rating, date_added, customers_name from :table_reviews where reviews_id = :reviews_id and languages_id = :languages_id and reviews_status = 1');
      $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qreviews->bindInt(':reviews_id', $id);
      $Qreviews->bindInt(':languages_id', $lC_Language->getID());
      $Qreviews->execute();

      return $Qreviews;
    }
    
    function getListingOutput($id = null) {
      global $lC_Database, $lC_Language, $lC_Image;

      if (is_numeric($id)) {
        $Qreviews = $lC_Database->query('select reviews_id, reviews_text, reviews_rating, date_added, customers_name from :table_reviews where products_id = :products_id and languages_id = :languages_id and reviews_status = 1 order by reviews_id desc');
        $Qreviews->bindInt(':products_id', $id);
        $Qreviews->bindInt(':languages_id', $lC_Language->getID());
      } else {
        $Qreviews = $lC_Database->query('select r.reviews_id, left(r.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, r.customers_name, p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, pd.products_keyword, i.image from :table_reviews r, :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd where r.reviews_status = 1 and r.languages_id = :languages_id and r.products_id = p.products_id and p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = :language_id order by r.reviews_id desc');
        $Qreviews->bindTable(':table_products', TABLE_PRODUCTS);
        $Qreviews->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qreviews->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qreviews->bindInt(':default_flag', 1);
        $Qreviews->bindInt(':languages_id', $lC_Language->getID());
        $Qreviews->bindInt(':language_id', $lC_Language->getID());
      }
      $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qreviews->setBatchLimit((isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1), MAX_DISPLAY_NEW_REVIEWS);
      $Qreviews->execute();      
      
      
      $counter = 0;
      $output = '';
      if ($Qreviews->numberOfRows() > 0) {
        while ($Qreviews->next()) {
          $counter++;
          if ($counter > 1) {
            $output .= '<br />' . "\n";                                                                                     
          }
          $text = (strlen($Qreviews->valueProtected('reviews_text')) > 60) ? substr($Qreviews->valueProtected('reviews_text'), 0, 360) . '...' : $Qreviews->valueProtected('reviews_text');
          $output .= '<div class="content-reviews-stars">' . lc_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $Qreviews->valueInt('reviews_rating') . '.png', sprintf($lC_Language->get('rating_of_5_stars'), $Qreviews->valueInt('reviews_rating'))) . '&nbsp;' . sprintf($lC_Language->get('reviewed_by'), $Qreviews->valueProtected('customers_name')) . '; ' . lC_DateTime::getLong($Qreviews->value('date_added')) . '</div>' . "\n";
          $output .= '<div class="content-reviews-text"><em>' . nl2br($text) . '</em></div>' . "\n";
        }
      } else {
        $output ='<div>' . $lC_Language->get('no_reviews_available') . '</div>' . "\n"; 
      }
      
      return $output;
    }

    function saveEntry($data) {
      global $lC_Database, $lC_Language;

      $Qreview = $lC_Database->query('insert into :table_reviews (products_id, customers_id, customers_name, reviews_rating, languages_id, reviews_text, reviews_status, date_added, reviews_read) values (:products_id, :customers_id, :customers_name, :reviews_rating, :languages_id, :reviews_text, :reviews_status, now(), :reviews_read)');
      $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qreview->bindInt(':products_id', $data['products_id']);
      $Qreview->bindInt(':customers_id', $data['customer_id']);
      $Qreview->bindValue(':customers_name', $data['customer_name']);
      $Qreview->bindValue(':reviews_rating', $data['rating']);
      $Qreview->bindInt(':languages_id', $lC_Language->getID());
      $Qreview->bindValue(':reviews_text', $data['review']);
      $Qreview->bindInt(':reviews_status', $data['status']);
      $Qreview->bindInt(':reviews_read', 0);
      $Qreview->execute();
    }
  }
?>
