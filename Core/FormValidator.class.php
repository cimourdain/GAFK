<?php

/*************************
TO-DO detail file format
*************************/

namespace Core;

class FormValidator{

    protected $_data = array();

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
          foreach($format as $type => $value){
            $valid = true;
            if(strtolower($type) != "optionnal" && strtolower($type) != "optionnalif"){
              $check_method = "check".ucfirst(strtolower($type));
              if(!method_exists($this, $check_method) || $this->$check_method($field, $value)){
                $valid = false;
              }
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

    protected function fieldIsOptionnal($field, $format){
      if(isset($format["optionnal"]))
        return true;
      elseif(isset($format["optionnalif"]) && is_array($format["optionnalif"])){
        $valid = true;
        foreach($format["optionnalif"] as $field_to_check => $values_to_check){
          if(is_array($values_to_check)){
            if(!$this->fieldSubmitted($field_to_check) || in_array($this->getFieldValue($field_to_check), $values_to_check)){
              \Core\Logger::AddMessage("Field ".$field." is not optionnal");
              return false;
            }
          }
        }
        return $valid;
      }
      //\Core\Logger::AddMessage("Field ".$field." is not optionnal");
      return false;
    }

    protected function fieldIsEmpty($field){
      if(isset($this->_data[$field]) && (empty($this->_data[$field]) || strlen($this->_data[$field]) <= 0))
        return true;
      //\Core\Logger::AddMessage("Field ".$field." is not empty(".$this->data[$field].")".strlen($this->data[$field]).".", "info", "dev");
      return false;
    }

    /* function to check field format on multiple fields */
    public function checkFieldsFormat($fields_formats){
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
        else if($this->loggerDefined())
            \Core\Logger::AddMessage("Field ".$field. " must have a size of ".$size." characters.", "error", "user");
        return false;
    }

    /* function to check min size of field */
    protected function checkMin($field, $min){
        if(strlen($this->getFieldValue($field)) >= $min )
            return true;
        else if($this->loggerDefined())
            \Core\Logger::AddMessage("Field ".$field. " must have a min length of ".$min." characters.", "error", "user");
        return false;
    }

    /* function to check max size of field */
    protected function checkMax($field, $max){
        if(!$this->fieldIsEmpty($field) && strlen($this->getFieldValue($field)) <= $max )
            return true;
        else if($this->loggerDefined())
            \Core\Logger::AddMessage("Field ".$field. " must have a max length of ".$max." characters.","error", "user");

        return false;
    }

    /******************************************
    ************  CHECK FIELD TYPE ***********
    ******************************************/
    /* check if field is only alphabetic */
    protected function checkAlpha($field, $value){
      if(ctype_alpha($this->getFieldValue($field)))
        return true;
        else if($this->loggerDefined())
            \Core\Logger::AddMessage("Field ".$field." (".$this->getFieldValue($field).") has to be alphabetic.", "error", "user");
      return false;
    }

    /* check if field is only alphanumeric */
    protected function checkAlphanum($field, $value){
      if(ctype_alnum($this->getFieldValue($field)))
        return true;
      else if($this->loggerDefined())
          \Core\Logger::AddMessage("Field ".$field." (".$this->getFieldValue($field).") has to be alphanumeric.", "error", "user");
      return false;
    }

    /* check if field is only alphanumeric */
    protected function checkNumeric($field, $value = true){
        if(is_numeric($this->getFieldValue($field)))
          return true;
        else if($this->loggerDefined())
          \Core\Logger::AddMessage("Field ".$field." (".$this->getFieldValue($field).") has to be numeric.", "error", "user");
      return false;
    }


    /* check if value is integer */
    protected function checkInt($field, $value = true){
        if(is_numeric($this->getFieldValue($field)) && is_int(intval($this->getFieldValue($field))))
          return true;
        else if($this->loggerDefined())
          \Core\Logger::AddMessage("Field ".$field." (".$this->getFieldValue($field).") has to be an integer.", "error", "user");
      return false;
    }

    /* function to check that field value has an email format */
    protected function checkEmail($field, $value = true){
        if(filter_var($this->getFieldValue($field), FILTER_VALIDATE_EMAIL))
            return true;
        else if($this->loggerDefined())
            \Core\Logger::AddMessage("Invalid email format for field ".$field, "error", "user");
        return false;
    }

    /* function to check that 2 fields are identicals */
    protected function checkIdentical($field, $other_field_name){
        if($this->fieldSubmitted($other_field_name) && $this->getFieldValue($field) ==  $this->getFieldValue($other_field_name))
            return true;
        else if($this->loggerDefined())
          \Core\Logger::AddMessage("Field ".$field. "  must be identical to ".$other_field_name.".", "error", "user");
        return false;
    }

    /* check number of uppercases letters in string */
    protected function checkMinuppercases($field, $nb_uppercase_required){
        if(preg_match_all('/[A-Z]/', $this->getFieldValue($field)) >= $nb_uppercase_required)
         return true;
        else if($this->loggerDefined())
           \Core\Logger::AddMessage("Field ".$field. " must contain at least".$nb_uppercase_required." upppercases character ".preg_match_all('/[A-Z]/', $this->getFieldValue($field))." given.", "error", "user");
        return false;
    }

    /* check number of digits in string */
    protected function checkMindigits($field, $nb_digits_required){
        if(preg_match_all('/[0-9]/', $this->getFieldValue($field)) >= $nb_digits_required)
          return true;
        else if($this->loggerDefined())
          \Core\Logger::AddMessage("Field ".$field. " must contain at least".$nb_digits_required." digits.", "error", "user");
        return false;
    }

    /* check number of spaces in string */
    protected function checkMaxnbspaces($field, $nb_allowed_spaces){
      if(substr_count($this->getFieldValue($field), ' ') <= $nb_allowed_spaces)
        return true;
      else if($this->loggerDefined())
           \Core\Logger::AddMessage("Not more than ".$nb_allowed_spaces. "  spaces are allowed in ".$field.".", "error", "user");
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
                if(!$this->$method($date, $date_comp))
                  $valid = false;
              }
            }
            return $valid;
          }else{
            \Core\Logger::AddMessage("Invalid date format for field ".$field.".", "error", "user");
          }
        }else{
          \Core\Logger::AddMessage("Impossible to check date for field ".$field.", format not defined.", "error", "dev");
        }
        return false;
    }

    //check if date is before a reference date
    protected function checkDateBefore($date_field, $date_comp){
      if($date_field < $date_comp)
        return true;
      \Core\Logger::AddMessage($date_field->format('Y-m-d')." have to be before ".$date_comp->format('Y-m-d').".", "error", "user");
      return false;
    }

    //check if date is after a reference date
    protected function checkDateAfter($date_field, $date_comp){
      if($date_field > $date_comp)
        return true;
      \Core\Logger::AddMessage($date_field->format('Y-m-d')." have to be after ".$date_comp->format('Y-m-d').".", "error", "user");
      return false;
    }

    /* function to check img upload */
    /*public function checkImageUpload($field, $format){
      if(isset($_FILES[$field])){
        $target_dir = $format["target_dir"];
        $target_file = $target_dir . basename($_FILES[$field]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if(isset($format["target_file_name"]))
        $target_file = $target_dir . date("YmdHis-") . $format["target_file_name"].".".$imageFileType;

        $isImage = getimagesize($_FILES[$field]["tmp_name"]);
        if($isImage !== false) {
          if($_FILES[$field]["size"] <= $format["max_size"]){
            if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif"){
              \Core\FilesManager::createDirIfDoesNotExists($target_dir);
              \Core\FilesManager::deleteAllFromDir($target_dir);

              if (move_uploaded_file($_FILES[$field]["tmp_name"], $target_file))
                return $target_file;
              else
                \Core\Logger::AddMessage("Sorry, there was an error uploading your file.", "error", "user");
            }
            else
            \Core\Logger::AddMessage("Only png, jpg, jpeg and gif are allowed, the image given was regognised as \"".$imageFileType."\".", "error", "user");
          }
          else
          \Core\Logger::AddMessage("File is too large, max file size is ".$_FILES[$field]["size"]." and product size is ".$format["max_size"].".", "error", "user");
        }
        else
          \Core\Logger::AddMessage("File is not an image.", "error", "user");
      }else{
        \Core\Logger::AddMessage("Image not submitted.", "error", "user");
      }

      return false;
    }*/

    /* reset content of form */
    public function resetFormContent(){
        $this->_data = array();
    }

    /* SETTERS */
    public function setData($data){
        $this->_data = $data;
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

}

?>
