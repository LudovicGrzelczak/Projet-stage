<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220421100037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE servers (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, jeu_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_4F8AF5F79D86650F (user_id_id), INDEX IDX_4F8AF5F74DA19DAF (jeu_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE servers ADD CONSTRAINT FK_4F8AF5F79D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE servers ADD CONSTRAINT FK_4F8AF5F74DA19DAF FOREIGN KEY (jeu_id_id) REFERENCES jeux (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE servers');
    }
}
