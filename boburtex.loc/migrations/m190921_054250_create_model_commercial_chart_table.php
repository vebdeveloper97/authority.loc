<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_commercial_chart}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 */
class m190921_054250_create_model_commercial_chart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_commercial_chart}}', [
            'id' => $this->primaryKey(),
            'models_list_id' => $this->integer(),
            'name' => $this->string(),
            'size' => $this->integer(),
            'extension' => $this->string(10),
            'path' => $this->string(),
            'is_main' => $this->boolean(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-model_commercial_chart-models_list_id}}',
            '{{%model_commercial_chart}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-model_commercial_chart-models_list_id}}',
            '{{%model_commercial_chart}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-model_commercial_chart-models_list_id}}',
            '{{%model_commercial_chart}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-model_commercial_chart-models_list_id}}',
            '{{%model_commercial_chart}}'
        );

        $this->dropTable('{{%model_commercial_chart}}');
    }
}
