<?php
  define('HTTP_SERVER', 'http://loadedbeta2.resultsonlyhosting.com');
  define('HTTPS_SERVER', 'https://loadedbeta2.resultsonlyhosting.com');
  define('ENABLE_SSL', true);
  define('HTTP_COOKIE_DOMAIN', 'loadedbeta2.resultsonlyhosting.com');
  define('HTTPS_COOKIE_DOMAIN', 'loadedbeta2.resultsonlyhosting.com');
  define('HTTP_COOKIE_PATH', '/');
  define('HTTPS_COOKIE_PATH', '/');
  define('DIR_WS_HTTP_CATALOG', '/');
  define('DIR_WS_HTTPS_CATALOG', '/');
  define('DIR_WS_ADMIN', 'admin/');
  define('DIR_WS_IMAGES', 'images/');

  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', '/var/www/vhosts/resultsonlyhosting.com/loadedbeta2/');
  define('DIR_FS_ADMIN', '/var/www/vhosts/resultsonlyhosting.com/loadedbeta2/admin/');
  define('DIR_FS_WORK', '/var/www/vhosts/resultsonlyhosting.com/loadedbeta2/includes/work/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');
  define('DIR_FS_BACKUP', '/var/www/vhosts/resultsonlyhosting.com/loadedbeta2/admin/backups/');

  define('DB_SERVER', 'localhost');
  define('DB_SERVER_USERNAME', 'loadedbeta2Admin');
  define('DB_SERVER_PASSWORD', 'LoadedBeta2');
  define('DB_DATABASE', 'loadedbeta2_SQL');
  define('DB_DATABASE_CLASS', 'mysqli');
  define('DB_TABLE_PREFIX', '');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'database');
?>