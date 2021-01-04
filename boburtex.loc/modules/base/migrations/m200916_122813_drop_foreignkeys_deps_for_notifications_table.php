<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%foreignkeys_deps_for_notifications}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_departments}}`
 * - `{{%toquv_departments}}`
 */
class m200916_122813_drop_foreignkeys_deps_for_notifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-notifications-dept_from}}',
            '{{%notifications}}'
        );

        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-notifications-dept_to}}',
            '{{%notifications}}'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-notifications-dept_from}}',
            '{{%notifications}}',
            'dept_from',
            '{{%toquv_departments}}',
            'id'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-notifications-dept_to}}',
            '{{%notifications}}',
            'dept_to',
            '{{%toquv_departments}}',
            'id'
        );
    }
}
