<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_list}}`.
 */
class m200929_064156_add_is_kit_column_to_models_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_list}}', 'is_kit', $this->smallInteger(1)->defaultValue(1));

        // creates index for column `is_kit`
        $this->createIndex(
            '{{%idx-models_list-is_kit}}',
            '{{%models_list}}',
            'is_kit'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `is_kit`
        $this->dropIndex(
            '{{%idx-models_list-is_kit}}',
            '{{%models_list}}'
        );
        $this->dropColumn('{{%models_list}}', 'is_kit');
    }
}
