<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230212210853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE billet (id INT AUTO_INCREMENT NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE match_f (id INT AUTO_INCREMENT NOT NULL, heure_deb_m TIME NOT NULL, heure_fin_m TIME NOT NULL, date_match DATE NOT NULL, equipe_a VARCHAR(255) NOT NULL, equipe_b VARCHAR(255) NOT NULL, type_match VARCHAR(255) NOT NULL, stade VARCHAR(255) NOT NULL, tournois VARCHAR(255) NOT NULL, resultat_a INT NOT NULL, resultat_b INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE match_f_reservation (match_f_id INT NOT NULL, reservation_id INT NOT NULL, INDEX IDX_BC9DA35CC766DA93 (match_f_id), INDEX IDX_BC9DA35CB83297E7 (reservation_id), PRIMARY KEY(match_f_id, reservation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, date_resevation DATETIME NOT NULL, etat TINYINT(1) NOT NULL, nombre_billet INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE match_f_reservation ADD CONSTRAINT FK_BC9DA35CC766DA93 FOREIGN KEY (match_f_id) REFERENCES match_f (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE match_f_reservation ADD CONSTRAINT FK_BC9DA35CB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE match_f_reservation DROP FOREIGN KEY FK_BC9DA35CC766DA93');
        $this->addSql('ALTER TABLE match_f_reservation DROP FOREIGN KEY FK_BC9DA35CB83297E7');
        $this->addSql('DROP TABLE billet');
        $this->addSql('DROP TABLE match_f');
        $this->addSql('DROP TABLE match_f_reservation');
        $this->addSql('DROP TABLE reservation');
    }
}
