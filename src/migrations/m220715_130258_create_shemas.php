<?php

use yii\db\Migration;

/**
 * Class m220715_130258_create_shemas
 */
class m220715_130258_create_shemas extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            file_get_contents(
                '/db/test_db_structure.sql')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220715_130258_create_shemas cannot be reverted.\n";

        return false;
    }
}
