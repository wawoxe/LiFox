<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220185011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute ADD class_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE attribute ADD is_required BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE attribute ADD is_unique BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE attribute DROP class_name');
        $this->addSql('ALTER TABLE attribute DROP is_required');
        $this->addSql('ALTER TABLE attribute DROP is_unique');
    }
}
