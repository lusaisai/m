<?php
namespace app\controllers;

use mako\Config;
use mako\Database;
use mako\View;
use mako\Validate;
use mako\Session;
use mako\security\Password;
use mako\I18n;

class User extends \mako\Controller
{
	public function action_index() {
        $data = $this->userInfo() + $this->getPlaylists() + array('errors' => "", 'successes' => "");
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
            return array( 'id' => $row->id, 'name' => $row->username, 'email' => $row->email, 'role' => $row->role );
        } else {
            return array( 'id' => '-1', 'name' => '', 'email' => '', 'role' => '' );
        }

    }

    public function action_playlistdetail($id=0)
    {
        $this->checkLogin();
        $user = $this->userInfo();
        $songids = Database::column( "select song_ids from playlists where id = ? and user_id = ?", array( $id, $user['id'] ) );
        if (! $songids) {
            return;
        }

        $query = "select
                  s.id, s.name as song_name, ar.name as artist_name, al.name as album_name
                  from song s
                  join album al
                  on   s.album_id = al.id
                  join artist ar
                  on   al.artist_id = ar.id
                  where s.id in ( $songids ) 
            ";
        $songs = Database::all( $query );    
        return new View( 'user/playlistdetail', array( 'songs' => $songs ) );

    }

    public function action_showadmin()
    {
        $this->checkLogin();
        return new View('user.admin');
    }

    public function action_showplaylist( $pageid = 1 )
    {
        $this->checkLogin();
        return new View( 'user.playlist', $this->getPlaylists($pageid) );
    }

    private function getPlaylists( $pageid = 1 )
    {
        $this->checkLogin();
        $user = $this->userInfo();
        $limit = 9;
        $offset = ($pageid - 1) * $limit;

        $query = "select count(*) from playlists where user_id = ?";
        $count = Database::column( $query, array( $user['id'] ) );
        $query = "select id, name, song_ids from playlists where user_id = ? limit ? offset ?";
        $playlists = Database::query( $query, array( $user['id'], $limit, $offset ) );

        return array( 'pageid'=>$pageid, 'count'=>$count, 'limit'=>$limit, 'data'=>$playlists );
    }

    public function action_saveplaylist($songids, $playlistName, $playlistid = 0)
    {
        $this->checkLogin();
        $user = $this->userInfo();
        $query = "select count(*) from playlists where id = ? and user_id = ?";
        $isPlaylistExist = Database::column( $query, array($playlistid, $user['id']) );
        if ($isPlaylistExist) {
            $query = "update playlists set song_ids = ?, name = ? where id = ?";
            Database::query( $query, array( $songids, $playlistName, $playlistid ) );
        } else {
            $query = "insert into playlists
            ( name, user_id, song_ids ) values ( ?,?,? )
            ";
            Database::query( $query, array( $playlistName, $user['id'], $songids ) );
        }
    }

    public function action_deleteplaylist($id=0)
    {
        $this->checkLogin();
        $user = $this->userInfo();
        $query = "delete from playlists where id = ? and user_id = ?";
        Database::query( $query, array( $id, $user['id'] ) );
    }

    public function action_updateinfo()
    {
        $this->checkLogin();
        $rules = array (
            'oldpassword' => 'required|min_length:8',
            'newpassword' => 'required|min_length:8',
        );

        $data = $this->userInfo() + array('errors' => "", 'successes' => "");
        if ($this->request->method() == 'GET') {
           return new View("user.updateinfo", $data);
        }

        $validation = new Validate($_POST, $rules);
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
            $messages = I18n::get('validate');
            $errors = '';
            foreach ($validation->errors() as $field => $rule) {
                $errors .= sprintf( $messages[$rule], $field );
                $errors .= "<br/>";
            }
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
                Session::remember( "role", $row->role );
                $data = $data + $this->userInfo() + $this->getPlaylists();
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
        $rules = array (
            'username' => 'required|unique:users,username|min_length:4|max_length:20',
            'password' => 'required|min_length:8',
            'email'    => 'required|unique:users,email|email',
        );

        $validation = new Validate($_POST, $rules);
        if($validation->successful()) {
            $username = $_POST['username'];
            $password = Password::hash( $_POST['password'] );
            $email = $_POST['email'];

            Database::query( "insert into users (username, password, email) values(?,?,?)", array($username,$password,$email) );
            $data = array('errors' => "", 'page' =>'login');
            
            return new View("user.login", $data);
        }
        else {
            $messages = I18n::get('validate');
            $errors = '';
            foreach ($validation->errors() as $field => $rule) {
                $errors .= sprintf( $messages[$rule], $field );
                $errors .= "<br/>";
            }
            
            $data = array('errors' => $errors, 'page' =>'register');

            return new View("user.login", $data);
        }

    }

}
