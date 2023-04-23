<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230421145746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stockage ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stockage ADD CONSTRAINT FK_CABCB492A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CABCB492A76ED395 ON stockage (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stockage DROP FOREIGN KEY FK_CABCB492A76ED395');
        $this->addSql('DROP INDEX IDX_CABCB492A76ED395 ON stockage');
        $this->addSql('ALTER TABLE stockage DROP user_id');
    }
}
