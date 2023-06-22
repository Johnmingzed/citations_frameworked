<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230622082409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE citations DROP FOREIGN KEY FK_AC492EAC75F8742E');
        $this->addSql('ALTER TABLE citations ADD CONSTRAINT FK_AC492EAC75F8742E FOREIGN KEY (auteur_id_id) REFERENCES auteurs (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE citations DROP FOREIGN KEY FK_AC492EAC75F8742E');
        $this->addSql('ALTER TABLE citations ADD CONSTRAINT FK_AC492EAC75F8742E FOREIGN KEY (auteur_id_id) REFERENCES auteurs (id)');
    }
}
