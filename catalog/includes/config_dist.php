<?php
  define('HTTP_SERVER', 'http://www.mystore.com');
  define('HTTPS_SERVER', 'https://www.mystore.com');
  define('ENABLE_SSL', true);
  define('HTTP_COOKIE_DOMAIN', 'www.mystore.com');
  define('HTTPS_COOKIE_DOMAIN', 'www.mystore.com');
  define('HTTP_COOKIE_PATH', '/catalog/');
  define('HTTPS_COOKIE_PATH', '/catalog/');
  define('DIR_WS_HTTP_CATALOG', '/catalog/');
  define('DIR_WS_HTTPS_CATALOG', '/catalog/');
  define('DIR_WS_IMAGES', 'images/');

  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  define('DIR_FS_CATALOG', '/home/user/www/catalog/');
  define('DIR_FS_WORK', '/home/user/www/catalog/includes/work/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');
  define('DIR_FS_BACKUP', '/home/user/www/catalog/admin/backups/');

  define('DB_SERVER', 'localhost');
  define('DB_SERVER_USERNAME', 'dbuser');
  define('DB_SERVER_PASSWORD', 'dbpass!');
  define('DB_DATABASE', 'dbname');
  define('DB_DATABASE_CLASS', 'mysqli');
  define('DB_TABLE_PREFIX', 'lc_');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'database');
?>