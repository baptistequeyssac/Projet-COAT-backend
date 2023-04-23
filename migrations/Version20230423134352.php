<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230423134352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stockage ADD event_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stockage ADD CONSTRAINT FK_CABCB49271F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_CABCB49271F7E88B ON stockage (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stockage DROP FOREIGN KEY FK_CABCB49271F7E88B');
        $this->addSql('DROP INDEX IDX_CABCB49271F7E88B ON stockage');
        $this->addSql('ALTER TABLE stockage DROP event_id, CHANGE user_id user_id INT NOT NULL');
    }
}
