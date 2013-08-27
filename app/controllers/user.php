<?php
namespace app\controllers;

use mako\Database;
use mako\View;
use mako\Validate;
use mako\Session;
use mako\security\Password;
use \mako\ReCaptcha;

class User extends \mako\Controller
{
	public function action_index() {
        if (Session::get('isLogin', false)) {
            return new View("user.index");
        } else {
            $this->response->redirect('user/login');
        }

    }

    public function action_login() {
        if($this->request->method() == 'GET') { // this is for accessing from menu, not from form
            $data = array('errors' => "", 'page' =>'login');
            return new View("user.login", $data);
        }

        $rules = array (
            'username' => 'required',
            'password' => 'required'
        );
        $validation = new Validate($_POST, $rules);
        if($validation->successful()) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $row = Database::first( "select * from users where username = ? ", array($username) );

            if ( ! $row ) {
                $data = array('errors' => "Incorrect username or password", 'page' =>'login');
            } elseif (Password::validate($password, $row->password)) {
                Session::regenerate();
                Session::remember( "isLogin", true );
                Session::remember( "username", $username );
                Session::remember( "userid", $row->id );
                return new View("user.index");
            } else {
                $data = array('errors' => "Incorrect username or password", 'page' =>'login');
            }
            return new View("user.login", $data);
        }
        else {
            $errors = implode( "<br/>", array_values($validation->errors()) );
            $data = array('errors' => $errors, 'page' =>'login');

            return new View("user.login", $data);
        }
    }

    public function action_logout()
    {
        Session::destroy();
        Session::regenerate();
        $this->response->redirect('user/login');
    }

    public function action_register() {
        $recaptcha = new ReCaptcha();
        $rules = array (
            'username' => 'required|min_length:4|max_length:20',
            'password' => 'required|min_length:8',
            'email'    => 'required|email',
        );

        $validation = new Validate($_POST, $rules);
        if($validation->successful()) {
            $username = $_POST['username'];
            $password = Password::hash( $_POST['password'] );
            $email = $_POST['email'];
            $isuserExist = Database::column( "select count(*) from users where username = ? ", array($username) ) > 0 ;
            $isemailExist = Database::column( "select count(*) from users where email = ? ", array($email) ) > 0 ;

            if ($isuserExist) {
                $data = array('errors' => "The username has already been registered", 'page' =>'register');
            } elseif ($isemailExist) {
                $data = array('errors' => "The email has already been registered", 'page' =>'register');
            } elseif ( $recaptcha->validate() && $recaptcha->failed() ) {
                $data = array('errors' => "The ReCaptcha inputs are incorrect", 'page' =>'register');
            }
            else {
                Database::query( "insert into users (username, password, email) values(?,?,?)", array($username,$password,$email) );
                $data = array('errors' => "", 'page' =>'login');
            }
            return new View("user.login", $data);
        }
        else {
            $errors = implode( "<br/>", array_values($validation->errors()) );
            $data = array('errors' => $errors, 'page' =>'register');

            return new View("user.login", $data);
        }

    }

}
