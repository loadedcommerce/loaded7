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
if ( !class_exists('lC_Summary') ) {
  include('includes/classes/summary.php');
}

class lC_Summary_reviews extends lC_Summary {

  var $enabled = FALSE,
      $sort_order = 60;
  
  /* Class constructor */
  function __construct() {
    global $lC_Language;

    $lC_Language->loadIniFile('modules/summary/reviews.php');

    $this->_title = $lC_Language->get('summary_reviews_title');
    $this->_title_link = lc_href_link_admin(FILENAME_DEFAULT, 'reviews');

    if ( lC_Access::hasAccess('reviews') ) {
      $this->_setData();
    }
  }

  /* Private methods */
  function _setData() {
    global $lC_Database, $lC_Language;

    if (!$this->enabled) {
      $this->_data = '';
    } else {
      $this->_data = '<div class="four-columns six-columns-tablet twelve-columns-mobile">' .
                     '  <h2 class="relative thin">' . $this->_title . '</h2>' .
                     '  <ul class="list spaced">';

      $Qreviews = $lC_Database->query('select r.reviews_id, r.products_id, greatest(r.date_added, greatest(r.date_added, r.last_modified)) as date_last_modified, r.reviews_rating, pd.products_name, l.name as languages_name, l.code as languages_code from :table_reviews r left join :table_products_description pd on (r.products_id = pd.products_id and r.languages_id = pd.language_id), :table_languages l where r.languages_id = l.languages_id order by date_last_modified desc limit 6');
      $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qreviews->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
      $Qreviews->bindTable(':table_languages', TABLE_LANGUAGES);
      $Qreviews->execute();

      while ( $Qreviews->next() ) {
        $this->_data .= '    <li>' .
                        '      <span class="list-link icon-speech icon-purple" title="' . $lC_Language->get('orders') . '">' .  
                        '        <strong>' . $Qreviews->value('products_name')  . '</strong> ' . lc_image('../images/stars_' . $Qreviews->valueInt('reviews_rating') . '.png', $Qreviews->valueInt('reviews_rating') . '/5') .
                        '      </span>' .
                        '    </li>';
      }

      $this->_data .= '  </ul>' . 
                      '</div>';

      $Qreviews->freeResult();
    }
  }
}
?>