<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_testing}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%test}}`
 */
class m230426_133111_create_user_testing_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_testing}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'test_id' => $this->integer(),
            'status' => $this->string()->defaultValue('new'),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_testing-user_id}}',
            '{{%user_testing}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_testing-user_id}}',
            '{{%user_testing}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `test_id`
        $this->createIndex(
            '{{%idx-user_testing-test_id}}',
            '{{%user_testing}}',
            'test_id'
        );

        // add foreign key for table `{{%test}}`
        $this->addForeignKey(
            '{{%fk-user_testing-test_id}}',
            '{{%user_testing}}',
            'test_id',
            '{{%test}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_testing-user_id}}',
            '{{%user_testing}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_testing-user_id}}',
            '{{%user_testing}}'
        );

        // drops foreign key for table `{{%test}}`
        $this->dropForeignKey(
            '{{%fk-user_testing-test_id}}',
            '{{%user_testing}}'
        );

        // drops index for column `test_id`
        $this->dropIndex(
            '{{%idx-user_testing-test_id}}',
            '{{%user_testing}}'
        );

        $this->dropTable('{{%user_testing}}');
    }
}
