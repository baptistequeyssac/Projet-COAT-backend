<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230404095330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist ADD region VARCHAR(128) NOT NULL, ADD address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD region VARCHAR(128) NOT NULL');
        $this->addSql('ALTER TABLE stockage CHANGE video video VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP role, CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP region');
        $this->addSql('ALTER TABLE artist DROP region, DROP address');
        $this->addSql('ALTER TABLE stockage CHANGE video video VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD role VARCHAR(32) NOT NULL, DROP roles, CHANGE email email VARCHAR(255) NOT NULL');
    }
}
