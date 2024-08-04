<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240804153753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card ADD COLUMN is_published BOOLEAN DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE folder ADD COLUMN is_published BOOLEAN DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE level ADD COLUMN is_published BOOLEAN DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__card AS SELECT id, folder_id, word, translation, sentence, image FROM card');
        $this->addSql('DROP TABLE card');
        $this->addSql('CREATE TABLE card (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, folder_id INTEGER NOT NULL, word VARCHAR(255) NOT NULL, translation VARCHAR(255) NOT NULL, sentence VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_161498D3162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO card (id, folder_id, word, translation, sentence, image) SELECT id, folder_id, word, translation, sentence, image FROM __temp__card');
        $this->addSql('DROP TABLE __temp__card');
        $this->addSql('CREATE INDEX IDX_161498D3162CB942 ON card (folder_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__folder AS SELECT id, level_id, name, image FROM folder');
        $this->addSql('DROP TABLE folder');
        $this->addSql('CREATE TABLE folder (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, level_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, CONSTRAINT FK_ECA209CD5FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO folder (id, level_id, name, image) SELECT id, level_id, name, image FROM __temp__folder');
        $this->addSql('DROP TABLE __temp__folder');
        $this->addSql('CREATE INDEX IDX_ECA209CD5FB14BA7 ON folder (level_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__level AS SELECT id, language_id, name FROM level');
        $this->addSql('DROP TABLE level');
        $this->addSql('CREATE TABLE level (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, language_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_9AEACC1382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO level (id, language_id, name) SELECT id, language_id, name FROM __temp__level');
        $this->addSql('DROP TABLE __temp__level');
        $this->addSql('CREATE INDEX IDX_9AEACC1382F1BAF4 ON level (language_id)');
    }
}
