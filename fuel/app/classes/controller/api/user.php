<?php
// app/classes/controller/api/user.php

//namespace Controller\Api;

// RESTfulなAPIとしてJSON応答を行うため、Controller_Restを継承
class Controller_Api_User extends \Controller_Rest
{
    // POST /api/user/register のリクエストを処理
    // Knockout.jsのAJAXリクエストの送信先です
    public function post_register()
    {
        // 1. 【Controllerの役割】POSTデータ取得と基本的な整形
        $username = trim(\Input::post('username'));
        $password = \Input::post('password');
        $email    = trim(\Input::post('username')); // registration.jsでemailフィールドを定義している前提

        // 2. サーバーサイドでの必須データチェック (Controllerで実施)
        if (empty($username) || empty($password) || empty($email)) {
             return $this->response([
                'success' => false,
                'message' => 'ユーザー名、パスワード、メールアドレスは必須です。'
            ], 400); // Bad Request
        }

        try {
            // 3. 重複チェック (Modelに依頼)
            if (\Model_User::exists_by_username($username)) {
                 return $this->response([
                    'success' => false,
                    'message' => 'そのユーザー名は既に使用されています。'
                ], 409); // Conflict
            }

            // 4. 【Controllerの役割】パスワードのハッシュ化（データの整形）
            // Modelに渡す前にAuthクラスで処理します
            $hashed_password = \Auth::instance()->hash_password($password);

            // 5. データの準備（Modelに渡す整形済みデータ）
            $data_to_save = [
                'username' => $username,
                'password' => $hashed_password, // ハッシュ済み
                'email'    => $email,
            ];

            // 6. データの保存 (Modelに依頼)
            if (!\Model_User::create_user($data_to_save)) {
                 throw new \Exception("Database insertion failed.");
            }

            // 7. 成功レスポンス（JSON形式で返す）
            return $this->response([
                'success' => true,
                'message' => 'ユーザー登録が完了しました。'
            ], 201); // Created

        } catch (\Exception $e) {
            // データベースエラーやその他の予期せぬ例外処理
            \Log::error('API registration error: ' . $e->getMessage());
            return $this->response([
                'success' => false,
                'message' => '登録中に予期せぬサーバーエラーが発生しました。'
            ], 500); // Internal Server Error
        }
    }
}
