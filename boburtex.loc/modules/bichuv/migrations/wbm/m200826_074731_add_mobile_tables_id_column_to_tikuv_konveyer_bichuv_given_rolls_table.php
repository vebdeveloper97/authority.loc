<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_konveyer_bichuv_given_rolls}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_tables}}`
 */
class m200826_074731_add_mobile_tables_id_column_to_tikuv_konveyer_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_konveyer_bichuv_given_rolls}}', 'mobile_tables_id', $this->integer());

        $this->addPrimaryKey('PRIMARY', 'tikuv_konveyer_bichuv_given_rolls', ['mobile_tables_id', 'bichuv_given_rolls_id']);

        // creates index for column `mobile_tables_id`
        $this->createIndex(
            '{{%idx-tikuv_konveyer_bichuv_given_rolls-mobile_tables_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}',
            'mobile_tables_id'
        );

        // add foreign key for table `{{%mobile_tables}}`
        $this->addForeignKey(
            '{{%fk-tikuv_konveyer_bichuv_given_rolls-mobile_tables_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}',
            'mobile_tables_id',
            '{{%mobile_tables}}',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // drops foreign key for table `{{%mobile_tables}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_konveyer_bichuv_given_rolls-mobile_tables_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}'
        );

        // drops index for column `mobile_tables_id`
        $this->dropIndex(
            '{{%idx-tikuv_konveyer_bichuv_given_rolls-mobile_tables_id}}',
            '{{%tikuv_konveyer_bichuv_given_rolls}}'
        );

        $this->dropPrimaryKey('PRIMARY', 'tikuv_konveyer_bichuv_given_rolls');

        $this->dropColumn('{{%tikuv_konveyer_bichuv_given_rolls}}', 'mobile_tables_id');
    }
}
