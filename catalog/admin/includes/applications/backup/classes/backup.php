<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: backup.php v1.0 2013-08-08 datazen $
*/
class lC_Backup_Admin {
  /*
  * Returns the backups datatable data for listings
  *
  * @access public
  * @return array
  */
  public static function getAll() { 
    global $lC_Language;

    $media = $_GET['media'];

    $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_BACKUP);
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('zip');
    $lC_DirectoryListing->setCheckExtension('sql');
    $lC_DirectoryListing->setCheckExtension('gz');

    $cnt = 0;
    $result = array('aaData' => array());
    foreach ( $lC_DirectoryListing->getFiles() as $file ) {
      $downloadLink = lc_href_link_admin(FILENAME_DEFAULT, 'backup&action=download&file=' . $file['name']);
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $file['name'] . '" id="' . $file['name'] . '"></td>';
      $filename = '<td><a href="' . $downloadLink . '"><span class="icon-download icon-orange with-tooltip" title="' . $lC_Language->icon_download . '">&nbsp;' . $file['name'] . '</a></td>';
      $date = '<td>' . lC_DateTime::getShort(lC_DateTime::fromUnixTimestamp(@filemtime(DIR_FS_BACKUP . $file['name'])), true) . '</td>';
      $size = '<td>' . number_format(@filesize(DIR_FS_BACKUP . $file['name'])) . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
      <a href="' . ((int)($_SESSION['admin']['access']['backup'] < 3) ? '#' : 'javascript://" onclick="restoreEntry(\'' . $file['name'] . '\')') . '" class="button icon-pencil' . ((int)($_SESSION['admin']['access']['backup'] < 3) ? ' disabled' : NULL) . '">' .  (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_restore')) . '</a>
      <a href="' . ((int)($_SESSION['admin']['access']['backup'] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $file['name'] . '\', \'' . urlencode($file['name']) . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access']['backup'] < 4) ? ' disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
      </span></td>';  

      $result['aaData'][] = array("$check", "$filename", "$date", "$size", "$action");      
      $cnt++;
    }
    $result['total'] = $cnt; 

    return $result;
  }
  /*
  * Create the database backup file
  *
  * @param boolean $compression True = create backup using compression
  * @param boolean $download_only True = download only, do not create a local backup
  * @access public
  * @return boolean
  */
  public static function backup($compression = null, $download_only = false) {
    global $lC_Database;

    if ( lc_empty(DIR_FS_BACKUP) || !@is_dir(DIR_FS_BACKUP) || !@is_writeable(DIR_FS_BACKUP) ) {
      return false;
    }

    lc_set_time_limit(0);

    $backup_file = 'db_' . DB_DATABASE . '-' . @date('YmdHis') . '.sql';

    $fp = @fopen(DIR_FS_BACKUP . $backup_file, 'w');

    $schema = '# LoadedCommerce, Innovative eCommerce Solutions' . "\n" .
    '# http://www.loadedcommerce.com' . "\n" .
    '#' . "\n" .
    '# Database Backup For ' . STORE_NAME . "\n" .
    '# Copyright (c) ' . @date('Y') . ' ' . STORE_OWNER . "\n" .
    '#' . "\n" .
    '# Database: ' . DB_DATABASE . "\n" .
    '# Database Server: ' . DB_SERVER . "\n" .
    '#' . "\n" .
    '# Backup Date: ' . lC_DateTime::getShort(null, true) . "\n\n";

    @fputs($fp, $schema);

    $Qtables = $lC_Database->query('show tables');

    while ( $Qtables->next() ) {
      $table = $Qtables->value('Tables_in_' . DB_DATABASE);

      $schema = 'drop table if exists ' . $table . ';' . "\n" .
      'create table ' . $table . ' (' . "\n";

      $table_list = array();

      $Qfields = $lC_Database->query('show fields from :table');
      $Qfields->bindTable(':table', $table);
      $Qfields->execute();

      while ( $Qfields->next() ) {
        $table_list[] = $Qfields->value('Field');

        $schema .= '  ' . $Qfields->value('Field') . ' ' . $Qfields->value('Type');

        if ( !lc_empty($Qfields->value('Default')) ) {
          $schema .= ' default \'' . $Qfields->value('Default') . '\'';
        }

        if ( $Qfields->value('Null') != 'YES' ) {
          $schema .= ' not null';
        }

        if ( !lc_empty($Qfields->value('Extra')) ) {
          $schema .= ' ' . $Qfields->value('Extra');
        }

        $schema .= ',' . "\n";
      }

      $schema = substr($schema, 0, -2);

      // add the keys
      $Qkeys = $lC_Database->query('show keys from :table');
      $Qkeys->bindTable(':table', $table);
      $Qkeys->execute();

      $index = array();

      while ( $Qkeys->next() ) {
        $kname = $Qkeys->value('Key_name');

        if ( !isset($index[$kname]) ) {
          $index[$kname] = array('unique' => !$Qkeys->value('Non_unique'),
            'fulltext' => ($Qkeys->value('Index_type') == 'FULLTEXT' ? true : false),
            'columns' => array());
        }

        $index[$kname]['columns'][] = $Qkeys->value('Column_name');
      }

      foreach ( $index as $kname => $info ) {
        $schema .= ',' . "\n";

        $columns = implode($info['columns'], ', ');

        if ( $kname == 'PRIMARY' ) {
          $schema .= '  PRIMARY KEY (' . $columns . ')';
        } elseif ( $info['fulltext'] === true ) {
          $schema .= '  FULLTEXT ' . $kname . ' (' . $columns . ')';
        } elseif ( $info['unique'] ) {
          $schema .= '  UNIQUE ' . $kname . ' (' . $columns . ')';
        } else {
          $schema .= '  KEY ' . $kname . ' (' . $columns . ')';
        }
      }

      $schema .= "\n" . ');' . "\n\n";

      @fputs($fp, $schema);

      // dump the data from the tables except from the sessions table and the who's online table
      if ( ( $table != TABLE_SESSIONS ) && ( $table != TABLE_WHOS_ONLINE ) ) {
        $Qrows = $lC_Database->query('select :columns from :table');
        $Qrows->bindRaw(':columns', implode(', ', $table_list));
        $Qrows->bindTable(':table', $table);
        $Qrows->execute();

        while ( $Qrows->next() ) {
          $rows = $Qrows->toArray();

          $schema = 'insert into ' . $table . ' (' . implode(', ', $table_list) . ') values (';

          foreach ( $table_list as $i ) {
            if ( !isset($rows[$i]) ) {
              $schema .= 'NULL, ';
            } elseif ( strlen($rows[$i]) > 0 ) {
              $row = addslashes($rows[$i]);
              $row = str_replace("\n#", "\n".'\#', $row);

              $schema .= '\'' . $row . '\', ';
            } else {
              $schema .= '\'\', ';
            }
          }
          $schema = substr($schema, 0, -2) . ');' . "\n";

          fputs($fp, $schema);
        }
      }
    }

    fclose($fp);

    unset($schema);

    switch ( $compression ) {
      case 'gzip':
        exec(CFG_APP_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);

        $backup_file .= '.gz';

        break;

      case 'zip':
        exec(CFG_APP_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
        if(file_exists(DIR_FS_BACKUP . $backup_file)){
          unlink(DIR_FS_BACKUP . $backup_file);
        }

        $backup_file .= '.zip';

        break;
    }

    if ( $download_only === true ) {
      header('Content-type: application/x-octet-stream');
      header('Content-disposition: attachment; filename=' . $backup_file);

      readfile(DIR_FS_BACKUP . $backup_file);

      if(file_exists(DIR_FS_BACKUP . $backup_file)){
        unlink(DIR_FS_BACKUP . $backup_file);
      }
      exit;
    }

    if ( file_exists(DIR_FS_BACKUP . $backup_file) ) {
      return true;
    }

    return false;
  }
  /*
  * Restore a database backup file
  *
  * @param string $filename The database backup file name
  * @access public
  * @return boolean
  */
  public static function restore($filename = false) {
    global $lC_Database, $lC_Session;

    lc_set_time_limit(0);

    if ( $filename !== false ) {
      if ( file_exists(DIR_FS_BACKUP . $filename) ) {
        $restore_file = DIR_FS_BACKUP . $filename;
        $extension = substr($filename, -3);

        if ( ( $extension == 'sql' ) || ( $extension == '.gz' ) || ( $extension == 'zip' ) ) {
          switch ( $extension ) {
            case 'sql':
              $restore_from = $restore_file;

              $remove_raw = false;

              break;

            case '.gz':
              $restore_from = substr($restore_file, 0, -3);
              exec(CFG_APP_GUNZIP . ' ' . $restore_file . ' -c > ' . $restore_from);

              $remove_raw = true;

              break;

            case 'zip':
              $restore_from = substr($restore_file, 0, -4);
              exec(CFG_APP_UNZIP . ' ' . $restore_file . ' -d ' . DIR_FS_BACKUP);

              $remove_raw = true;

              break;
          }

          if ( isset($restore_from) && file_exists($restore_from) ) {
            $fd = fopen($restore_from, 'rb');
            $restore_query = fread($fd, filesize($restore_from));
            fclose($fd);
          }
        }
      }
    } else {
      $sql_file = new upload('sql_file');

      if ( $sql_file->parse() ) {

        $extension = substr($sql_file->filename, -3);

        switch ( $extension ) {
          case 'sql':
            $restore_from = $sql_file->tmp_filename;

            $remove_raw = false;

            break;

          case '.gz':
            $restore_from = substr($sql_file->tmp_filename, 0, -3);
            exec(CFG_APP_GUNZIP . ' ' . $sql_file->tmp_filename . ' -c > ' . $restore_from);

            $remove_raw = true;

            break;

          case 'zip':
            $restore_from = DIR_FS_WORK.substr($sql_file->filename, 0, -4);
            exec(CFG_APP_UNZIP . ' ' . $sql_file->tmp_filename . ' -d ' . DIR_FS_WORK);

            $remove_raw = true;

            break;
        }
        $restore_query = fread(fopen($restore_from, 'rb'), filesize($restore_from));
        $filename = $restore_from;
      }
    }

    if ( isset($restore_query) && !empty($restore_query) ) {
      $sql_array = array();
      $sql_length = strlen($restore_query);
      $pos = strpos($restore_query, ';');

      // loop and remove comments
      for ( $i = $pos; $i < $sql_length; $i++ ) {
        if ( $restore_query[0] == '#' ) {
          $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
          $sql_length = strlen($restore_query);
          $i = strpos($restore_query, ';')-1;
          continue;
        }

        if ( $restore_query[$i+1] == "\n" ) {
          for ( $j = ($i + 2); $j < $sql_length; $j++ ) {
            if ( trim($restore_query[$j]) != '' ) {
              $next = substr($restore_query, $j, 6);

              if ( $next[0] == '#' ) {
                // find out where the break position is so we can remove this line (#comment line)
                for ( $k = $j; $k < $sql_length; $k++ ) {
                  if ( $restore_query[$k] == "\n" ) {
                    break;
                  }
                }

                $query = substr($restore_query, 0, $i+1);
                $restore_query = substr($restore_query, $k);
                // join the query before the comment appeared, with the rest of the dump
                $restore_query = $query . $restore_query;
                $sql_length = strlen($restore_query);
                $i = strpos($restore_query, ';')-1;
                continue 2;
              }

              break;
            }
          }

          if ( $next == '' ) { // get the last insert query
            $next = 'insert';
          }

          if ( stristr($next, 'create') || stristr($next, 'insert') || stristr($next, 'drop t') ) {
            $next = '';
            $sql_array[] = substr($restore_query, 0, $i);
            $restore_query = ltrim(substr($restore_query, $i+1));
            $sql_length = strlen($restore_query);
            $i = strpos($restore_query, ';')-1;
          }
        }
      }

      for ( $i = 0, $n = sizeof($sql_array); $i < $n; $i++ ) {  
        $lC_Database->simpleQuery($sql_array[$i]);
      }

      // $lC_Session->close();

      // empty the sessions table
      // $Qsessions = $lC_Database->query('delete from :table_sessions');
      // $Qsessions->bindTable(':table_sessions', TABLE_SESSIONS);
      // $Qsessions->execute();

      // empty the who's online table
      $Qwho = $lC_Database->query('delete from :table_whos_online');
      $Qwho->bindTable(':table_whos_online', TABLE_WHOS_ONLINE);
      $Qwho->execute();

      $Qcfg = $lC_Database->query('delete from :table_configuration where configuration_key = :configuration_key');
      $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qcfg->bindValue(':configuration_key', 'DB_LAST_RESTORE');
      $Qcfg->execute();

      $Qcfg = $lC_Database->query('insert into :table_configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ("Last Database Restore", "DB_LAST_RESTORE", :filename, "Last database restore file", 6, 0, now())');
      $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qcfg->bindValue(':filename', $filename);
      $Qcfg->execute();

      lC_Cache::clear('configuration');

      if ( isset($remove_raw) && ( $remove_raw === true ) ) {
        unlink($restore_from);
      }

      //lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, 'login'));  
      return true;
    }

    return false;
  }
  /*
  * Delete the database backup file
  *
  * @param string $filename The database backup file name to delete
  * @access public
  * @return boolean
  */
  public static function delete($filename) {
    $filename = basename($filename);

    if ( !empty($filename) && file_exists(DIR_FS_BACKUP . $filename) ) {
      if ( @unlink(DIR_FS_BACKUP . $filename) ) {
        return true;
      }
    }

    return false;
  }
  /*
  * Batch delete database backup files
  *
  * @param array $batch The database backup file name's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ( $batch as $id ) {
      lC_Backup_Admin::delete($id);
    }
    return true;
  } 
  /*
  * Clear the last backup notice
  *
  * @access public
  * @return boolean
  */
  public static function forget() {
    global $lC_Database;

    $Qcfg = $lC_Database->query('delete from :table_configuration where configuration_key = :configuration_key');
    $Qcfg->bindTable(':table_configuration', TABLE_CONFIGURATION);
    $Qcfg->bindValue(':configuration_key', 'DB_LAST_RESTORE');
    $Qcfg->setLogging($_SESSION['module']);
    $Qcfg->execute();

    if ( !$lC_Database->isError() ) {
      lC_Cache::clear('configuration');

      return true;
    }

    return false;
  }
}
?>