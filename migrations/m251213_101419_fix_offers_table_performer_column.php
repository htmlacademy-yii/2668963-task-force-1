<?php

use yii\db\Migration;

class m251213_101419_fix_offers_table_performer_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('offers', 'performer_id', 'int');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251213_101419_fix_offers_table_performer_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251213_101419_fix_offers_table_performer_column cannot be reverted.\n";

        return false;
    }
    */
}
