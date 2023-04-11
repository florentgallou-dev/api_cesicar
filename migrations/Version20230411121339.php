<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411121339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(150) NOT NULL, ADD last_name VARCHAR(150) NOT NULL, ADD gender VARCHAR(5) NOT NULL, ADD city VARCHAR(150) NOT NULL, ADD driver TINYINT(1) NOT NULL, ADD car_type VARCHAR(150), ADD car_registration VARCHAR(15), ADD car_nb_place VARCHAR(15)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP first_name, DROP last_name, DROP gender, DROP city, DROP driver, DROP car_type, DROP car_registration, DROP car_nb_place');
    }
}
