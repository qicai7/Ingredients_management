<?php
class Model_Recipe extends Orm\Model
{
    protected static $_properties = [
        'id',
        'user_id',
        'name',
        'created_at',
        'updated_at',
    ];

    protected static $_table_name = 'recipes';
}
