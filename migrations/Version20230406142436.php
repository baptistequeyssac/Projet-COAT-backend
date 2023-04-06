<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230406142436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artist ADD region_id INT NOT NULL, CHANGE address address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE artist ADD CONSTRAINT FK_159968798260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_159968798260155 ON artist (region_id)');
        $this->addSql('ALTER TABLE event ADD region_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA798260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA798260155 ON event (region_id)');
        $this->addSql('ALTER TABLE organizer ADD status_id INT NOT NULL, ADD region_id INT NOT NULL');
        $this->addSql('ALTER TABLE organizer ADD CONSTRAINT FK_99D471736BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE organizer ADD CONSTRAINT FK_99D4717398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_99D471736BF700BD ON organizer (status_id)');
        $this->addSql('CREATE INDEX IDX_99D4717398260155 ON organizer (region_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist DROP FOREIGN KEY FK_159968798260155');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA798260155');
        $this->addSql('ALTER TABLE organizer DROP FOREIGN KEY FK_99D4717398260155');
        $this->addSql('ALTER TABLE organizer DROP FOREIGN KEY FK_99D471736BF700BD');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP INDEX IDX_3BAE0AA798260155 ON event');
        $this->addSql('ALTER TABLE event DROP region_id');
        $this->addSql('DROP INDEX IDX_159968798260155 ON artist');
        $this->addSql('ALTER TABLE artist DROP region_id, CHANGE address address VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX IDX_99D471736BF700BD ON organizer');
        $this->addSql('DROP INDEX IDX_99D4717398260155 ON organizer');
        $this->addSql('ALTER TABLE organizer DROP status_id, DROP region_id');
    }
}
