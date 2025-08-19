<?php

class Controller_User extends Controller
{
    public function action_login()
    {
        $error_message = null;

        if (Input::post()) {
            $username = Input::post('username');
            $password = Input::post('password');

            if (Auth::check()) {
                Auth::logout(); // 念のためログアウト
            }

            if (Auth::instance()->login($username, $password)) {
                // ログイン成功 → welcome/index にリダイレクト
                Response::redirect('welcome/index');
            } else {
                // ログイン失敗
                $error_message = 'ユーザー名かパスワードが間違っています';
            }
        }

        // View に変数を渡す
        $data = array('error_message' => $error_message ?? '');
        return Response::forge(View::forge('user/login', $data));
    }
}


