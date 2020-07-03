<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateBookmarksTags extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */

    public $autoId = false;

    public function change()
    {
        $table = $this->table('bookmarks_tags');
        $table->addColumn('bookmark_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('tag_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addPrimaryKey([
            'bookmark_id',
            'tag_id',
        ]);
        $table->addForeignKey('tag_id', 'tags', 'id');
        $table->addForeignKey('bookmark_id', 'bookmarks', 'id');
        $table->create();
    }
}
