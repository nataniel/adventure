<?php
namespace Main\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20181110172907 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE pages_choices (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, target_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, position INT DEFAULT NULL, INDEX IDX_95DC01D3727ACA70 (parent_id), INDEX IDX_95DC01D3158E0B66 (target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pages (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pages_choices ADD CONSTRAINT FK_95DC01D3727ACA70 FOREIGN KEY (parent_id) REFERENCES pages (id)');
        $this->addSql('ALTER TABLE pages_choices ADD CONSTRAINT FK_95DC01D3158E0B66 FOREIGN KEY (target_id) REFERENCES pages (id)');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE pages_choices DROP FOREIGN KEY FK_95DC01D3727ACA70');
        $this->addSql('ALTER TABLE pages_choices DROP FOREIGN KEY FK_95DC01D3158E0B66');
        $this->addSql('DROP TABLE pages_choices');
        $this->addSql('DROP TABLE pages');
    }
}