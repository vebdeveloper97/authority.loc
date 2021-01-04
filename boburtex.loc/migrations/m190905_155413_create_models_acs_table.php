<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_acs}}`.
 */
class m190905_155413_create_models_acs_table extends Migration
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
        $this->createTable('{{%models_acs}}', [
            'id' => $this->primaryKey(),
            'model_list_id' => $this->integer(),
            'bichuv_acs_id' => $this->integer(),
            'qty' => $this->decimal(20,3),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),

        ], $tableOptions);

        //model_list_id
        $this->createIndex(
            'idx-models_acs-model_list_id',
            'models_acs',
            'model_list_id'
        );

        $this->addForeignKey(
            'fk-models_acs-model_list_id',
            'models_acs',
            'model_list_id',
            'models_list',
            'id'
        );

        //bichuv_acs_id
        $this->createIndex(
            'idx-models_acs-bichuv_acs_id',
            'models_acs',
            'bichuv_acs_id'
        );

        $this->addForeignKey(
            'fk-models_acs-bichuv_acs_id',
            'models_acs',
            'bichuv_acs_id',
            'bichuv_acs',
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
            'fk-models_acs-model_list_id',
            'models_acs'
        );

        $this->dropIndex(
            'idx-models_acs-model_list_id',
            'models_acs'
        );

        //bichuv_acs_id
        $this->dropForeignKey(
            'fk-models_acs-bichuv_acs_id',
            'models_acs'
        );

        $this->dropIndex(
            'idx-models_acs-bichuv_acs_id',
            'models_acs'
        );

        $this->dropTable('{{%models_acs}}');
    }
}
