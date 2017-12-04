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
    $fv = new \Core\FormValidator();

    $fields_format = ["test_form_min" => [ "min" => 3 ],
                      "test_form_max" => [ "max" => 4 ],
                      "test_form_min_max" => [  "min" => 3, "max" => 6 ],
                      "test_optionnal" => [  "optionnal" => true, "min" => 3 ],
                      "test_exact_size" => [ "size" => 2],
                      "test_optionnalif" => [ "optionnalif" => ["test_exact_size" => ["US"]], "size" => 5],
                      "test_alpha" => ["alpha" => true],
                      "test_alphanum" => ["alphanum" => true],
                      "test_numeric" => ["numeric" => true],
                      "test_int" => ["int" => true],
                      "test_secure" => ["minuppercases" => 2, "mindigits" => 3,  "Maxnbspaces" => 0],
                      "test_date" => ["date" => ["format" => "Y/m/d"]],
                      "test_daterange" => ["date" => ["format" => "Y-m-d", "after" => "2016-07-15", "before" => "2017-11-15"]]
                      ];
    if(!$fv->formSubmitted())
      \Core\Logger::AddMessage("Form not sumbitted", "info", "user");

    if($fv->formSubmitted() && $fv->checkFieldsFormat($fields_format))
      \Core\Logger::AddMessage("Form content ok", "success", "user");

    \Core\Template::setStatic("title", "Form test");
    //send logger messages to view
    \Core\Template::setStatic("messages", \Core\Logger::getMessages());
    \Core\Template::setStatic("messages_html", \Core\Template::render("partials/messages.html"));

    //set fields content
    \Core\Template::setStatic("test_form_min", $fv->getFieldValueSecure("test_form_min", ""));
    \Core\Template::setStatic("test_form_max", $fv->getFieldValueSecure("test_form_max", ""));
    \Core\Template::setStatic("test_form_min_max", $fv->getFieldValueSecure("test_form_min_max", ""));
    \Core\Template::setStatic("test_optionnal", $fv->getFieldValueSecure("test_optionnal", ""));
    \Core\Template::setStatic("test_exact_size", $fv->getFieldValueSecure("test_exact_size", ""));
    \Core\Template::setStatic("test_optionnalif", $fv->getFieldValueSecure("test_optionnalif", ""));
    \Core\Template::setStatic("test_alpha", $fv->getFieldValueSecure("test_alpha", ""));
    \Core\Template::setStatic("test_alphanum", $fv->getFieldValueSecure("test_alphanum", ""));
    \Core\Template::setStatic("test_numeric", $fv->getFieldValueSecure("test_numeric", ""));
    \Core\Template::setStatic("test_int", $fv->getFieldValueSecure("test_int", ""));
    \Core\Template::setStatic("test_secure", $fv->getFieldValueSecure("test_secure", ""));
    \Core\Template::setStatic("test_date", $fv->getFieldValueSecure("test_date", ""));
    \Core\Template::setStatic("test_daterange", $fv->getFieldValueSecure("test_daterange", ""));

    //render in template
    \Core\Template::setStatic("content", \Core\Template::render("pages/main/form_test.html"));
  }

}

?>
