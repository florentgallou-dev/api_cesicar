<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411124149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D69D86650F');
        $this->addSql('DROP INDEX UNIQ_5E90F6D69D86650F ON inscription');
        $this->addSql('ALTER TABLE inscription ADD user_id INT NOT NULL, DROP user_id_id');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E90F6D6A76ED395 ON inscription (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6A76ED395');
        $this->addSql('DROP INDEX UNIQ_5E90F6D6A76ED395 ON inscription');
        $this->addSql('ALTER TABLE inscription ADD user_id_id INT DEFAULT NULL, DROP user_id');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D69D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E90F6D69D86650F ON inscription (user_id_id)');
    }
}
