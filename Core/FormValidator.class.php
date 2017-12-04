<?php

/*************************
TO-DO detail file format
*************************/

namespace Core;

class FormValidator{
    use TLoggedClass;

    protected $_data = array();

    public function __construct($logger){
        $this->setData($_POST);
        $this->setLogger($logger);
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
                        $this->AddMessage("Field ".$f." not received.");
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
        if($this->fieldSubmitted($field) && $this->getFieldValue($field) != null){
          foreach($format as $type => $value){
            $check_method = "check".ucfirst(strtolower($type));
            if(method_exists($this, $check_method)){
              //$this->AddMessage("Check ".$type." on field ".$field.".", "info", "dev");
              return $this->$check_method($field, $value);
            }
            else
              $this->AddMessage("Cannot check ".$type." on field ".$field.".", "error", "dev");
          }
        }
        else
            $this->AddMessage("Field ".$field." empty or impossible to check.", "error", "user");

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
    protected function checkMin($field, $min){
        if(strlen($this->getFieldValue($field)) >= $min )
            return true;
        else if($this->loggerDefined())
            $this->AddMessage("Field ".$field. " must have a min length of ".$min." characters.", "error", "user");
        return false;
    }

    /* function to check max size of field */
    protected function checkMax($field, $max){
        if(strlen($this->getFieldValue($field)) <= $max )
            return true;
        else if($this->loggerDefined())
            $this->AddMessage("Field ".$field. " must have a max length of ".$max." characters.","error", "user");

        return false;
    }

    /******************************************
    ************  CHECK FIELD TYPE ***********
    ******************************************/
    /* function to check that field value has an email format */
    protected function checkEmail($field, $value = true){
        if(filter_var($this->getFieldValue($field), FILTER_VALIDATE_EMAIL))
            return true;
        else if($this->loggerDefined())
            $this->AddMessage("Invalid email format for field ".$field, "error", "user");
        return false;
    }

    /* function to check that 2 fields are identicals */
    protected function checkIdentical($field, $other_field_name){
        if($this->fieldSubmitted($other_field_name) && $this->getFieldValue($field) ==  $this->getFieldValue($other_field_name))
            return true;
        else if($this->loggerDefined())
          $this->AddMessage("Field ".$field. "  must be identical to ".$other_field_name.".", "error", "user");
        return false;
    }

    /* check if value is integer */
    protected function checkInt($field, $value = true){
        if(is_numeric($this->getFieldValue($field)) && is_int(intval($this->getFieldValue($field))))
          return true;
        else if($this->loggerDefined())
          $this->addMessage("Field ".$field." (".$this->getFieldValue($field).") is not an integer.", "error", "user");
      return false;
    }

    /******************************************
    ************  CHECK DATE FUNCTIONS *******
    ******************************************/

    function isValidDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    //function to check date format
    function checkDate($field, $format)
    {
        if(isset($format["format"])){
          if(isValidDate($this->getFieldValue($field), $format["format"])){
            //get date
            $date = DateTime::createFromFormat($this->getFieldValue($field), $format["format"]);
            //remove format from format
            unset($format["format"]);

            //parse all checks on date
            $valid = true;
            foreach($format as $check => $value){
              $method = "checkDate".ucfirst(strtolower($check));
              if(!method_exists($method, $this)){
                  $this->addMessage("Impossible to check ".$check." on date.", "error", "dev");
                  $valid = false;
              }else if (!isValidDate($value)){
                $this->addMessage("Impossible to check date for field ".$field.", format not defined.", "error", "dev");
              }
              else{
                $date2 = DateTime::createFromFormat($value, 'Y-m-d');
                if(!$method($date, $date2))
                  $valid = false;
              }
            }
            return $valid;
          }else{
            $this->addMessage("Invalid date format for field ".$field.".", "error", "user");
          }
        }else{
          $this->addMessage("Impossible to check date for field ".$field.", format not defined.", "error", "dev");
        }
        return false;
    }

    //check if date is before a reference date
    protected function checkDateBefore($date_field, $date_comp){
      if($date_field > $date_comp)
        return true;
      $this->addMessage("Date have to be before ".$date_comp->format('Y-m-d').".", "error", "user");
      return false;
    }

    //check if date is after a reference date
    protected function checkDateAfter($date_field, $date_comp){
      if($date_field < $date_comp)
        return true;
      $this->addMessage("Date have to be before ".$date_comp->format('Y-m-d').".", "error", "user");
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
                $this->addMessage("Sorry, there was an error uploading your file.", "error", "user");
            }
            else
            $this->addMessage("Only png, jpg, jpeg and gif are allowed, the image given was regognised as \"".$imageFileType."\".", "error", "user");
          }
          else
          $this->addMessage("File is too large, max file size is ".$_FILES[$field]["size"]." and product size is ".$format["max_size"].".", "error", "user");
        }
        else
          $this->addMessage("File is not an image.", "error", "user");
      }else{
        $this->addMessage("Image not submitted.", "error", "user");
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
