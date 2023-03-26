<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212214742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hebergement ADD localisation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9CC68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id)');
        $this->addSql('CREATE INDEX IDX_4852DD9CC68BE09C ON hebergement (localisation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9CC68BE09C');
        $this->addSql('DROP INDEX IDX_4852DD9CC68BE09C ON hebergement');
        $this->addSql('ALTER TABLE hebergement DROP localisation_id');
    }
}
