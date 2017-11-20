<?php

/*************************
TO-DO detail file format
*************************/

namespace core;

class FormValidator{

    protected $_post = array();
    protected $_files = array();
    protected $_logger;

    public function __construct($logger){
        $this->setPostData($_POST);
        $this->setFilesData($_FILES);
        $this->setLogger($logger);
    }

    /* function to check if POST data where submitted */
    public function formSubmitted($fields = array()){
        if(!empty($this->_post)){
            if(empty($fields))
                return true;
            else{
                $all_fields_received = true;
                foreach($fields as $f){
                    if(!$this->fieldSubmitted($f)){
                        $this->_logger->AddMessage("Field ".$f." not received.");
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
        if(isset($this->_post[$field]) || isset($this->_files[$field]))
            return true;
        else
            return false;
    }

    /* function to check field format (see header for fofmat detail) */
    public function checkFieldFormat($field, $format){
        if($this->fieldSubmitted($field) && $this->getFieldValue($field) != null && isset($format["type"]) && $format["type"] != "checkbox_value"){
            switch($format["type"]){
            case "text":
                return $this->checkFieldSize($field, $format);
                break;
            case "email":
                return $this->checkFieldIsEmail($field);
                break;
            case "login_or_email":
                return $this->checkFieldIsTextOrMail($field, $format);
                break;
            case "identical":
                return $this->checkFieldsIdenticals($field, $format);
                break;
            case "image":
                return $this->checkImageUpload($field, $format);
                break;
            case "integer":
                return $this->checkIsInteger($field, $format);
                break;
            case "numeric":
                return $this->checkIsNumeric($field, $format);
                break;
            default:
                $this->_logger->AddMessage("Field ".$field.": format ".$format["type"]." cannot be checked.");
                return false;
            }
        }
        else if($format["type"] == "checkbox_value"){
          //in exception because checkbox $field not sumbmitted if nothing checked
          return $this->checkValueChecked($field, $format);
        }
        else
            $this->_logger->AddMessage("Field ".$field." empty or impossible to check.");
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

    /* function to check field size based on format min/max */
    protected function checkFieldSize($field, $format, $log = true){
        $l = strlen($this->getFieldValue($field));
        if((!isset($format["min"]) || $l >= $format["min"]) && (!isset($format["max"]) || $l <= $format["max"]))
            return true;
        else if($log)
            $this->_logger->AddMessage("Field ".$field. " must have between ".$format["min"]." and ".$format["max"]." characters.");

        return false;
    }

    /* function to check that field value has an email format */
    protected function checkFieldIsEmail($field, $log = true){
        if(filter_var($this->getFieldValue($field), FILTER_VALIDATE_EMAIL))
            return true;

        if($log)
            $this->_logger->AddMessage("Invalid email format for field ".$field);
        return false;
    }

    /* function to check that field is a text or an email */
    protected function checkFieldIsTextOrMail($field, $format){
        if($this->checkFieldSize($field, $format, false) || $this->checkFieldIsEmail($field, false))
            return true;
        $this->_logger->addMessage("Field ".$field." have to be an email or a text.");
        return false;
    }

    /* function to check that 2 fields are identicals */
    protected function checkFieldsIdenticals($field, $format){
        if(isset($format["field"]) && $this->fieldSubmitted($format["field"]) && $this->getFieldValue($field) ==  $this->getFieldValue($format["field"]))
            return true;

        $this->_logger->AddMessage("Field ".$field. "  must be identical to ".$format["field"]);
        return false;
    }

    /* function to check that a value is in array */
    protected function checkValueChecked($field, $format){
      if(!$format["mandatory"] && (!$this->fieldSubmitted($field) || ($this->fieldSubmitted($field) && in_array($this->getFieldValue($field), $format["allowed_values"]))))
        return true;
      else if ($format["mandatory"] && $this->fieldSubmitted($field) && in_array($this->getFieldValue($field), $format["allowed_values"]))
        return true;
      return false;
    }

    protected function checkIsInteger($field, $format){
      if($this->fieldSubmitted($field)){
        if(is_numeric($this->getFieldValue($field)) && is_int(intval($this->getFieldValue($field))))
          return true;
        else if(isset($format["allowed_exceptions"]) && in_array($this->getFieldValue($field), $format["allowed_exceptions"]))
          return true;
      }

      $this->_logger->addMessage("Field ".$field." (".$this->getFieldValue($field).")is not an integer.", "error", "dev");
      return false;

    }

    protected function checkIsNumeric($field, $format){
      if($this->fieldSubmitted($field)){
        if(is_numeric($this->getFieldValue($field)) )
          return true;
      }

      $this->_logger->addMessage("Field ".$field." is not a number.", "error", "dev");
      return false;

    }
    /* function to check img upload */
    public function checkImageUpload($field, $format){
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
              \core\FilesManager::createDirIfDoesNotExists($target_dir);
              \core\FilesManager::deleteAllFromDir($target_dir);

              if (move_uploaded_file($_FILES[$field]["tmp_name"], $target_file))
                return $target_file;
              else
                $this->_logger->addMessage("Sorry, there was an error uploading your file.", "error", "user");
            }
            else
            $this->_logger->addMessage("Only png, jpg, jpeg and gif are allowed, the image given was regognised as \"".$imageFileType."\".", "error", "user");
          }
          else
          $this->_logger->addMessage("File is too large, max file size is ".$_FILES[$field]["size"]." and product size is ".$format["max_size"].".", "error", "user");
        }
        else
          $this->_logger->addMessage("File is not an image.", "error", "user");
      }else{
        $this->_logger->addMessage("Image not submitted.", "error", "user");
      }

      return false;
    }

    public function resetFormContent(){
        $this->_post = array();
        $_POST = array();
        $_FILES = array();
    }

    /* SETTERS */
    protected function setPostData($p){
        $this->_post = $p;
    }

    protected function setFilesData($f){
        $this->_files = $f;
    }

    protected function setLogger($l){
        $this->_logger = $l;
    }

    /* GETTERS */
    protected function getFieldValue($f, $default = null){
        if($this->fieldSubmitted($f)){
            if(isset($this->_post[$f]))
              return $this->_post[$f];
            else if(isset($this->_files[$f]))
                return $this->_files[$f];
          }

        return $default;
    }

    public function getFieldValueSecure($f, $default=null){
      if(is_string($this->getFieldValue($f, $default)))
        return htmlentities($this->getFieldValue($f, $default));
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
