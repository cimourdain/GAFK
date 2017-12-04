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
    $post_fv = new \Core\FormValidator();
    $files_fv = new \Core\FormValidator();
    $files_fv->setData($_FILES);

    $fields_post_format = ["test_form_min" => [ "min" => 3 ],
                      "test_form_max" => [ "max" => 4 ],
                      "test_form_min_max" => [  "min" => 3, "max" => 6 ],
                      "test_optionnal" => [  "optionnal" => true, "min" => 3 ],
                      "test_exact_size" => [ "size" => 2],
                      "test_optionnalif" => [ "optionnalif" => ["test_exact_size" => ["US", "FR"]], "size" => 5],
                      "test_alpha" => ["alpha" => true],
                      "test_alphanum" => ["alphanum" => true],
                      "test_numeric" => ["numeric" => true],
                      "test_int" => ["int" => true],
                      "test_secure" => ["minuppercases" => 2, "mindigits" => 3,  "Maxnbspaces" => 0],
                      "test_date" => ["date" => ["format" => "Y/m/d"]],
                      "test_daterange" => ["date" => ["format" => "Y-m-d", "after" => "2016-07-15", "before" => "2017-11-15"]],
                      "test_hexcolor" => ["hexcolor" => true, "user_message" => "wrong hexa color"],
                      "test_inlist" => ["inlist" => ["blue", "red"]],
                      "test_password" => ["min" => 3, "max" => 6 ],
                      "test_password_conf" => ["identical"=>"test_password"]
                      ];
    $fields_files_format = ["test_image" => ["upload" => ["target_dir" => "img",
                                                          "max_size" => 50000,
                                                          "target_file_name" => "toto",
                                                          "allowed_extensions" => ["png"],
                                                          "date_prefix" => true]]];

    if($post_fv->formSubmitted() && $post_fv->checkFieldsFormat($fields_post_format) && $files_fv->checkFieldsFormat($fields_files_format)){
      \Core\Logger::AddMessage("post & files data ok", "success", "user");
    }
    else
      \Core\Logger::AddMessage("please check fields");

    \Core\Template::setStatic("title", "Form test");
    //send logger messages to view
    \Core\Template::setStatic("messages", \Core\Logger::getMessages());
    \Core\Template::setStatic("messages_html", \Core\Template::render("partials/messages.html"));

    //set fields content
    \Core\Template::setStatic("test_form_min", $post_fv->getFieldValueSecure("test_form_min", ""));
    \Core\Template::setStatic("test_form_max", $post_fv->getFieldValueSecure("test_form_max", ""));
    \Core\Template::setStatic("test_form_min_max", $post_fv->getFieldValueSecure("test_form_min_max", ""));
    \Core\Template::setStatic("test_optionnal", $post_fv->getFieldValueSecure("test_optionnal", ""));
    \Core\Template::setStatic("test_exact_size", $post_fv->getFieldValueSecure("test_exact_size", ""));
    \Core\Template::setStatic("test_optionnalif", $post_fv->getFieldValueSecure("test_optionnalif", ""));
    \Core\Template::setStatic("test_alpha", $post_fv->getFieldValueSecure("test_alpha", ""));
    \Core\Template::setStatic("test_alphanum", $post_fv->getFieldValueSecure("test_alphanum", ""));
    \Core\Template::setStatic("test_numeric", $post_fv->getFieldValueSecure("test_numeric", ""));
    \Core\Template::setStatic("test_int", $post_fv->getFieldValueSecure("test_int", ""));
    \Core\Template::setStatic("test_secure", $post_fv->getFieldValueSecure("test_secure", ""));
    \Core\Template::setStatic("test_date", $post_fv->getFieldValueSecure("test_date", ""));
    \Core\Template::setStatic("test_daterange", $post_fv->getFieldValueSecure("test_daterange", ""));
    \Core\Template::setStatic("test_hexcolor", $post_fv->getFieldValueSecure("test_hexcolor", ""));
    \Core\Template::setStatic("test_hexcolor", $post_fv->getFieldValueSecure("test_hexcolor", ""));
    \Core\Template::setStatic("test_inlist", $post_fv->getFieldValueSecure("test_inlist", ""));
    \Core\Template::setStatic("test_password", $post_fv->getFieldValueSecure("test_password", ""));
    \Core\Template::setStatic("test_password_conf", $post_fv->getFieldValueSecure("test_password_conf", ""));

    //render in template
    \Core\Template::setStatic("content", \Core\Template::render("pages/main/form_test.html"));
  }

}

?>
