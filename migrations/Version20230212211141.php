<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212211141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD billet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495544973C78 FOREIGN KEY (billet_id) REFERENCES billet (id)');
        $this->addSql('CREATE INDEX IDX_42C8495544973C78 ON reservation (billet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495544973C78');
        $this->addSql('DROP INDEX IDX_42C8495544973C78 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP billet_id');
    }
}
