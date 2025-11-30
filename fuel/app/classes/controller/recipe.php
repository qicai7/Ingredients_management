<?php
// DB操作をModelに移譲するため、DBクラスを直接参照しない
// AuthとInput、View、ResponseはControllerの責務なので残す
class Controller_Recipe extends Controller
{
    public function before()
    {
        parent::before();

        // logoutアクション以外はログイン必須
        $action = Request::active()->action;

        // Auth::check() はDB操作ではないためControllerに残す
        if ($action !== 'logout' && !Auth::check()) {
            Response::redirect('user/login');
        }
    }

    // レシピ追加画面
    public function action_add()
    {
        $error = '';
        $success = '';

        if (Input::post('name')) {
            // ユーザーIDはControllerで取得
            $user_id = Auth::get_user_id()[1];

            $ingredients_post = Input::post('ingredients', []);
            $amounts_post     = Input::post('amounts', []);

            // バリデーションはControllerの責務として残す
            foreach ($ingredients_post as $index => $ingredient_name) {
                if (trim($ingredient_name) === '' || trim($amounts_post[$index] ?? '') === '') {
                    $error = '全てのフォームに入力してください';
                    // Viewの呼び出しはControllerの責務
                    return View::forge('recipe/add', ['error' => $error, 'success' => '']);
                }
            }

            try {
                // ここから下のDB操作を全てModelに移譲する
                
                // 1. recipes テーブルに追加 (Model_Recipe)
                $recipe_id = Model_Recipe::create_recipe([
                    'user_id' => $user_id,
                    'name'    => Input::post('name'),
                ]);

                // 2. 材料と分量の登録処理 (Model_Ingredient, Model_RecipeIngredient)
                foreach ($ingredients_post as $index => $ingredient_name) {
                    $ingredient_name = trim($ingredient_name);
                    $amount          = trim($amounts_post[$index] ?? '');

                    // ingredients テーブルに追加 or 既存のIDを取得 (Model_Ingredient)
                    $ingredient_id = Model_Ingredient::find_or_create($ingredient_name);

                    // recipe_ingredients に紐付け (Model_RecipeIngredient)
                    Model_RecipeIngredient::create_link($recipe_id, $ingredient_id, $amount);
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
        // 1. IDがURLから渡されているかを確認
        if (is_null($id)) {
            // IDが渡されていない場合は即座にリダイレクト
            return Response::redirect('home/index');
        }

        $user_id = Auth::get_user_id()[1];

        // DBクラスメソッドに置き換え
        $recipe = Model_Recipe::get_by_id_and_user_id($id, $user_id);

        // 2. 【修正ポイント】レシピが見つからなかった場合の処理を確実に行う
        // (Modelが null または false を返すと仮定)
        if (empty($recipe)) { // 配列でも nullでも falseでも、空なら処理する
            // 見つからなかった場合は即座にリダイレクト
            return Response::redirect('home/index'); 
        }

        // DB直接操作からModelのDBクラスメソッドに置き換え
        $ingredients = Model_RecipeIngredient::get_ingredients_with_amount_by_recipe_id($recipe['id']);

        // 3. Viewに渡す (この時点で $recipe は null ではないことが保証される)
        return View::forge('recipe/view', [
            'recipe'      => $recipe,
            'ingredients' => $ingredients
        ]);
    }

    // レシピ編集画面
    public function action_edit($id = null)
    {
        if (!$id) {
            Response::redirect('home/index');
        }

        $user_id = Auth::get_user_id()[1];

        // ORM呼び出しからModelのDBクラスメソッドに置き換え
        $recipe = Model_Recipe::get_by_id_and_user_id($id, $user_id);

        if (!$recipe) {
            Response::redirect('home/index');
        }

        // DB直接操作からModelのDBクラスメソッドに置き換え
        $ingredients = Model_RecipeIngredient::get_ingredients_for_edit_by_recipe_id($recipe['id']);

        $error = '';

        if (Input::post('name')) {
            try {
                // recipes テーブル更新
                Model_Recipe::update_recipe($recipe['id'], Input::post('name'));

                // 一旦既存の材料紐付けを削除
                Model_RecipeIngredient::delete_by_recipe_id($recipe['id']);

                // 新しい材料と分量を登録
                $ingredients_post = Input::post('ingredients', []);
                $amounts_post     = Input::post('amounts', []);

                foreach ($ingredients_post as $index => $ingredient_name) {
                    $ingredient_name = trim($ingredient_name);
                    $amount          = trim($amounts_post[$index] ?? '');

                    if ($ingredient_name === '') continue;

                    // ingredients テーブルに追加 or 既存のID取得
                    $ingredient_id = Model_Ingredient::find_or_create($ingredient_name);

                    // recipe_ingredients に紐付け
                    Model_RecipeIngredient::create_link($recipe['id'], $ingredient_id, $amount);
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
        if (!$id) {
            Response::redirect('home/index');
        }

        $user_id = Auth::get_user_id()[1];

        // ORM呼び出しからModelのDBクラスメソッドに置き換え
        $recipe = Model_Recipe::get_by_id_and_user_id($id, $user_id);

        if (!$recipe) {
            Response::redirect('home/index');
        }

        // DB直接操作からModelのDBクラスメソッドに置き換え
        $ingredients = Model_RecipeIngredient::get_ingredients_with_amount_by_recipe_id($recipe['id']);

        return View::forge('recipe/delete', [
            'recipe'      => $recipe,
            'ingredients' => $ingredients
        ]);
    }

    // レシピ削除処理
    public function action_delete($id = null)
    {
        if (!$id) {
            Response::redirect('home/index');
        }

        $user_id = Auth::get_user_id()[1];

        // 削除対象のレシピを確認
        $recipe = Model_Recipe::get_by_id_and_user_id($id, $user_id);

        if ($recipe) {
            // トランザクション処理はModelに書くのが理想だが、ここではControllerから連続呼び出しで代替
            
            // 1. recipe_ingredients も削除 (Model_RecipeIngredient)
            Model_RecipeIngredient::delete_by_recipe_id($recipe['id']);

            // 2. recipes テーブルから削除 (Model_Recipe)
            Model_Recipe::delete_recipe($recipe['id']);
        }

        Response::redirect('home/index');
    }
}