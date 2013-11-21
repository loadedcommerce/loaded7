<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: reviews.php v1.0 2013-08-08 datazen $
*/
class lC_Reviews_Admin {
 /*
  * Returns the backups datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;

    $lC_Language->loadIniFile('reviews.php');

    $media = $_GET['media'];
    
    $Qreviews = $lC_Database->query('select r.reviews_id, r.products_id, r.date_added, r.last_modified, r.reviews_rating, r.reviews_status, pd.products_name, l.code as languages_code from :table_reviews r left join :table_products_description pd on (r.products_id = pd.products_id and r.languages_id = pd.language_id), :table_languages l where r.languages_id = l.languages_id order by r.date_added desc');
    $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
    $Qreviews->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qreviews->bindTable(':table_languages', TABLE_LANGUAGES);
    $Qreviews->execute();

    $result = array('aaData' => array());
    while ($Qreviews->next()) {
      switch ($Qreviews->valueInt('reviews_status')) {
        case 1 : // approved
          $status_image = '<span class="icon-tick icon-green icon-size2 with-tooltip" title="' . $lC_Language->get('review_status_approved') . '"></span>';
          break;
        case 2 : // rejected
          $status_image = '<span class="icon-cross icon-red icon-size2 with-tooltip" title="' . $lC_Language->get('review_status_rejected') . '"></span>';
          break;
        default : //pending
          $status_image = '<span class="icon-warning icon-orange icon-size2 with-tooltip" title="' . $lC_Language->get('review_status_pending') . '"></span>';
          break;
      }
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qreviews->valueInt('reviews_id') . '" id="' . $Qreviews->valueInt('reviews_id') . '"></td>';
      $product = '<td><a onclick="showPreview(\'' . $Qreviews->valueInt('reviews_id') . '\')" href="javascript:void(0);"><span class="icon-search icon-blue"></span>&nbsp;' . $Qreviews->value('products_name') . '</a></td>';
      $lang = '<td>' . $lC_Language->showImage($Qreviews->value('languages_code')) . '</td>';
      $rating = '<td>' . lc_image('../images/stars_' . $Qreviews->valueInt('reviews_rating') . '.png', sprintf($lC_Language->get('rating_from_5_stars'), $Qreviews->valueInt('reviews_rating'))) . '</td>';
      $status = '<td>' . $status_image . '</td>';
      $date = '<td>' . lC_DateTime::getShort($Qreviews->value('date_added')) . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : 'javascript://" onclick="editEntry(\'' . $Qreviews->valueInt('reviews_id') . '\')') . '" class="button icon-pencil ' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? 'disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $Qreviews->valueInt('reviews_id') . '\', \'' . urlencode($Qreviews->valueProtected('products_name')) . '\');') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$product", "$lang", "$rating", "$status", "$date", "$action");
    }

    return $result;
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $id The newsletter id
  * @access public
  * @return array
  */
  public static function formData($id = null) {
    global $lC_Database, $lC_Language;

    $lC_Language->loadIniFile('reviews.php');

    $result = array();

    if ($id != null) {
      $result['rData'] =  lC_Reviews_Admin::getData($id);
      $result['dateShort'] = lC_DateTime::getShort($result['rData']['date_added']);

      switch ($result['rData']['reviews_status']) {
        case 1 : // approved
          $result['rData']['reviews_status_text'] = $lC_Language->get('review_status_approved');
          break;
        case 2 : // rejected
          $result['rData']['reviews_status_text'] = $lC_Language->get('review_status_rejected');
          break;
        default : //new
          $result['rData']['reviews_status_text'] = $lC_Language->get('review_status_pending');
          break;
      }

      $result['ratingRadio'] = '';
      for ($i=1; $i<=5; $i++) {
        $checked = ($result['rData']['reviews_rating'] == $i) ? 'checked' : NULL;
        $result['ratingRadio'] .= '<label for="reviews_rating-' . $i . '" class="button blue-active"><input type="radio" name="reviews_rating" id="reviews_rating-' . $i . '" value="' . $i . '" ' . $checked . '>' . $i . '</label>';
      }
      $result['ratingStars'] = lc_image('../images/stars_' . $result['rData']['reviews_rating'] . '.png', sprintf($lC_Language->get('rating_from_5_stars'), $result['rData']['reviews_rating'])) . '&nbsp;[' . sprintf($lC_Language->get('rating_from_5_stars'), $result['rData']['reviews_rating']) . ']';

    }

    return $result;
  }
 /*
  * Return the reviews information
  *
  * @param integer $id The reviews id
  * @access public
  * @return array
  */
  public static function getData($id) {
    global $lC_Database;

    $Qreview = $lC_Database->query('select r.*, pd.products_name from :table_reviews r left join :table_products_description pd on (r.products_id = pd.products_id and r.languages_id = pd.language_id) where r.reviews_id = :reviews_id');
    $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
    $Qreview->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
    $Qreview->bindInt(':reviews_id', $id);
    $Qreview->execute();

    $data = $Qreview->toArray();

    $Qaverage = $lC_Database->query('select (avg(reviews_rating) / 5 * 100) as average_rating from :table_reviews where products_id = :products_id');
    $Qaverage->bindTable(':table_reviews', TABLE_REVIEWS);
    $Qaverage->bindInt(':products_id', $Qreview->valueInt('products_id'));
    $Qaverage->execute();

    $data['average_rating'] = $Qaverage->value('average_rating');

    $Qaverage->freeResult();
    $Qreview->freeResult();

    return $data;
  }
 /*
  * Save the reviews information
  *
  * @param integer $id The reviews id
  * @param array $data The reviews information
  * @access public
  * @return boolean
  */
  public static function save($id, $data) {
    global $lC_Database;

    $Qreview = $lC_Database->query('update :table_reviews set reviews_text = :reviews_text, reviews_rating = :reviews_rating, last_modified = now() where reviews_id = :reviews_id');
    $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
    $Qreview->bindValue(':reviews_text', $data['reviews_text']);
    $Qreview->bindInt(':reviews_rating', $data['reviews_rating']);
    $Qreview->bindInt(':reviews_id', $id);
    $Qreview->setLogging($_SESSION['module'], $id);
    $Qreview->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Delete the reviews record
  *
  * @param integer $id The reviews id to delete
  * @access public
  * @return boolean
  */
  public static function delete($id) {
    global $lC_Database;

    $Qreview = $lC_Database->query('delete from :table_reviews where reviews_id = :reviews_id');
    $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
    $Qreview->bindInt(':reviews_id', $id);
    $Qreview->setLogging($_SESSION['module'], $id);
    $Qreview->execute();

    if ( !$lC_Database->isError() ) {
      return true;
    }

    return false;
  }
 /*
  * Batch delete reviews records
  *
  * @param array $batch The reviews id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Reviews_Admin::delete($id);
    }
    return true;
  }
 /*
  * Approve the review
  *
  * @param integer $id The reviews id
  * @access public
  * @return boolean
  */
  public static function approve($id) {
    global $lC_Database;

    if (isset($id) && is_numeric($id)) {
      $Qreview = $lC_Database->query('update :table_reviews set reviews_status = 1 where reviews_id = :reviews_id');
      $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qreview->bindInt(':reviews_id', $id);
      $Qreview->setLogging($_SESSION['module'], $id);
      $Qreview->execute();

      if (!$lC_Database->isError()) {
        return true;
      }

      return false;
    }
  }
 /*
  * Reject the review
  *
  * @param integer $id The reviews id
  * @access public
  * @return boolean
  */
  public static function reject($id) {
    global $lC_Database;

    if (isset($id) && is_numeric($id)) {
      $Qreview = $lC_Database->query('update :table_reviews set reviews_status = 2 where reviews_id = :reviews_id');
      $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
      $Qreview->bindInt(':reviews_id', $id);
      $Qreview->setLogging($_SESSION['module'], $id);
      $Qreview->execute();

       if (!$lC_Database->isError()) {
        return true;
      }

      return false;
    }
  }
}
?>