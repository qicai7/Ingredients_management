<?php
class Model_Ingredient extends \Model
{
    private static $table = 'ingredients';

    /**
     * 材料名で検索し、存在しなければ作成してIDを返す
     * @param string $name 材料名
     * @return int 材料ID
     */
    public static function find_or_create($name)
    {
        // 既存の材料を検索
        $ingredient = \DB::select('id')->from(self::$table)
            ->where('name', $name)
            ->execute()
            ->current();

        if ($ingredient) {
            return $ingredient['id'];
        }

        // 存在しなければ新規作成
        list($insert_id, $rows_affected) = \DB::insert(self::$table)
            ->set([
                'name'       => $name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ])
            ->execute();

        return $insert_id;
    }
}