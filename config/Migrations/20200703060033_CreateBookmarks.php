<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateBookmarks extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('bookmarks');
        $table
            ->addColumn('user_id', 'integer', [
                'null' => false
            ])
            ->addColumn('title', 'string', [
                'limit' => 50
            ])
            ->addColumn('description', 'text')
            ->addColumn('url', 'text')
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->addForeignKey('user_id', 'users', 'id')
            ->create();
    }
}
