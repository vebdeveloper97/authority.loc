<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_acs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_variations}}`
 */
class m200815_065219_add_models_var_id_column_to_models_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_acs}}', 'models_var_id', $this->integer());

        // creates index for column `models_var_id`
        $this->createIndex(
            '{{%idx-models_acs-models_var_id}}',
            '{{%models_acs}}',
            'models_var_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `models_var_id`
        $this->dropIndex(
            '{{%idx-models_acs-models_var_id}}',
            '{{%models_acs}}'
        );

        $this->dropColumn('{{%models_acs}}', 'models_var_id');
    }
}
