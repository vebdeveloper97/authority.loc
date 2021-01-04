<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_variations}}`.
 */
class m190905_155436_create_models_variations_table extends Migration
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
        $this->createTable('{{%models_variations}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'model_list_id' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //model_list_id
        $this->createIndex(
            'idx-models_variations-model_list_id',
            'models_variations',
            'model_list_id'
        );

        $this->addForeignKey(
            'fk-models_variations-model_list_id',
            'models_variations',
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
            'fk-models_variations-model_list_id',
            'models_variations'
        );

        $this->dropIndex(
            'idx-models_variations-model_list_id',
            'models_variations'
        );
        $this->dropTable('{{%models_variations}}');
    }
}
