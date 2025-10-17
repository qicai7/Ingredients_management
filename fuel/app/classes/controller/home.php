<?php
class Controller_Home extends Controller
{
    // ホーム画面
    public function before()
    {
        parent::before();

        // logoutアクション以外はログイン必須
        $action = Request::active()->action;

        if ($action !== 'logout' && !Auth::check()) {
            Response::redirect('user/login');
        }
    }
    
    public function action_index()
    {
        // ログインチェック
        /*if (!Auth::check()) {
            Response::redirect('user/login');
        }*/

        // 自分のレシピだけ取得
        $user_id = Auth::get_user_id()[1]; // [1] はユーザーID
        $data['recipes'] = Model_Recipe::query()
            ->where('user_id', $user_id)
            ->get();

        // ビューを表示
        return View::forge('home/index', $data);
    }

    // ログアウト
    public function action_logout()
    {
        Auth::logout();
        Response::redirect('user/login');
    }
}
