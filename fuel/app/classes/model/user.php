<?php
// app/classes/model/user.php

class Model_User
{
    /**
     * 指定されたユーザー名が既に存在するかチェックする
     * @param string $username
     * @return bool 存在するならtrue
     */
    public static function exists_by_username($username)
    {
        // DB::* を使った重複チェックロジックをModelに書く
        $count = \DB::select(\DB::expr('COUNT(*) as count'))
                    ->from('users')
                    ->where('username', $username)
                    ->execute()
                    ->get('count');
                    
        return $count > 0;
    }

    /**
     * ユーザーデータをDBに保存する
     * @param array $data 登録するデータ配列（username, password, emailなど）
     * @return bool 成功ならtrue
     */
    public static function create_user($data)
    {
        // DB::* を使った保存ロジックをModelに書く
        // 【注意】$data['password']は既にハッシュ化されている前提とする
        
        $result = \DB::insert('users')->set([
            'username'   => $data['username'],
            'password'   => $data['password'], // ハッシュ済み
            'email'      => $data['email'],
            // 'group'      => 1, // 適切なユーザーグループIDを指定
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ])->execute();
        
        // 成功した場合は、挿入されたIDの配列が返される
        return (bool) $result[1];
    }
}
