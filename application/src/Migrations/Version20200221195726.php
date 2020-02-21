<?php
namespace Main\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200221195726 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE pages_choices CHANGE description description TEXT NOT NULL');
    }

    public function down(Schema $schema)
    {
    }
}
