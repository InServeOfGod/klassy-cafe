<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220709164513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, offer_menu_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date_arrived DATETIME NOT NULL, INDEX IDX_62534E216DDF2F1B (offer_menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, offer_menu_id INT DEFAULT NULL, count INT NOT NULL, INDEX IDX_B3BA5A5A6DDF2F1B (offer_menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profits (id INT AUTO_INCREMENT NOT NULL, profit DOUBLE PRECISION NOT NULL, loss DOUBLE PRECISION NOT NULL, money DOUBLE PRECISION NOT NULL, date_profit DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E216DDF2F1B FOREIGN KEY (offer_menu_id) REFERENCES offers_menus (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A6DDF2F1B FOREIGN KEY (offer_menu_id) REFERENCES offers_menus (id)');
        $this->addSql('ALTER TABLE offers_menus ADD cost DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE profits');
        $this->addSql('ALTER TABLE offers_menus DROP cost');
    }
}
