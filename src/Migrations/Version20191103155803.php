<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191103155803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, order_datetime DATETIME NOT NULL, customer_id INT NOT NULL, customer_fname VARCHAR(100) NOT NULL, customer_lname VARCHAR(100) DEFAULT NULL, customer_email VARCHAR(100) DEFAULT NULL, customer_phone VARCHAR(20) NOT NULL, customer_street LONGTEXT NOT NULL, customer_postcode VARCHAR(9) DEFAULT NULL, customer_suburb VARCHAR(100) NOT NULL, customer_state VARCHAR(100) NOT NULL, longitude VARCHAR(30) DEFAULT NULL, latitude VARCHAR(30) DEFAULT NULL, distinct_unit_count INT NOT NULL, total_units_count INT NOT NULL, subtotal DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, total_order_value DOUBLE PRECISION NOT NULL, average_unit_price DOUBLE PRECISION NOT NULL, shipping_fee DOUBLE PRECISION DEFAULT NULL, grand_total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE orders');
    }
}
