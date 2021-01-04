<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_var_baski}}`.
 */
class m190905_155947_create_model_var_baski_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%model_var_baski}}', [
            'id' => $this->primaryKey(),
            'model_list_id' => $this->integer(),
            'name' => $this->string(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //model_list_id
        $this->createIndex(
            'idx-model_var_baski-model_list_id',
            'model_var_baski',
            'model_list_id'
        );

        $this->addForeignKey(
            'fk-model_var_baski-model_list_id',
            'model_var_baski',
            'model_list_id',
            'models_list',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //model_list_id
        $this->dropForeignKey(
            'fk-model_var_baski-model_list_id',
            'model_var_baski'
        );

        $this->dropIndex(
            'idx-model_var_baski-model_list_id',
            'model_var_baski'
        );

        $this->dropTable('{{%model_var_baski}}');
    }
}
