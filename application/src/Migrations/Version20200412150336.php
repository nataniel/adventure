<?php
namespace Main\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200412150336 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE games_operators (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, user_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_AEA0A1FAE48FD905 (game_id), INDEX IDX_AEA0A1FAA76ED395 (user_id), UNIQUE INDEX game_user (game_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_FF232B315E237E06 (name), INDEX IDX_FF232B31DE12AB56 (created_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE games_operators ADD CONSTRAINT FK_AEA0A1FAE48FD905 FOREIGN KEY (game_id) REFERENCES games (id)');
        $this->addSql('ALTER TABLE games_operators ADD CONSTRAINT FK_AEA0A1FAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B31DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('DROP INDEX UNIQ_2074E5755E237E06 ON pages');
        $this->addSql('ALTER TABLE pages ADD game_id INT DEFAULT NULL AFTER id, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE pages ADD CONSTRAINT FK_2074E575E48FD905 FOREIGN KEY (game_id) REFERENCES games (id)');
        $this->addSql('CREATE INDEX IDX_2074E575E48FD905 ON pages (game_id)');
        $this->addSql('CREATE UNIQUE INDEX game_page ON pages (game_id, name)');
    }

    public function down(Schema $schema) : void
    {
    }
}
