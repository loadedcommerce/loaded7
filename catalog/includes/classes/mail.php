<?php
/**
  @package    catalog::classes
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: mail.php v1.0 2013-08-08 datazen $
*/
class lC_Mail {
  var $_to = array(),
      $_from = array(),
      $_cc = array(),
      $_bcc = array(),
      $_subject,
      $_body_plain,
      $_body_html,
      $_attachments = array(),
      $_images = array(),
      $_boundary,
      $_headers = array('X-Mailer' => 'Loaded Commerce'),
      $_body,
      $_content_transfer_encoding = '7bit',
      $_charset = 'iso-8859-1';

  public function lC_Mail($to = null, $to_email_address = null, $from = null, $from_email_address = null, $subject = null) {
    if ( !empty($to_email_address) ) {
      $this->_to[] = array('name' => $to,
                           'email_address' => $to_email_address);
    }

    if ( !empty($from_email_address) ) {
      $this->_from = array('name' => $from,
                           'email_address' => $from_email_address);
    }

    if ( !empty($subject) ) {
      $this->_subject = $subject;
    }
  }

  public function addTo($name = null, $email_address) {
    $this->_to[] = array('name' => $name,
                         'email_address' => $email_address);
  }

  public function setFrom($name = null, $email_address) {
    $this->_from = array('name' => $name,
                         'email_address' => $email_address);
  }

  public function addCC($name = null, $email_address) {
    $this->_cc[] = array('name' => $name,
                         'email_address' => $email_address);
  }

  public function addBCC($name = null, $email_address) {
    $this->_bcc[] = array('name' => $name,
                          'email_address' => $email_address);
  }

  public function clearTo() {
    $this->_to = array();
    $this->_cc = array();
    $this->_bcc = array();
    $this->_headers = array('X-Mailer' => 'LoadedCommerce');
  }

  public function setSubject($subject) {
    $this->_subject = $subject;
  }

  public function setBodyPlain($body) {
    $this->_body_plain = $body;
    $this->_body = null;
  }

  public function setBodyHTML($body) {
    $this->_body_html = $body;
    $this->_body = null;
  }

  public function setContentTransferEncoding($encoding) {
    $this->_content_transfer_encoding = $encoding;
  }

  public function setCharset($charset) {
    $this->_charset = $charset;
  }

  public function addHeader($key, $value) {
    if ( ( strpos($key, "\n") !== false ) || ( strpos($key, "\r") !== false ) ) {
      return false;
    }

    if ( ( strpos($value, "\n") !== false ) || ( strpos($value, "\r") !== false ) ) {
      return false;
    }

    $this->_headers[$key] = $value;
  }

  public function addAttachment($file, $is_uploaded = false) {
    if ( $is_uploaded === true ) {
    } elseif ( file_exists($file) && is_readable($file) ) {
      $data = file_get_contents($file);
      $filename = basename($file);
      $mimetype = $this->_get_mime_type($filename);
    } else {
      return false;
    }

    $this->_attachments[] = array('filename' => $filename,
                                  'mimetype' => $mimetype,
                                  'data' => chunk_split(base64_encode($data)));
  }

  public function addImage($file, $is_uploaded = false) {
    if ( $is_uploaded === true ) {
    } elseif ( file_exists($file) && is_readable($file) ) {
      $data = file_get_contents($file);
      $filename = basename($file);
      $mimetype = $this->_get_mime_type($filename);
    } else {
      return false;
    }

    $this->_images[] = array('id' => md5(uniqid(time())),
                             'filename' => $filename,
                             'mimetype' => $mimetype,
                             'data' => chunk_split(base64_encode($data)));
  }

  public function send() {
    if ( empty($this->_body) ) {
      if ( !empty($this->_body_plain) && !empty($this->_body_html) ) {
        $this->_boundary = '=_____MULTIPART_MIXED_BOUNDARY____';
        $this->_related_boundary = '=_____MULTIPART_RELATED_BOUNDARY____';
        $this->_alternative_boundary = '=_____MULTIPART_ALTERNATIVE_BOUNDARY____';

        $this->_headers['MIME-Version'] = '1.0';
        $this->_headers['Content-Type'] = 'multipart/mixed; boundary="' . $this->_boundary . '"';
        $this->_headers['Content-Transfer-Encoding'] = $this->_content_transfer_encoding;

        if ( !empty($this->_images) ) {
          foreach ( $this->_images as $image ) {
            $this->_body_html = str_replace('src="' . $image['filename'] . '"', 'src="cid:' . $image['id'] . '"', $this->_body_html);
          }

          unset($image);
        }

        $this->_body = 'This is a multi-part message in MIME format.' . "\n\n" .
                       '--' . $this->_boundary . "\n";

        $this->_body .= 'Content-Type: multipart/alternative; boundary="' . $this->_alternative_boundary . '";' . "\n\n" .
                        '--' . $this->_alternative_boundary . "\n" .
                        'Content-Type: text/plain; charset="' . $this->_charset . '"' . "\n" .
                        'Content-Transfer-Encoding: ' . $this->_content_transfer_encoding . "\n\n" .
                        $this->_body_plain . "\n\n" .
                        '--' . $this->_alternative_boundary . "\n" .
                        'Content-Type: multipart/related; boundary="' . $this->_related_boundary . '"' . "\n\n" .
                        '--' . $this->_related_boundary . "\n" .
                        'Content-Type: text/html; charset="' . $this->_charset . '"' . "\n" .
                        'Content-Transfer-Encoding: ' . $this->_content_transfer_encoding . "\n\n" .
                        $this->_body_html . "\n\n";

        if ( !empty($this->_images) ) {
          foreach ( $this->_images as $image ) {
            $this->_body .= $this->_build_image($image, $this->_related_boundary);
          }

          unset($image);
        }

        $this->_body .= '--' . $this->_related_boundary . '--' . "\n\n" .
                        '--' . $this->_alternative_boundary . '--' . "\n\n";

        if ( !empty($this->_attachments) ) {
          foreach ( $this->_attachments as $attachment ) {
            $this->_body .= $this->_build_attachment($attachment, $this->_boundary);
          }

          unset($attachment);
        }

        $this->_body .= '--' . $this->_boundary . '--' . "\n\n";
      } elseif ( !empty($this->_body_html) && !empty($this->_images) ) {
        $this->_boundary = '=_____MULTIPART_MIXED_BOUNDARY____';
        $this->_related_boundary = '=_____MULTIPART_RELATED_BOUNDARY____';

        $this->_headers['MIME-Version'] = '1.0';
        $this->_headers['Content-Type'] = 'multipart/mixed; boundary="' . $this->_boundary . '"';

        foreach ( $this->_images as $image ) {
          $this->_body_html = str_replace('src="' . $image['filename'] . '"', 'src="cid:' . $image['id'] . '"', $this->_body_html);
        }

        unset($image);

        $this->_body = 'This is a multi-part message in MIME format.' . "\n\n" .
                       '--' . $this->_boundary . "\n" .
                       'Content-Type: multipart/related; boundary="' . $this->_related_boundary . '";' . "\n\n" .
                        '--' . $this->_related_boundary . "\n" .
                        'Content-Type: text/html; charset="' . $this->_charset . '"' . "\n" .
                        'Content-Transfer-Encoding: ' . $this->_content_transfer_encoding . "\n\n" .
                        $this->_body_html . "\n\n";

        foreach ( $this->_images as $image ) {
          $this->_body .= $this->_build_image($image, $this->_related_boundary);
        }

        unset($image);

        $this->_body .= '--' . $this->_related_boundary . '--' . "\n\n";

        foreach ( $this->_attachments as $attachment ) {
          $this->_body .= $this->_build_attachment($attachment, $this->_boundary);
        }

        unset($attachment);

        $this->_body .= '--' . $this->_boundary . '--' . "\n";
      } elseif ( !empty($this->_attachments) ) {
        $this->_boundary = '=_____MULTIPART_MIXED_BOUNDARY____';
        $this->_related_boundary = '=_____MULTIPART_RELATED_BOUNDARY____';

        $this->_headers['MIME-Version'] = '1.0';
        $this->_headers['Content-Type'] = 'multipart/mixed; boundary="' . $this->_boundary . '"';

        $this->_body = 'This is a multi-part message in MIME format.' . "\n\n" .
                       '--' . $this->_boundary . "\n" .
                       'Content-Type: multipart/related; boundary="' . $this->_related_boundary . '";' . "\n\n" .
                       '--' . $this->_related_boundary . "\n" .
                       'Content-Type: text/' . (empty($this->_body_plain) ? 'html' : 'plain') . '; charset="' . $this->_charset . '"' . "\n" .
                       'Content-Transfer-Encoding: ' . $this->_content_transfer_encoding . "\n\n" .
                       (empty($this->_body_plain) ? $this->_body_html : $this->_body_plain) . "\n\n" .
                       '--' . $this->_related_boundary . '--' . "\n\n";

        foreach ( $this->_attachments as $attachment ) {
          $this->_body .= $this->_build_attachment($attachment, $this->_boundary);
        }

        unset($attachment);

        $this->_body .= '--' . $this->_boundary . '--' . "\n";
      } elseif ( !empty($this->_body_html) ) {
        $this->_headers['MIME-Version'] = '1.0';
        $this->_headers['Content-Type'] = 'text/html; charset="' . $this->_charset . '"';
        $this->_headers['Content-Transfer-Encoding'] = $this->_content_transfer_encoding;

        $this->_body = $this->_body_html . "\n";
      } else {
        $this->_body = $this->_body_plain . "\n";
      }
    }

    $to_email_addresses = array();

    foreach ( $this->_to as $to ) {
      if ( ( strpos($to['email_address'], "\n") !== false ) || ( strpos($to['email_address'], "\r") !== false ) ) {
        return false;
      }

      if ( ( strpos($to['name'], "\n") !== false ) || ( strpos($to['name'], "\r") !== false ) ) {
        return false;
      }

      if ( empty($to['name']) ) {
        $to_email_addresses[] = $to['email_address'];
      } else {
        $to_email_addresses[] = '"' . $to['name'] . '" <' . $to['email_address'] . '>';
      }
    }

    unset($to);

    $cc_email_addresses = array();

    foreach ( $this->_cc as $cc ) {
      if ( empty($cc['name']) ) {
        $cc_email_addresses[] = $cc['email_address'];
      } else {
        $cc_email_addresses[] = '"' . $cc['name'] . '" <' . $cc['email_address'] . '>';
      }
    }

    unset($cc);

    $bcc_email_addresses = array();

    foreach ( $this->_bcc as $bcc ) {
      if ( empty($bcc['name']) ) {
        $bcc_email_addresses[] = $bcc['email_address'];
      } else {
        $bcc_email_addresses[] = '"' . $bcc['name'] . '" <' . $bcc['email_address'] . '>';
      }
    }

    unset($bcc);

    if ( empty($this->_from['name']) ) {
      $this->addHeader('From', $this->_from['email_address']);
    } else {
      $this->addHeader('From', '"' . $this->_from['name'] . '" <' . $this->_from['email_address'] . '>');
    }

    if ( !empty($cc_email_addresses) ) {
      $this->addHeader('Cc', implode(', ', $cc_email_addresses));
    }

    if ( !empty($bcc_email_addresses) ) {
      $this->addHeader('Bcc', implode(', ', $bcc_email_addresses));
    }

    $headers = '';

    foreach ( $this->_headers as $key => $value ) {
      $headers .= $key . ': ' . $value . "\n";
    }

    if ( empty($this->_from['email_address']) || empty($to_email_addresses) ) {
      return false;
    }

    if ( empty($this->_from['name']) ) {
      @ini_set('sendmail_from', $this->_from['email_address']);
    } else {
      @ini_set('sendmail_from', '"' . $this->_from['name'] . '" <' . $this->_from['email_address'] . '>');
    }

    @mail(implode(', ', $to_email_addresses), $this->_subject, $this->_body, $headers);

    @ini_restore('sendmail_from');
  }

  protected function _get_mime_type($file) {
    $ext = substr($file, strrpos($file, '.') + 1);

    $mime_types = array('gif' => 'image/gif',
                        'jpg' => 'image/jpeg',
                        'jpeg' => 'image/jpeg',
                        'jpe' => 'image/jpeg',
                        'bmp' => 'image/bmp',
                        'png' => 'image/png',
                        'tif' => 'image/tiff',
                        'tiff' => 'image/tiff',
                        'swf' => 'application/x-shockwave-flash');

    if (isset($mime_types[$ext])) {
      return $mime_types[$ext];
    } else {
      return 'application/octet-stream';
    }
  }

  protected function _build_attachment($attachment, $boundary) {
    return '--' . $boundary . "\n" .
           'Content-Type: ' . $attachment['mimetype'] . '; name="' . $attachment['filename'] . '"' . "\n" .
           'Content-Disposition: attachment' . "\n" .
           'Content-Transfer-Encoding: base64' . "\n\n" .
            $attachment['data'] . "\n\n";
  }

  protected function _build_image($image, $boundary) {
    return '--' . $boundary . "\n" .
           'Content-Type: ' . $image['mimetype'] . '; name="' . $image['filename'] . '"' . "\n" .
           'Content-ID: ' . $image['id'] . "\n" .
           'Content-Disposition: inline' . "\n" .
           'Content-Transfer-Encoding: base64' . "\n\n" .
            $image['data'] . "\n\n";
  }
}
?>