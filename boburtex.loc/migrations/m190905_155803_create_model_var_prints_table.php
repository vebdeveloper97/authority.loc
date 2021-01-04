<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_var_prints}}`.
 */
class m190905_155803_create_model_var_prints_table extends Migration
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
        $this->createTable('{{%model_var_prints}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'model_list_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //model_list_id
        $this->createIndex(
            'idx-model_var_prints-model_list_id',
            'model_var_prints',
            'model_list_id'
        );

        $this->addForeignKey(
            'fk-model_var_prints-model_list_id',
            'model_var_prints',
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
            'fk-model_var_prints-model_list_id',
            'model_var_prints'
        );

        $this->dropIndex(
            'idx-model_var_prints-model_list_id',
            'model_var_prints'
        );
        $this->dropTable('{{%model_var_prints}}');
    }
}
