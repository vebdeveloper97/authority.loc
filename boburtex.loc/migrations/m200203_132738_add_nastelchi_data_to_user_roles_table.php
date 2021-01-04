<?php

use yii\db\Migration;

/**
 * Class m200203_132738_add_nastelchi_data_to_user_roles_table
 */
class m200203_132738_add_nastelchi_data_to_user_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `user_roles` (`id`, `role_name`, `code`, `department`) VALUES (NULL, 'Nastelchi', 'NASTELCHI', 'bichuv');");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //$this->execute("DELETE FROM `user_roles` WHERE `user_roles`.`code` = 'NASTELCHI';");
    }
}
