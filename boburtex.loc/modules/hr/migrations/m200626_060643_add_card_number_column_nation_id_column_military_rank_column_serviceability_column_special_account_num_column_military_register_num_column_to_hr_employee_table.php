<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_employee}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_nation}}`
 */
class m200626_060643_add_card_number_column_nation_id_column_military_rank_column_serviceability_column_special_account_num_column_military_register_num_column_to_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_employee}}', 'card_number', $this->string());
        $this->addColumn('{{%hr_employee}}', 'hr_nation_id', $this->integer());
        $this->addColumn('{{%hr_employee}}', 'military_rank', $this->string(100));
        $this->addColumn('{{%hr_employee}}', 'serviceability', $this->string());
        $this->addColumn('{{%hr_employee}}', 'special_account_num', $this->string());
        $this->addColumn('{{%hr_employee}}', 'military_register_num', $this->string());

        // creates index for column `hr_nation_id`
        $this->createIndex(
            '{{%idx-hr_employee-hr_nation_id}}',
            '{{%hr_employee}}',
            'hr_nation_id'
        );

        // add foreign key for table `{{%hr_nation}}`
        $this->addForeignKey(
            '{{%fk-hr_employee-hr_nation_id}}',
            '{{%hr_employee}}',
            'hr_nation_id',
            '{{%hr_nation}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_nation}}`
        $this->dropForeignKey(
            '{{%fk-hr_employee-hr_nation_id}}',
            '{{%hr_employee}}'
        );

        // drops index for column `hr_nation_id`
        $this->dropIndex(
            '{{%idx-hr_employee-hr_nation_id}}',
            '{{%hr_employee}}'
        );

        $this->dropColumn('{{%hr_employee}}', 'card_number');
        $this->dropColumn('{{%hr_employee}}', 'hr_nation_id');
        $this->dropColumn('{{%hr_employee}}', 'military_rank');
        $this->dropColumn('{{%hr_employee}}', 'serviceability');
        $this->dropColumn('{{%hr_employee}}', 'special_account_num');
        $this->dropColumn('{{%hr_employee}}', 'military_register_num');
    }
}
