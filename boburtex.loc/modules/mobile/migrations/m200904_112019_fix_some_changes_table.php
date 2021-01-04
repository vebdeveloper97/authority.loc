<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mobile_process_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_detail_list}}`
 */
class m200904_112019_fix_some_changes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `mobile_process_production` DROP FOREIGN KEY `fk-mobile_process_production-base_detail_list_id`; ALTER TABLE `mobile_process_production` ADD CONSTRAINT `fk-mobile_process_production-base_detail_list_id` FOREIGN KEY (`base_detail_list_id`) REFERENCES `base_detail_lists`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}

