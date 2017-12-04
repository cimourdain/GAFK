<?php

namespace App\Controllers;

class MainController extends AppController{

  /* Execute Index page */
  protected function executeIndex($params = null){
    \Core\Template::setStatic("title", "Home page");
    \Core\Template::setStatic("content", \Core\Template::render("pages/main/home.html"));
  }

  /* Execute Index page */
  protected function executePage2($params = null){
    \Core\Template::setStatic("title", "Second page");
    \Core\Template::setStatic("param_val", $this->_params[0]);
    \Core\Template::setStatic("content", \Core\Template::render("pages/main/page2.html"));
  }

  /* Execute form page */
  protected function executeFormtest($params = null){
    $fv = new \Core\FormValidator($this->_logger);

    $fields_format = ["test_form_min" => [ "min" => 3 ],
                      "test_form_max" => [ "max" => 4 ],
                      "test_form_min_max" => [  "min" => 3, "max" => 6 ],
                      ];
    if(!$fv->formSubmitted())
      $this->addMessage("Form not sumbitted", "info", "user");

    if($fv->formSubmitted() && $fv->checkFieldsFormat($fields_format))
      $this->addMessage("Form content ok", "success", "user");

    \Core\Template::setStatic("title", "Form test");
    //send logger messages to view
    \Core\Template::setStatic("messages", $this->getMessages());
    \Core\Template::setStatic("messages_html", \Core\Template::render("partials/messages.html"));

    //set fields content
    \Core\Template::setStatic("test_form_min", $fv->getFieldValueSecure("test_form_min", ""));
    \Core\Template::setStatic("test_form_max", $fv->getFieldValueSecure("test_form_max", ""));
    \Core\Template::setStatic("test_form_min_max", $fv->getFieldValueSecure("test_form_min_max", ""));

    //render in template
    \Core\Template::setStatic("content", \Core\Template::render("pages/main/form_test.html"));
  }

}

?>
