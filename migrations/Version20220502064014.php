<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220502064014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE servers ADD duration_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE servers ADD CONSTRAINT FK_4F8AF5F723A4A42B FOREIGN KEY (duration_id_id) REFERENCES duration (id)');
        $this->addSql('CREATE INDEX IDX_4F8AF5F723A4A42B ON servers (duration_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE servers DROP FOREIGN KEY FK_4F8AF5F723A4A42B');
        $this->addSql('DROP INDEX IDX_4F8AF5F723A4A42B ON servers');
        $this->addSql('ALTER TABLE servers DROP duration_id_id');
    }
}
