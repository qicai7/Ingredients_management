<?php
// Model_Recipe は \Model を継承
class Model_Recipe extends \Model
{
    private static $table = 'recipes';

    /**
     * IDとユーザーIDを指定してレシピを1件取得（詳細・編集・削除に使用）
     * @param int $id レシピID
     * @param int $user_id ユーザーID
     * @return array|null 1件のレシピデータ（連想配列）
     */
    public static function get_by_id_and_user_id($id, $user_id) 
    {
        $result = \DB::select('*')
            ->from(self::$table)
            ->where('id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->limit(1)
            ->execute();

        // current() で1件の結果を返す
        return $result->current(); 
    }

    /**
     * IDとユーザーIDを指定してレシピを1件取得（詳細・編集・削除に使用）
     * @param int $user_id ユーザーID
     * @return array レシピデータの配列
     */
    public static function get_recipes_by_user_id($user_id) // 新規追加
    {
        $result = \DB::select('*')
            ->from(self::$table)
            ->where('user_id', '=', $user_id)
            // 通常、新しいものから表示したいので、created_at の降順でソート
            ->order_by('created_at', 'desc')
            ->execute();
            
        // 結果は連想配列の配列として返されます
        return $result->as_array();
    }

    /**
     * 新しいレシピを作成
     * @param array $data 挿入するデータ (user_id, nameなど)
     * @return int 挿入されたレコードのID
     */
    public static function create_recipe(array $data)
    {
        // タイムスタンプはModel側で設定
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        list($insert_id, $rows_affected) = \DB::insert(self::$table)
            ->set($data)
            ->execute();

        return $insert_id;
    }

    /**
     * レシピ名を更新
     * @param int $id レシピID
     * @param string $name 新しいレシピ名
     */
    public static function update_recipe($id, $name)
    {
        \DB::update(self::$table)
            ->set([
                'name'       => $name,
                'updated_at' => date('Y-m-d H:i:s')
            ])
            ->where('id', $id)
            ->execute();
    }

    /**
     * レシピを削除
     * @param int $id レシピID
     */
    public static function delete_recipe($id)
    {
        \DB::delete(self::$table)
            ->where('id', $id)
            ->execute();
    }
}