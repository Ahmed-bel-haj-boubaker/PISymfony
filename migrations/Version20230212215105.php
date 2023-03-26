<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212215105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hebergement ADD category_hebergement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9CC2AB4C5B FOREIGN KEY (category_hebergement_id) REFERENCES category_hebergement (id)');
        $this->addSql('CREATE INDEX IDX_4852DD9CC2AB4C5B ON hebergement (category_hebergement_id)');
        $this->addSql('ALTER TABLE transport ADD category_transport_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transport ADD CONSTRAINT FK_66AB212E43DEE2AD FOREIGN KEY (category_transport_id) REFERENCES category_transport (id)');
        $this->addSql('CREATE INDEX IDX_66AB212E43DEE2AD ON transport (category_transport_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9CC2AB4C5B');
        $this->addSql('DROP INDEX IDX_4852DD9CC2AB4C5B ON hebergement');
        $this->addSql('ALTER TABLE hebergement DROP category_hebergement_id');
        $this->addSql('ALTER TABLE transport DROP FOREIGN KEY FK_66AB212E43DEE2AD');
        $this->addSql('DROP INDEX IDX_66AB212E43DEE2AD ON transport');
        $this->addSql('ALTER TABLE transport DROP category_transport_id');
    }
}
