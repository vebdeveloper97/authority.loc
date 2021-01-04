<?php

use yii\db\Migration;

/**
 * Class m200121_140812_alter_column_to_users_table
 */
class m200121_140812_alter_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE `users` CHANGE `uid` `uid` INT(11) NULL DEFAULT NULL, CHANGE `lavozimi` `lavozimi` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `user_role` `user_role` SMALLINT(6) NULL DEFAULT NULL, CHANGE `add_info` `add_info` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `session_id` `session_id` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
