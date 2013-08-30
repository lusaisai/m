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
        $data = $this->userInfo() + array('errors' => "", 'successes' => "");
        if ($this->checkLogin()) {
            return new View("user.index", $data);
        }
    }

    public function checkLogin()
    {
        if ( Session::get('isLogin', false)) {
            return true;
        } else {
            $this->response->redirect('user/login');
        }
    }

    private function userInfo()
    {
        $row = Database::first( "select * from users where id = ? ", array( Session::get('userid', -1) ) );
        if ($row) {
            return array( 'id' => $row->id, 'name' => $row->username, 'email' => $row->email );
        } else {
            return array( 'id' => '-1', 'name' => '', 'email' => '' );
        }

    }

    public function action_saveplaylist($songids, $playlistName, $playlistid = 0)
    {
        $this->checkLogin();
        $user = $this->userInfo();
        $query = "select count(*) from playlists where id = ?";
        $isPlaylistExist = Database::column( $query, array($playlistid) );
        if ($isPlaylistExist) {
            $query = "update playlists set song_ids = ? where id = ?";
            Database::query( $query, array( $songids, $playlistid ) );
        } else {
            $query = "insert into playlists
            ( name, user_id, song_ids ) values ( ?,?,? )
            ";
            Database::query( $query, array( $playlistName, $user['id'], $songids ) );
        }
    }

    public function action_updateinfo()
    {
        $this->checkLogin();
        $rules = array (
            'oldpassword' => 'required|min_length:8',
            'newpassword' => 'required|min_length:8',
        );

        $validation = new Validate($_POST, $rules);
        $data = $this->userInfo() + array('errors' => "", 'successes' => "");
        if($validation->successful()) {
            $oldpassword = $_POST['oldpassword'];
            $newpassword = Password::hash( $_POST['newpassword'] );
            $newpasswordrpt = $_POST['newpasswordrpt'];
            $row = Database::first( "select * from users where id = ? ", array($data['id']) );

            if ( ! Password::validate($oldpassword, $row->password) ) {
                $data['errors'] = "The old password is incorrect";
            } elseif ( ! Password::validate($newpasswordrpt, $newpassword) ) {
                $data['errors'] = "Your repeated password is different";
            }
            else {
                $query = "update users
                set password = ?,
                update_ts = current_timestamp
                where id = ?
                ";
                Database::query( $query, array($newpassword,$data['id']) );
                $data['successes'] = "Your password has been updated";
            }
        }
        else {
            $errors = implode( "<br/>", array_values($validation->errors()) );
            $data['errors'] = $errors;
        }
        return new View("user.updateinfo", $data);
    }

    public function action_login() {
        $data = array('errors' => "", 'successes' => "", 'page' =>'login');

        if($this->request->method() == 'GET') { // this is for accessing from menu, not from form
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
                $data['errors'] = "Incorrect username or password";
            } elseif (Password::validate($password, $row->password)) {
                Session::regenerate();
                Session::remember( "isLogin", true );
                Session::remember( "username", $username );
                Session::remember( "userid", $row->id );
                $data += $this->userInfo();
                return new View("user.index", $data);
            } else {
                $data['errors'] = "Incorrect username or password";
            }
            return new View("user.login", $data);
        }
        else {
            $errors = implode( "<br/>", array_values($validation->errors()) );
            $data['errors'] = $errors;

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
