<?php
namespace Main\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20191230160110 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE pages_choices DROP FOREIGN KEY FK_95DC01D3158E0B66');
        $this->addSql('DROP INDEX IDX_95DC01D3158E0B66 ON pages_choices');
        $this->addSql('ALTER TABLE pages_choices DROP target_id, CHANGE name target VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2074E5755E237E06 ON pages (name)');
    }

    public function down(Schema $schema)
    {
    }
}