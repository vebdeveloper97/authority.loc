<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_raw_materials}}`.
 */
class m190905_155356_create_models_raw_materials_table extends Migration
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
        $this->createTable('{{%models_raw_materials}}', [
            'id' => $this->primaryKey(),
            'model_list_id' => $this->integer(),
            'rm_id' => $this->integer(),
            'is_main' => $this->boolean(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //model_list_id
        $this->createIndex(
            'idx-models_raw_materials-model_list_id',
            'models_raw_materials',
            'model_list_id'
        );

        $this->addForeignKey(
            'fk-models_raw_materials-model_list_id',
            'models_raw_materials',
            'model_list_id',
            'models_list',
            'id'
        );

        //rm_id
        $this->createIndex(
            'idx-models_raw_materials-rm_id',
            'models_raw_materials',
            'rm_id'
        );

        $this->addForeignKey(
            'fk-models_raw_materials-rm_id',
            'models_raw_materials',
            'rm_id',
            'toquv_raw_materials',
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
            'fk-models_raw_materials-model_list_id',
            'models_raw_materials'
        );

        $this->dropIndex(
            'idx-models_raw_materials-model_list_id',
            'models_raw_materials'
        );

        //rm_id
        $this->dropForeignKey(
            'fk-models_raw_materials-rm_id',
            'models_raw_materials'
        );

        $this->dropIndex(
            'idx-models_raw_materials-rm_id',
            'models_raw_materials'
        );

        $this->dropTable('{{%models_raw_materials}}');
    }
}
