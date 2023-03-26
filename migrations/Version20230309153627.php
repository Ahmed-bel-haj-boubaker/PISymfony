<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309153627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, classement INT NOT NULL, points INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stade (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournoi (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_match (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE match_f_reservation DROP FOREIGN KEY FK_BC9DA35CB83297E7');
        $this->addSql('ALTER TABLE match_f_reservation DROP FOREIGN KEY FK_BC9DA35CC766DA93');
        $this->addSql('DROP TABLE category_prod');
        $this->addSql('DROP TABLE match_f_reservation');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE billet ADD reservation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT FK_1F034AF6B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_1F034AF6B83297E7 ON billet (reservation_id)');
        $this->addSql('ALTER TABLE match_f ADD equipe_a_id INT DEFAULT NULL, ADD equipe_b_id INT DEFAULT NULL, ADD stade_id INT DEFAULT NULL, ADD tournoi_id INT DEFAULT NULL, ADD type_match_id INT DEFAULT NULL, ADD prix INT NOT NULL, ADD nb_billet_total INT DEFAULT NULL, ADD nb_billet_reserve INT DEFAULT NULL, ADD heurefin_m TIME DEFAULT NULL, ADD image2 VARCHAR(255) NOT NULL, DROP heure_fin_m, DROP equipe_a, DROP equipe_b, DROP type_match, DROP stade, DROP tournois, CHANGE heure_deb_m heure_deb_m TIME DEFAULT NULL, CHANGE date_match date_match DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE match_f ADD CONSTRAINT FK_4E522F283297C2A6 FOREIGN KEY (equipe_a_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE match_f ADD CONSTRAINT FK_4E522F2820226D48 FOREIGN KEY (equipe_b_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE match_f ADD CONSTRAINT FK_4E522F286538AB43 FOREIGN KEY (stade_id) REFERENCES stade (id)');
        $this->addSql('ALTER TABLE match_f ADD CONSTRAINT FK_4E522F28F607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE match_f ADD CONSTRAINT FK_4E522F28E7418B2A FOREIGN KEY (type_match_id) REFERENCES type_match (id)');
        $this->addSql('CREATE INDEX IDX_4E522F283297C2A6 ON match_f (equipe_a_id)');
        $this->addSql('CREATE INDEX IDX_4E522F2820226D48 ON match_f (equipe_b_id)');
        $this->addSql('CREATE INDEX IDX_4E522F286538AB43 ON match_f (stade_id)');
        $this->addSql('CREATE INDEX IDX_4E522F28F607770A ON match_f (tournoi_id)');
        $this->addSql('CREATE INDEX IDX_4E522F28E7418B2A ON match_f (type_match_id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495544973C78');
        $this->addSql('DROP INDEX IDX_42C8495544973C78 ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE etat etat VARCHAR(255) NOT NULL, CHANGE billet_id match_f_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955C766DA93 FOREIGN KEY (match_f_id) REFERENCES match_f (id)');
        $this->addSql('CREATE INDEX IDX_42C84955C766DA93 ON reservation (match_f_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE match_f DROP FOREIGN KEY FK_4E522F283297C2A6');
        $this->addSql('ALTER TABLE match_f DROP FOREIGN KEY FK_4E522F2820226D48');
        $this->addSql('ALTER TABLE match_f DROP FOREIGN KEY FK_4E522F286538AB43');
        $this->addSql('ALTER TABLE match_f DROP FOREIGN KEY FK_4E522F28F607770A');
        $this->addSql('ALTER TABLE match_f DROP FOREIGN KEY FK_4E522F28E7418B2A');
        $this->addSql('CREATE TABLE category_prod (id INT AUTO_INCREMENT NOT NULL, cat_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE match_f_reservation (match_f_id INT NOT NULL, reservation_id INT NOT NULL, INDEX IDX_BC9DA35CC766DA93 (match_f_id), INDEX IDX_BC9DA35CB83297E7 (reservation_id), PRIMARY KEY(match_f_id, reservation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, headers LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, queue_name VARCHAR(190) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, price DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, size VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, qte INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, enable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE match_f_reservation ADD CONSTRAINT FK_BC9DA35CB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE match_f_reservation ADD CONSTRAINT FK_BC9DA35CC766DA93 FOREIGN KEY (match_f_id) REFERENCES match_f (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE stade');
        $this->addSql('DROP TABLE tournoi');
        $this->addSql('DROP TABLE type_match');
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY FK_1F034AF6B83297E7');
        $this->addSql('DROP INDEX IDX_1F034AF6B83297E7 ON billet');
        $this->addSql('ALTER TABLE billet DROP reservation_id');
        $this->addSql('DROP INDEX IDX_4E522F283297C2A6 ON match_f');
        $this->addSql('DROP INDEX IDX_4E522F2820226D48 ON match_f');
        $this->addSql('DROP INDEX IDX_4E522F286538AB43 ON match_f');
        $this->addSql('DROP INDEX IDX_4E522F28F607770A ON match_f');
        $this->addSql('DROP INDEX IDX_4E522F28E7418B2A ON match_f');
        $this->addSql('ALTER TABLE match_f ADD heure_fin_m TIME NOT NULL, ADD equipe_b VARCHAR(255) NOT NULL, ADD type_match VARCHAR(255) NOT NULL, ADD stade VARCHAR(255) NOT NULL, ADD tournois VARCHAR(255) NOT NULL, DROP equipe_a_id, DROP equipe_b_id, DROP stade_id, DROP tournoi_id, DROP type_match_id, DROP prix, DROP nb_billet_total, DROP nb_billet_reserve, DROP heurefin_m, CHANGE date_match date_match DATE NOT NULL, CHANGE heure_deb_m heure_deb_m TIME NOT NULL, CHANGE image2 equipe_a VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955C766DA93');
        $this->addSql('DROP INDEX IDX_42C84955C766DA93 ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE etat etat TINYINT(1) NOT NULL, CHANGE match_f_id billet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495544973C78 FOREIGN KEY (billet_id) REFERENCES billet (id)');
        $this->addSql('CREATE INDEX IDX_42C8495544973C78 ON reservation (billet_id)');
    }
}
