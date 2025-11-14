<?php
class Controller_User extends Controller
{
    // 新規登録 (ビュー表示専用)
    public function action_register()
    {
        // $error 変数は、ビューに渡すために初期値として残します
        $error = ''; 

        // 従来のフォーム送信処理（Input::post() ブロック）は削除します
        
        // ビューを表示
        return Response::forge(View::forge('user/register', ['error' => $error]));
    }


    // ログイン
    public function action_login()
    {
        $error = '';

        if (Input::post()) {
            $username = trim(Input::post('username'));
            $password = trim(Input::post('password'));

            if (!Auth::login($username, $password)) {
                $error = 'ユーザー名もしくはパスワードが間違っています';
            } else {
                Response::redirect('home/index');
            }
        }

        return View::forge('user/login', ['error' => $error]);
    }
}
