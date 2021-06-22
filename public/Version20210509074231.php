<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509074231 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coin (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, client VARCHAR(255) NOT NULL, contact VARCHAR(255) NOT NULL, total INT NOT NULL, order_number VARCHAR(255) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product (id INT AUTO_INCREMENT NOT NULL, from_order_id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, quantity INT NOT NULL, INDEX IDX_2530ADE6CB708DA2 (from_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_via (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payku_plan (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, payku_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, menu_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price INT NOT NULL, enabled TINYINT(1) NOT NULL, category VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_D34A04ADCCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suscription (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, plan_id INT NOT NULL, payku_id VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_CD871086A76ED395 (user_id), INDEX IDX_CD871086E899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, user_coin_id INT NOT NULL, order_via_id INT NOT NULL, category_id INT NOT NULL, menu_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, brand_name VARCHAR(255) NOT NULL, opening VARCHAR(255) NOT NULL, payment_instructions VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, open TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, image VARCHAR(255) DEFAULT NULL, payku_id VARCHAR(255) NOT NULL, phone_number INT NOT NULL, min_delivery INT DEFAULT 0 NOT NULL, delivery_charge INT DEFAULT 0 NOT NULL, INDEX IDX_8D93D6495046A3AD (user_coin_id), INDEX IDX_8D93D649218EC6D2 (order_via_id), INDEX IDX_8D93D64912469DE2 (category_id), UNIQUE INDEX UNIQ_8D93D649CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE6CB708DA2 FOREIGN KEY (from_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE suscription ADD CONSTRAINT FK_CD871086A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE suscription ADD CONSTRAINT FK_CD871086E899029B FOREIGN KEY (plan_id) REFERENCES payku_plan (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6495046A3AD FOREIGN KEY (user_coin_id) REFERENCES coin (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649218EC6D2 FOREIGN KEY (order_via_id) REFERENCES order_via (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');


        $this->addSql('INSERT INTO coin (id, name, description) VALUES (1, "$", "CLP")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (1, "Gastronomia", "Comidas y bebidas")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (2, "Fashion accessories", "Fashion accessories")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (3, "Fitness &amp; sports", "Fitness &amp; sports")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (4, "Flowers / greetings and gifts", "Flowers / greetings and gifts")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (5, "Food &amp; beverages", "Food &amp; beverages")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (6, "Gadgets and accessories", "Gadgets and accessories")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (7, "Hawker center stall", "Hawker center stall")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (8, "Health and wellness", "Health and wellness")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (9, "Home decor", "Home decor")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (10, "Home goods / appliances and utility", "Home goods / appliances and utility")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (11, "Hotels and Hostels", "Hotels and Hostels")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (12, "Ice-cream parlour", "Ice-cream parlour")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (13, "Jewelry and watches", "Jewelry and watches")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (14, "Life hack products", "Life hack products")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (15, "Marketing agency", "Marketing agency")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (16, "Pet products", "Pet products")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (17, "Pop-up event stall", "Pop-up event stall")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (18, "Pre order and catering", "Pre order and catering")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (19, "Restaurant", "Restaurant")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (20, "Street food cart", "Street food cart")');
        $this->addSql('INSERT INTO category (id, name, description) VALUES (21, "Takeaway", "Takeaway")');
        $this->addSql('INSERT INTO order_via (id, name) VALUES (1, "whatsapp")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64912469DE2');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6495046A3AD');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADCCD7E912');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649CCD7E912');
        $this->addSql('ALTER TABLE order_product DROP FOREIGN KEY FK_2530ADE6CB708DA2');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649218EC6D2');
        $this->addSql('ALTER TABLE suscription DROP FOREIGN KEY FK_CD871086E899029B');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE suscription DROP FOREIGN KEY FK_CD871086A76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE coin');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('DROP TABLE order_via');
        $this->addSql('DROP TABLE payku_plan');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE suscription');
        $this->addSql('DROP TABLE `user`');
    }
}
