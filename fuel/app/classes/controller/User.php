<?php
class Controller_User extends Controller
{
    // 新規登録
    public function action_register()
    {
        $error = '';

        if (Input::post()) {
            $username = trim(Input::post('username'));
            $password = Input::post('password');

            // 入力不足チェック
            if ($username === '' || $password === '') {
                $error = '入力情報が不足しています';
            } else {
                // パスワードをハッシュ化
                $hashed_password = Auth::instance()->hash_password($password);

                // users テーブルに追加
                \DB::insert('users')->set([
                    'username'   => $username,
                    'password'   => $hashed_password,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ])->execute();

                // 登録成功したらログイン画面へ
                Response::redirect('user/login');
            }
        }

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
