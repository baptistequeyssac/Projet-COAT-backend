<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230404123717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_artist (event_id INT NOT NULL, artist_id INT NOT NULL, INDEX IDX_33C0E1D571F7E88B (event_id), INDEX IDX_33C0E1D5B7970CF8 (artist_id), PRIMARY KEY(event_id, artist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_organizer (event_id INT NOT NULL, organizer_id INT NOT NULL, INDEX IDX_1F414F4E71F7E88B (event_id), INDEX IDX_1F414F4E876C4DDA (organizer_id), PRIMARY KEY(event_id, organizer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organizer_artist (organizer_id INT NOT NULL, artist_id INT NOT NULL, INDEX IDX_59A91261876C4DDA (organizer_id), INDEX IDX_59A91261B7970CF8 (artist_id), PRIMARY KEY(organizer_id, artist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_artist ADD CONSTRAINT FK_33C0E1D571F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_artist ADD CONSTRAINT FK_33C0E1D5B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_organizer ADD CONSTRAINT FK_1F414F4E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_organizer ADD CONSTRAINT FK_1F414F4E876C4DDA FOREIGN KEY (organizer_id) REFERENCES organizer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organizer_artist ADD CONSTRAINT FK_59A91261876C4DDA FOREIGN KEY (organizer_id) REFERENCES organizer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organizer_artist ADD CONSTRAINT FK_59A91261B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7C54C8C93 ON event (type_id)');
        $this->addSql('ALTER TABLE stockage ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE stockage ADD CONSTRAINT FK_CABCB492A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CABCB492A76ED395 ON stockage (user_id)');
        $this->addSql('ALTER TABLE user ADD organizer_id INT NOT NULL, ADD artist_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649876C4DDA FOREIGN KEY (organizer_id) REFERENCES organizer (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649876C4DDA ON user (organizer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B7970CF8 ON user (artist_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_artist DROP FOREIGN KEY FK_33C0E1D571F7E88B');
        $this->addSql('ALTER TABLE event_artist DROP FOREIGN KEY FK_33C0E1D5B7970CF8');
        $this->addSql('ALTER TABLE event_organizer DROP FOREIGN KEY FK_1F414F4E71F7E88B');
        $this->addSql('ALTER TABLE event_organizer DROP FOREIGN KEY FK_1F414F4E876C4DDA');
        $this->addSql('ALTER TABLE organizer_artist DROP FOREIGN KEY FK_59A91261876C4DDA');
        $this->addSql('ALTER TABLE organizer_artist DROP FOREIGN KEY FK_59A91261B7970CF8');
        $this->addSql('DROP TABLE event_artist');
        $this->addSql('DROP TABLE event_organizer');
        $this->addSql('DROP TABLE organizer_artist');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7C54C8C93');
        $this->addSql('DROP INDEX IDX_3BAE0AA7C54C8C93 ON event');
        $this->addSql('ALTER TABLE event DROP type_id');
        $this->addSql('ALTER TABLE stockage DROP FOREIGN KEY FK_CABCB492A76ED395');
        $this->addSql('DROP INDEX IDX_CABCB492A76ED395 ON stockage');
        $this->addSql('ALTER TABLE stockage DROP user_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649876C4DDA');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B7970CF8');
        $this->addSql('DROP INDEX UNIQ_8D93D649876C4DDA ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649B7970CF8 ON user');
        $this->addSql('ALTER TABLE user DROP organizer_id, DROP artist_id');
    }
}
