<?php
class Model_RecipeIngredient extends \Model
{
    private static $table = 'recipe_ingredients';

    /**
     * recipe_ingredients に紐付けを登録
     * @param int $recipe_id レシピID
     * @param int $ingredient_id 材料ID
     * @param string $amount 分量
     */
    public static function create_link($recipe_id, $ingredient_id, $amount)
    {
        \DB::insert(self::$table)
            ->set([
                'recipe_id'     => $recipe_id,
                'ingredient_id' => $ingredient_id,
                'amount'        => $amount,
            ])
            ->execute();
    }

    /**
     * レシピIDに基づいて材料と分量を取得 (詳細画面用)
     * @param int $recipe_id レシピID
     * @return array
     */
    public static function get_ingredients_with_amount_by_recipe_id($recipe_id)
    {
        // 複雑なJOINクエリもModelにカプセル化
        return \DB::select('i.name', 'ri.amount')
            ->from([self::$table, 'ri'])
            ->join(['ingredients', 'i'], 'INNER')
            ->on('ri.ingredient_id', '=', 'i.id')
            ->where('ri.recipe_id', $recipe_id)
            ->execute()
            ->as_array();
    }
    
    /**
     * レシピIDに基づいて材料と分量を編集画面に必要な情報と合わせて取得
     * @param int $recipe_id レシピID
     * @return array
     */
    public static function get_ingredients_for_edit_by_recipe_id($recipe_id)
    {
        // 編集画面に必要な情報（ingredient_id, ri_id）を含めて取得
        return \DB::select(
                ['i.name', 'name'],
                ['ri.amount', 'amount'],
                ['i.id', 'ingredient_id'],
                ['ri.id', 'ri_id']
            )
            ->from([self::$table, 'ri'])
            ->join(['ingredients', 'i'], 'INNER')
            ->on('ri.ingredient_id', '=', 'i.id')
            ->where('ri.recipe_id', $recipe_id)
            ->execute()
            ->as_array();
    }

    /**
     * レシピIDに基づいて紐付けレコードを削除
     * @param int $recipe_id レシピID
     */
    public static function delete_by_recipe_id($recipe_id)
    {
        \DB::delete(self::$table)
            ->where('recipe_id', $recipe_id)
            ->execute();
    }
}