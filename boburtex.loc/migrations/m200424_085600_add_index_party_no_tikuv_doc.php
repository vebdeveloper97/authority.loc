<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tikuv_doc}}`
 */
class m200424_085600_add_index_party_no_tikuv_doc extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for column `party_no`
        $this->createIndex(
            '{{%idx-tikuv_doc-party_no}}',
            '{{%tikuv_doc}}',
            'party_no'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // drops index for column `party_no`
        $this->dropIndex(
            '{{%idx-tikuv_doc-party_no}}',
            '{{%tikuv_doc}}'
        );
    }
}
