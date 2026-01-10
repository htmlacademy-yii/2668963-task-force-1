<?php

use yii\db\Migration;

class m260109_140747_specializationFix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = '{{%specializations}}';

        $columns = $this->db->schema->getTableSchema($table)->columns;
        foreach ($columns as $name => $column) {
            if ($name !== 'id') {
                foreach ($this->db->schema->getTableSchema($table)->foreignKeys as $fkName => $fk) {
                    if (in_array($name, $fk)) {
                        $this->dropForeignKey($fkName, $table);
                    }
                }
                $this->dropColumn($table, $name);
            }
        }

        $this->addColumn($table, 'performer_id', $this->integer()->null());
        $this->addColumn($table, 'category_id', $this->integer()->null());

        $this->addForeignKey(
            'fk-specializations-performer',
            $table,
            'performer_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-specializations-category',
            $table,
            'category_id',
            '{{%categories}}',
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
        echo "m260109_140747_specializationFix cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260109_140747_specializationFix cannot be reverted.\n";

        return false;
    }
    */
}
