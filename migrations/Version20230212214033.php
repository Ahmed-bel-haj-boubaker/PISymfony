<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212214033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transport_localisation (transport_id INT NOT NULL, localisation_id INT NOT NULL, INDEX IDX_212135CF9909C13F (transport_id), INDEX IDX_212135CFC68BE09C (localisation_id), PRIMARY KEY(transport_id, localisation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transport_localisation ADD CONSTRAINT FK_212135CF9909C13F FOREIGN KEY (transport_id) REFERENCES transport (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transport_localisation ADD CONSTRAINT FK_212135CFC68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transport_localisation DROP FOREIGN KEY FK_212135CF9909C13F');
        $this->addSql('ALTER TABLE transport_localisation DROP FOREIGN KEY FK_212135CFC68BE09C');
        $this->addSql('DROP TABLE transport_localisation');
    }
}
