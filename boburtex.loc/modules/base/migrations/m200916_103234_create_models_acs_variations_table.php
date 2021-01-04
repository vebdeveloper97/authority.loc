<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_acs_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 * - `{{%bichuv_acs}}`
 */
class m200916_103234_create_models_acs_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%models_acs_variations}}', [
            'id' => $this->primaryKey(),
            'models_list_id' => $this->integer(),
            'model_var_id' => $this->integer(),
            'bichuv_acs_id' => $this->integer(),
        ]);

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-models_acs_variations-models_list_id}}',
            '{{%models_acs_variations}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-models_acs_variations-models_list_id}}',
            '{{%models_acs_variations}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-models_acs_variations-model_var_id}}',
            '{{%models_acs_variations}}',
            'model_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-models_acs_variations-model_var_id}}',
            '{{%models_acs_variations}}',
            'model_var_id',
            '{{%models_variations}}',
            'id',
            'CASCADE'
        );

        // creates index for column `bichuv_acs_id`
        $this->createIndex(
            '{{%idx-models_acs_variations-bichuv_acs_id}}',
            '{{%models_acs_variations}}',
            'bichuv_acs_id'
        );

        // add foreign key for table `{{%bichuv_acs}}`
        $this->addForeignKey(
            '{{%fk-models_acs_variations-bichuv_acs_id}}',
            '{{%models_acs_variations}}',
            'bichuv_acs_id',
            '{{%bichuv_acs}}',
            'id',
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
            '{{%fk-models_acs_variations-models_list_id}}',
            '{{%models_acs_variations}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-models_acs_variations-models_list_id}}',
            '{{%models_acs_variations}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-models_acs_variations-model_var_id}}',
            '{{%models_acs_variations}}'
        );

        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-models_acs_variations-model_var_id}}',
            '{{%models_acs_variations}}'
        );

        // drops foreign key for table `{{%bichuv_acs}}`
        $this->dropForeignKey(
            '{{%fk-models_acs_variations-bichuv_acs_id}}',
            '{{%models_acs_variations}}'
        );

        // drops index for column `bichuv_acs_id`
        $this->dropIndex(
            '{{%idx-models_acs_variations-bichuv_acs_id}}',
            '{{%models_acs_variations}}'
        );

        $this->dropTable('{{%models_acs_variations}}');
    }
}
