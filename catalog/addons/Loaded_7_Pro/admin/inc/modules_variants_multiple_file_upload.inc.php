<?php
    if (isset($data['simple_option']) && $data['simple_option'] !== false) {
      $options = array();
      $group_id = '';
      $group_title = '';
      unset($data['simple_option']);
      
      foreach($data as $key => $val) {
        if (isset($val['group_title']) && empty($val['group_title']) === false) {
          $group_title = $val['group_title'];
          break;
        }
      }      
      
      $string = '<style>.file-inputs { .margin-top-neg(); }</style><div class="margin-left margin-bottom">' .
                '  <table class="full-width">' . 
                '    <tr>' .
                '      <td valign="top" class="third-width"><label class="margin-right margin-top">' . $group_title . '</label></td>' .
                '      <td valign="top"><div id="file_upload_container">';

      reset($data);
      $cnt = 0;
      foreach($data as $key => $val) {  
        $price_ind = ((float)$val['price_modifier'] < 0.00) ? '-' : '+';
        $price_formatted = ((float)$val['price_modifier'] != 0.00) ? $price_ind . $lC_Currencies->format(number_format($val['price_modifier'], DECIMAL_PLACES), $lC_Currencies->getCode()) : null;
        $options[$val['value_id']] = $val['price_modifier']; 
        $group_id = $val['group_id'];
        $group_title = $val['group_title'];       
                    
        $string .= '        <div id="file_upload_div_' . $cnt . '" class="no-margin-top small-margin-bottom small-padding-left small-margin-left">' .
                   '         <label>' .
                   '           <input type="file" htitle="" group-id="' . $group_id . '" value-id="' . $val['value_id'] . '" class="file-inputs btn-primary btn-file" data-filename-placement="inside" title="' . $val['value_title'] . '" default="' . $val['value_title'] . '" name="simple_options_upload[]" value="' . $val['value_id'] . '" modifier="' . $val['price_modifier'] . '" onchange="refreshPrice();" id="simple_options_mupload_' . $cnt . '">' .
                   '           <input type="hidden" name="simple_options[' . $group_id . '][' . $val['value_id'] . '][]" value="' . $val['value_id'] . '" modifier="' . $val['price_modifier'] . '" id="simple_options_' . $cnt . '">' .
                   '         </label><i id="simple_options_remove_' . $cnt . '" class="fa fa-times margin-left red hidden" style="cursor:pointer;" onclick="removeFileUploadRow(\'' . $cnt . '\');"></i>' .
                   '       </div>';
        $cnt++;
      }                 
       
      $string .= '       </div></td>' .   
                 '    </tr>' .
                 '  </table>' .
                 '</div>';                     
      
    } else {      

      $string = '<div id="file_upload_container">';
      $cnt = 0;
      foreach ( $data['data'] as $field ) {
        $string .= '       <div id="file_upload_div_' . $cnt . '" class="form-group margin-left">' .
                   '         <label class="label-control" style="width:29%;">' . $data['title'] . '</label>' . 
                   '         <input type="file" htitle="' . $data['title'] . '" modifier="variant" group-id="' . $data['group_id'] . '" value-id="' . $field['id'] . '" class="file-inputs btn-primary btn-file mid-margin-left" data-filename-placement="inside" title="' . $field['text'] . '" default="' . $field['text'] . '" name="variants_upload[]" value="' . $field['id'] . '" onchange="refreshPrice();" id="variants_mupload_' . $cnt . '">' .
                   '         <input type="hidden" name="variants[' . $data['group_id'] . ']" value="' . $field['id'] . '" id="variants_' . $data['group_id'] . '_' . $field['id'] . '">' .
                   '         <i id="variants_remove_' . $cnt . '" class="fa fa-times margin-left red hidden" style="cursor:pointer;" onclick="removeFileUploadRow(\'' . $cnt . '\');"></i>' .
                   '       </div>';        
        $cnt++;
      }
      $string .= '</div>'; 
    }
?>