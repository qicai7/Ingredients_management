<?php
class Controller_Recipe extends Controller
{
    public function before()
    {
        parent::before();

        // logoutアクション以外はログイン必須
        $action = Request::active()->action;

        if ($action !== 'logout' && !Auth::check()) {
            Response::redirect('user/login');
        }
    }
    // レシピ追加画面
    public function action_add()
{
    /*if (!Auth::check()) {
        Response::redirect('user/login');
    }*/

    $error = '';
    $success = '';

    if (Input::post('name')) {
        $user_id = Auth::get_user_id()[1];

        // 材料名と分量の配列を取得
        $ingredients = Input::post('ingredients', []); // 例: ['玉ねぎ', 'にんじん']
        $amounts     = Input::post('amounts', []);     // 例: ['1個', '2本']

        foreach ($ingredients as $index => $ingredient_name) {
            if (trim($ingredient_name) === '' || trim($amounts[$index] ?? '') === '') {
                $error = '全てのフォームに入力してください';
                return View::forge('recipe/add', ['error' => $error, 'success' => '']);
            }
        }
        

        try {
            // recipes テーブルに追加
            $recipe_id = DB::insert('recipes')->set([
                'user_id'    => $user_id,
                'name'       => Input::post('name'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ])->execute()[0]; // 追加したレコードのIDを取得

         


            foreach ($ingredients as $index => $ingredient_name) {
                /*if (trim($ingredient_name) === '' || trim($amounts[$index] ?? '') === '') {
                    $error = '全てのフォームに入力してください';
                    return View::forge('recipe/add', ['error' => $error, 'success' => $success]);
                }*/

                //if (trim($ingredient_name) === '') continue; // 空はスキップ

                // ingredients テーブルに追加 or 既存のIDを取得
                $ingredient = DB::select()->from('ingredients')
                    ->where('name', trim($ingredient_name))
                    ->execute()
                    ->current();

             if ($ingredient) {
                $ingredient_id = $ingredient['id'];
             } else {
                $ingredient_id = DB::insert('ingredients')->set([
                    'name' => trim($ingredient_name),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ])->execute()[0];
               }

             // recipe_ingredients に紐付け
                DB::insert('recipe_ingredients')->set([
                    'recipe_id'     => $recipe_id,
                    'ingredient_id' => $ingredient_id,
                    'amount'        => trim($amounts[$index] ?? ''),
                ])->execute();
            }

            $success = 'レシピを追加しました';
            Response::redirect('home/index');
        } catch (Exception $e) {
            $error = 'レシピの追加に失敗しました';
        }
    }

    return View::forge('recipe/add', ['error' => $error, 'success' => $success]);
}
    // レシピ詳細画面
    public function action_view($id = null)
    {
        /*if (!Auth::check()) {
            Response::redirect('user/login');
        }*/

        if (!$id) {
            Response::redirect('home/index');
        }

    // レシピ取得（自分のものだけ）
        $user_id = Auth::get_user_id()[1];
        $recipe = Model_Recipe::query()
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->get_one();

        if (!$recipe) {
            Response::redirect('home/index');
        }

    // 材料と分量を取得
        $ingredients = DB::select('i.name', 'ri.amount')
            ->from(['recipe_ingredients', 'ri'])
            ->join(['ingredients', 'i'], 'INNER')
            ->on('ri.ingredient_id', '=', 'i.id')
            ->where('ri.recipe_id', $recipe->id)
            ->execute()
            ->as_array();

        return View::forge('recipe/view', [
            'recipe'      => $recipe,
            'ingredients' => $ingredients
        ]);
    }
    // レシピ編集画面
    public function action_edit($id = null)
    {
        /*if (!Auth::check()) {
            Response::redirect('user/login');
        }*/

        if (!$id) {
            Response::redirect('home/index');
        }

        $user_id = Auth::get_user_id()[1];

        // 編集対象のレシピを取得
        $recipe = Model_Recipe::query()
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->get_one();

        if (!$recipe) {
            Response::redirect('home/index');
        }

        // 材料と分量を取得
        $ingredients = DB::select(
                ['i.name', 'name'],           // カラム名とエイリアスを配列で指定
                ['ri.amount', 'amount'],
                ['i.id', 'ingredient_id'],
                ['ri.id', 'ri_id']
            )
            ->from(['recipe_ingredients', 'ri'])
            ->join(['ingredients', 'i'], 'INNER')
            ->on('ri.ingredient_id', '=', 'i.id')
            ->where('ri.recipe_id', $recipe->id)
            ->execute()
            ->as_array();


        $error = '';

        if (Input::post('name')) {
            try {
                // recipes テーブル更新
                DB::update('recipes')->set([
                    'name'       => Input::post('name'),
                    'updated_at' => date('Y-m-d H:i:s')
                ])->where('id', $recipe->id)->execute();

                // 一旦既存の材料紐付けを削除
                DB::delete('recipe_ingredients')->where('recipe_id', $recipe->id)->execute();

                // 新しい材料と分量を登録
                $ingredients_post = Input::post('ingredients', []);
                $amounts_post     = Input::post('amounts', []);

                foreach ($ingredients_post as $index => $ingredient_name) {
                    if (trim($ingredient_name) === '') continue;

                    // ingredients テーブルに追加 or 既存のID取得
                    $ingredient = DB::select()->from('ingredients')
                        ->where('name', trim($ingredient_name))
                        ->execute()
                        ->current();

                    $ingredient_id = $ingredient ? $ingredient['id'] : DB::insert('ingredients')->set([
                        'name'       => trim($ingredient_name),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ])->execute()[0];

                    DB::insert('recipe_ingredients')->set([
                        'recipe_id'     => $recipe->id,
                        'ingredient_id' => $ingredient_id,
                        'amount'        => trim($amounts_post[$index] ?? ''),
                    ])->execute();
                }

                Response::redirect('home/index');

            } catch (Exception $e) {
                $error = '編集に失敗しました';
            }
        }

        return View::forge('recipe/edit', [
            'recipe'      => $recipe,
            'ingredients' => $ingredients,
            'error'       => $error
        ]);
    }
    // レシピ削除確認画面
    public function action_confirm_delete($id = null)
    {
        /*if (!Auth::check()) {
            Response::redirect('user/login');
        }*/

        if (!$id) {
            Response::redirect('home/index');
        }

        $user_id = Auth::get_user_id()[1];

        // 削除対象のレシピを取得
        $recipe = Model_Recipe::query()
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->get_one();

        if (!$recipe) {
            Response::redirect('home/index');
        }

        // 材料と分量を取得
        $ingredients = DB::select('i.name', 'ri.amount')
            ->from(['recipe_ingredients', 'ri'])
            ->join(['ingredients', 'i'], 'INNER')
            ->on('ri.ingredient_id', '=', 'i.id')
            ->where('ri.recipe_id', $recipe->id)
            ->execute()
            ->as_array();

        return View::forge('recipe/delete', [
            'recipe'      => $recipe,
            'ingredients' => $ingredients
        ]);
    }

    // レシピ削除処理
    public function action_delete($id = null)
    {
        /*if (!Auth::check()) {
            Response::redirect('user/login');
        }*/

        if (!$id) {
            Response::redirect('home/index');
        }

        $user_id = Auth::get_user_id()[1];

        // 削除対象のレシピを確認
        $recipe = Model_Recipe::query()
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->get_one();

        if ($recipe) {
            // recipe_ingredients も削除
            DB::delete('recipe_ingredients')
                ->where('recipe_id', $recipe->id)
                ->execute();

            // recipes テーブルから削除
            DB::delete('recipes')
                ->where('id', $recipe->id)
                ->execute();
        }

        // ホーム画面に戻る
        Response::redirect('home/index');
    }


}
