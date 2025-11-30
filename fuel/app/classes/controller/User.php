<?php
class Controller_User extends Controller
{
    // 新規登録フォーム表示 (GETリクエスト専用)
    public function action_register()
    {
        // 1. CSRFトークンの生成とセッションへの保存
        // フォーム表示前に新しいトークンを確実に生成します。
        $csrf_token = \Fuel\Core\Str::random('alnum', 32); 
        \Fuel\Core\Session::set('csrf_token', $csrf_token);
        
        $error = ''; 
        
        // ビューを表示　(user/register.php)
        return \Fuel\Core\Response::forge(\Fuel\Core\View::forge('user/register', [
            'error' => $error,
            // 2. Viewにトークンを渡す
            'csrf_token' => $csrf_token,
        ]));
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
