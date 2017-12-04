<?php

/*************************
TO-DO detail file format
*************************/

namespace Core;

class FormValidator{

    protected $_data = array();
    protected $_format = array();

    public function __construct(){
        $this->setData($_POST);
    }

    /* function to check if POST data where submitted */
    public function formSubmitted($fields = array()){
        if(!empty($this->_data)){
            if(empty($fields))
                return true;
            else{
                $all_fields_received = true;
                foreach($fields as $f){
                    if(!$this->fieldSubmitted($f)){
                        \Core\Logger::AddMessage("Field ".$f." not received.");
                        $all_fields_received = false;
                    }
                }
                return $all_fields_received;
            }
        }
        return false;
    }

    /* function to check if a specific field was submitted */
    public function fieldSubmitted($field){
        if(isset($this->_data[$field]))
            return true;
        else
            return false;
    }

    /* function to check field format (see header for fofmat detail) */
    public function checkFieldFormat($field, $format){
        if(!$this->fieldIsEmpty($field)){
          $this->addFormat($field, $format);
          $valid = true;
          foreach($format as $type => $value){
            if(strtolower($type) != "optionnal" && strtolower($type) != "optionnalif" && strtolower($type) != "user_message"){
              $check_method = "check".ucfirst(strtolower($type));
              if(!method_exists($this, $check_method) || !$this->$check_method($field, $value))
                $valid = false;
            }
          }
          return $valid;
        }
        else if ($this->fieldIsOptionnal($field, $format) && $this->fieldIsEmpty($field))
          return true;
        else
            \Core\Logger::AddMessage("Field ".$field." empty or impossible to check.", "error", "user");

        return false;
    }

    /* check if field is optionnal */
    protected function fieldIsOptionnal($field, $format){
      if(isset($format["optionnal"]))
        return true;
      elseif(isset($format["optionnalif"]) && is_array($format["optionnalif"])){
        $valid = false;
        foreach($format["optionnalif"] as $field_to_check => $values_to_check){
          if(is_array($values_to_check)){
            if(!$this->fieldSubmitted($field_to_check) || in_array($this->getFieldValue($field_to_check), $values_to_check)){
              //\Core\Logger::AddMessage("Field ".$field." is optionnal");
              return true;
            }
          }
        }
        return $valid;
      }
      //\Core\Logger::AddMessage("Field ".$field." is not optionnal");
      return false;
    }

    /* check if field is empty */
    protected function fieldIsEmpty($field){
      if(isset($this->_data[$field]) &&
            (empty($this->_data[$field]) ||
              ((!is_array($this->_data[$field]) && strlen($this->_data[$field]) <= 0) || (is_array($this->_data[$field]) && count($this->_data[$field]) <= 0))
            )
        )
        return true;
      //\Core\Logger::AddMessage("Field ".$field." is not empty(".$this->data[$field].")".strlen($this->data[$field]).".", "info", "dev");
      return false;
    }

    /* function to check field format on multiple fields */
    public function checkFieldsFormat($fields_formats){
        $this->setFormat($fields_formats);

        $formats_valid = true;
        foreach($fields_formats as $field => $format){
            if(!$this->checkFieldFormat($field,$format))
                $formats_valid = false;
        }
        return $formats_valid;
    }

    /******************************************
    ************  CHECK STRING SIZE **********
    ******************************************/
    /* function to check min size of field */
    protected function checkSize($field, $size){
        if(strlen($this->getFieldValue($field)) == $size )
            return true;
        \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field. " must have a size of ".$size." characters."), "error", "user");
        return false;
    }

    /* function to check min size of field */
    protected function checkMin($field, $min){
        if(strlen($this->getFieldValue($field)) >= $min )
            return true;
        \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field. " must have a min length of ".$min." characters."), "error", "user");
        return false;
    }

    /* function to check max size of field */
    protected function checkMax($field, $max){
        if(!$this->fieldIsEmpty($field) && strlen($this->getFieldValue($field)) <= $max )
            return true;
        \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field. " must have a max length of ".$max." characters."),"error", "user");
        return false;
    }

    /******************************************
    ************  CHECK FIELD TYPE ***********
    ******************************************/
    /* check if field is only alphabetic */
    protected function checkAlpha($field, $value){
      if(ctype_alpha($this->getFieldValue($field)))
        return true;
    \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field." (".$this->getFieldValue($field).") has to be alphabetic."), "error", "user");
      return false;
    }

    /* check if field is only alphanumeric */
    protected function checkAlphanum($field, $value){
      if(ctype_alnum($this->getFieldValue($field)))
        return true;
      \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field." (".$this->getFieldValue($field).") has to be alphanumeric."), "error", "user");
      return false;
    }

    /* check if field is only alphanumeric */
    protected function checkNumeric($field, $value = true){
        if(is_numeric($this->getFieldValue($field)))
          return true;
      \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field." (".$this->getFieldValue($field).") has to be numeric."), "error", "user");
      return false;
    }


    /* check if value is integer */
    protected function checkInt($field, $value = true){
        if(is_numeric($this->getFieldValue($field)) && is_int(intval($this->getFieldValue($field))))
          return true;
      \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field." (".$this->getFieldValue($field).") has to be an integer."), "error", "user");
      return false;
    }

    /* function to check that field value has an email format */
    protected function checkEmail($field, $value = true){
        if(filter_var($this->getFieldValue($field), FILTER_VALIDATE_EMAIL))
            return true;
        \Core\Logger::AddMessage($this->getErrMsg($field, "Invalid email format for field ".$field), "error", "user");
        return false;
    }

    /******************************************
    ************  CHECK FIELD CONTENT ***********
    ******************************************/
    /* function to check that 2 fields are identicals */
    protected function checkIdentical($field, $other_field_name){
        if($this->fieldSubmitted($other_field_name) && $this->getFieldValue($field) ==  $this->getFieldValue($other_field_name))
            return true;
        \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field. "  must be identical to ".$other_field_name."."), "error", "user");
        return false;
    }

    /* check number of uppercases letters in string */
    protected function checkMinuppercases($field, $nb_uppercase_required){
        if(preg_match_all('/[A-Z]/', $this->getFieldValue($field)) >= $nb_uppercase_required)
         return true;
        \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field. " must contain at least".$nb_uppercase_required." upppercases character ".preg_match_all('/[A-Z]/', $this->getFieldValue($field))." given."), "error", "user");
        return false;
    }

    /* check number of digits in string */
    protected function checkMindigits($field, $nb_digits_required){
        if(preg_match_all('/[0-9]/', $this->getFieldValue($field)) >= $nb_digits_required)
          return true;
        \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field. " must contain at least".$nb_digits_required." digits."), "error", "user");
        return false;
    }

    /* check number of spaces in string */
    protected function checkMaxnbspaces($field, $nb_allowed_spaces){
      if(substr_count($this->getFieldValue($field), ' ') <= $nb_allowed_spaces)
        return true;
      \Core\Logger::AddMessage($this->getErrMsg($field, "Not more than ".$nb_allowed_spaces. "  spaces are allowed in ".$field."."), "error", "user");
      return false;
    }

    /* check if content is an hex color */
    protected function checkHexcolor($field, $value="true"){
      if(preg_match_all('/#([a-f0-9]{3}){1,2}\b/i', $this->getFieldValue($field)) >= 1)
        return true;
      \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field. " is not a valid hex color."), "error", "user");
      return false;
    }

    /* check if content is in list */
    protected function checkInlist($field, $value = array()){
      if(is_array($value) && in_array($this->getFieldValue($field), $value))
        return true;
      \Core\Logger::AddMessage($this->getErrMsg($field, "Field ".$field. " have can only takes the following values ".implode(", ", $value)."."), "error", "user");
      return false;
    }

    /******************************************
    ************  CHECK DATE FUNCTIONS *******
    ******************************************/

    function isValidDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    //function to check date format
    function checkDate($field, $date_format)
    {
        if(isset($date_format["format"])){
          if($this->isValidDate($this->getFieldValue($field), $date_format["format"])){
            //get date
            $date = new \DateTime($this->getFieldValue($field));
            //remove format from format
            unset($date_format["format"]);
            //parse all checks on date
            $valid = true;
            foreach($date_format as $check => $value){
              $method = "checkDate".ucfirst(strtolower($check));
              if(!method_exists($this, $method)){
                  \Core\Logger::AddMessage("Impossible to check ".$check." on date.", "error", "dev");
                  $valid = false;
              }else if (!$this->isValidDate($value)){
                \Core\Logger::AddMessage("Impossible to check date for field ".$field.", format not defined.", "error", "dev");
              }
              else{
                $date_comp = new \DateTime($value);
                if(!$this->$method($field, $date, $date_comp))
                  $valid = false;
              }
            }
            return $valid;
          }else{
            \Core\Logger::AddMessage($this->getErrMsg($field, "Invalid date format for field ".$field."."), "error", "user");
          }
        }else{
          \Core\Logger::AddMessage("Impossible to check date for field ".$field.", format not defined.", "error", "dev");
        }
        return false;
    }

    //check if date is before a reference date
    protected function checkDateBefore($field, $date_field, $date_comp){
      if($date_field < $date_comp)
        return true;
      \Core\Logger::AddMessage($this->getErrMsg($field, $date_field->format('Y-m-d')." have to be before ".$date_comp->format('Y-m-d')."."), "error", "user");
      return false;
    }

    //check if date is after a reference date
    protected function checkDateAfter($field, $date_field, $date_comp){
      if($date_field > $date_comp)
        return true;
      \Core\Logger::AddMessage($this->getErrMsg($field, $date_field->format('Y-m-d')." have to be after ".$date_comp->format('Y-m-d')."."), "error", "user");
      return false;
    }

    /******************************************
    *****************  CHECK IMAGE ***********
    ******************************************/
    /* check field in $_FILES data */
    protected function checkFileFieldArray($field){
      if(isset($this->_data[$field]["name"]) && isset($this->_data[$field]["tmp_name"]))
        return true;
      \Core\Logger::AddMessage("Invalid file received for field ".$field.".", "error", "dev");
      return false;
    }

    /* check format given by controller for this field */
    protected function checkFileFieldFormat($format){
      if(isset($format["target_dir"]) && isset($format["max_size"]))
        return true;
      \Core\Logger::AddMessage("Image file directory not defined or max size not defined.", "error", "dev");
      return false;
    }

    /* build target path */
    protected function getFileDirectory($format){
      $target_dir_path = __DIR__."/../".$format["target_dir"];
      if(is_dir($target_dir_path) || mkdir($target_dir_path)){
        return $target_dir_path;
      }
      \Core\Logger::AddMessage("Upload directory ".$target_dir_path." does not exists.", "error", "dev");
      return "";
    }

    /* build target file name */
    protected function getTargetFileName($field, $format, $target_dir_path){
      //set date as filename prefix if necessary
      $name_prefix = "";
      if(isset($format["date_prefix"]))
        $name_prefix = date("YmdHis-");

      $file_pathinfo = pathinfo($this->_data[$field]["name"]);
      //var_dump($file_pathinfo);
      //define target filename
      if(isset($format["target_file_name"]))
        return $target_dir_path. "/" . $name_prefix . $format["target_file_name"].".".$file_pathinfo["extension"];
      else
        return $target_dir_path. "/" . $name_prefix . basename($this->_data[$field]["name"]);
    }

    /* check if uploaded file is an image */
    protected function checkIfImage($field, $format){
      if(!empty($this->_data[$field]["tmp_name"]) && getimagesize($this->_data[$field]["tmp_name"]) !== false)
        return true;
      \Core\Logger::AddMessage("File uploaded is not an image.", "error", "dev");
      return false;
    }

    /* check uploaded file extension */
    protected function checkFileExtension($field, $format, $target_file){
      $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
      if(isset($format["allowed_extensions"]) && in_array($file_extension, $format["allowed_extensions"]))
        return true;
      else if(!isset($format["allowed_extensions"]) && $this->checkIfImage($field, $format) && in_array($file_extension, ["jpg", "jpeg", "png", "gif", "bmp"]))
        return true;
      \Core\Logger::AddMessage("Invalid format for file: ".$file_extension, "error", "dev");
      return false;
    }

    /* check uploaded file size */
    protected function checkFileSize($field, $format){
      if($this->_data[$field]["size"] <= $format["max_size"])
        return true;
      \Core\Logger::AddMessage("Invalid file size, max size is ".$format["max_size"]." and your file is ".$this->_data[$field]["size"].".", "error", "dev");
      return false;
    }

    /* check uploaded file */
    protected function checkUpload($field, $format){
      if($this->checkFileFieldArray($field)
        && $this->checkFileFieldFormat($format)
        && ($target_dir_path = $this->getFileDirectory($format)) != ""
        && ($target_file = $this->getTargetFileName($field, $format, $target_dir_path)) != ""
        && $this->checkFileExtension($field, $format, $target_file)
        && $this->checkFileSize($field, $format))
        {
          if(isset($format["clean_dir_before_add"]))
            \Core\FilesManager::deleteAllFromDir($target_dir_path);

          if (move_uploaded_file($this->_data[$field]["tmp_name"], $target_file))
            return true;
          else
            \Core\Logger::AddMessage("Sorry, there was an error uploading your file.", "error", "user");
        }
      return false;
    }


    /* reset content of form */
    public function resetFormContent(){
        $this->_data = array();
    }

    /* SETTERS */
    public function setData($data){
        $this->_data = $data;
    }

    public function setFormat($format){
        $this->_format = $format;
    }

    public function addFormat($field, $format){
        $this->_format[$field] = $format;
    }

    /* GETTERS */
    protected function getFieldValue($f, $default = null){
        if($this->fieldSubmitted($f)){
            if(isset($this->_data[$f]))
              return $this->_data[$f];
          }
        return $default;
    }

    public function getFieldValueSecure($f, $default=null){
      if(is_string($this->getFieldValue($f, $default)))
        return htmlentities(trim($this->getFieldValue($f, $default)));
      else
        return $this->getFieldValue($f, $default);
    }

    public function getFieldAsHashedPassword($f){
        $pass = $this->getFieldValueSecure($f);
        $options = [
            'cost' => 12,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
        ];
        return password_hash($pass, PASSWORD_BCRYPT, $options);
    }

    protected function getErrMsg($field, $default){
      foreach($this->_format as $fi => $fo){
        if($fi == $field && isset($fo["user_message"]) && !empty($fo["user_message"]))
          return $fo["user_message"];
      }
      return $default;
    }

}

?>
