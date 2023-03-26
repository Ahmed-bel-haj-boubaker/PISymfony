<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216172122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category_prod');
        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL, ADD second_name VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE phone phone INT NOT NULL, CHANGE date_join date_join DATETIME NOT NULL, CHANGE banned banned TINYINT(1) NOT NULL, CHANGE is_verified is_verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_prod (id INT AUTO_INCREMENT NOT NULL, cat_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, price DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, size VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, qte INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, enable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user DROP first_name, DROP second_name, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE phone phone INT DEFAULT NULL, CHANGE date_join date_join DATETIME DEFAULT NULL, CHANGE banned banned TINYINT(1) DEFAULT NULL, CHANGE is_verified is_verified TINYINT(1) DEFAULT NULL');
    }
}
