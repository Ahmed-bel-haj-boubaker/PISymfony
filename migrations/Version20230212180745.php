<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212180745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_event DROP FOREIGN KEY FK_35A28D5071F7E88B');
        $this->addSql('DROP INDEX IDX_35A28D5071F7E88B ON type_event');
        $this->addSql('ALTER TABLE type_event DROP event_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_event ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_event ADD CONSTRAINT FK_35A28D5071F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_35A28D5071F7E88B ON type_event (event_id)');
    }
}
