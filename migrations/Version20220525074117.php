<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220525074117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shootarounds ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shootarounds ADD CONSTRAINT FK_EF465F52A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_EF465F52A76ED395 ON shootarounds (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shootarounds DROP FOREIGN KEY FK_EF465F52A76ED395');
        $this->addSql('DROP INDEX IDX_EF465F52A76ED395 ON shootarounds');
        $this->addSql('ALTER TABLE shootarounds DROP user_id');
    }
}
