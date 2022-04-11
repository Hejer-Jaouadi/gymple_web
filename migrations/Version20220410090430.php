<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410090430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD id_p INT AUTO_INCREMENT NOT NULL, DROP id, CHANGE name name VARCHAR(50) DEFAULT NULL, CHANGE quantity quantity INT DEFAULT NULL, CHANGE description description VARCHAR(200) DEFAULT NULL, CHANGE category category VARCHAR(255) DEFAULT NULL, CHANGE price price DOUBLE PRECISION DEFAULT NULL, ADD PRIMARY KEY (id_p)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products MODIFY id_p INT NOT NULL');
        $this->addSql('ALTER TABLE products DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE products ADD id INT NOT NULL, DROP id_p, CHANGE name name VARCHAR(255) NOT NULL, CHANGE quantity quantity INT NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE category category VARCHAR(30) NOT NULL, CHANGE price price DOUBLE PRECISION NOT NULL');
    }
}
