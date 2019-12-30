<?php
namespace Main\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20191230165151 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE pages_choices ADD status VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema)
    {
    }
}