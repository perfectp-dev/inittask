<?php

use yii\db\Migration;

/**
 * Class m220715_130704_insert_data
 */
class m220715_130704_insert_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute(
            file_get_contents(
                '/db/test_db_data.sql')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220715_130704_insert_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220715_130704_insert_data cannot be reverted.\n";

        return false;
    }
    */
}
