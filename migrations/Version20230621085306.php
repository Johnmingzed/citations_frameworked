<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230621085306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auteurs CHANGE bio bio LONGTEXT DEFAULT NULL, CHANGE date_modif date_modif DATETIME NOT NULL');
        $this->addSql('DROP INDEX auteur ON auteurs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DD7D42A55AB140 ON auteurs (auteur)');
        $this->addSql('ALTER TABLE citations DROP FOREIGN KEY citations_ibfk_1');
        $this->addSql('DROP INDEX citation ON citations');
        $this->addSql('DROP INDEX auteurs_id ON citations');
        $this->addSql('ALTER TABLE citations CHANGE explication explication LONGTEXT DEFAULT NULL, CHANGE date_modif date_modif DATETIME NOT NULL, CHANGE auteur_id auteur_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE citations ADD CONSTRAINT FK_AC492EAC75F8742E FOREIGN KEY (auteur_id_id) REFERENCES auteurs (id)');
        $this->addSql('CREATE INDEX IDX_AC492EAC75F8742E ON citations (auteur_id_id)');
        $this->addSql('ALTER TABLE utilisateurs ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', CHANGE mot_de_passe mot_de_passe VARCHAR(255) NOT NULL, CHANGE date_modif date_modif DATETIME NOT NULL, CHANGE admin admin TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX mail ON utilisateurs');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497B315E5126AC48 ON utilisateurs (mail)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE auteurs CHANGE bio bio TEXT DEFAULT NULL, CHANGE date_modif date_modif DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('DROP INDEX uniq_6dd7d42a55ab140 ON auteurs');
        $this->addSql('CREATE UNIQUE INDEX auteur ON auteurs (auteur)');
        $this->addSql('ALTER TABLE citations DROP FOREIGN KEY FK_AC492EAC75F8742E');
        $this->addSql('DROP INDEX IDX_AC492EAC75F8742E ON citations');
        $this->addSql('ALTER TABLE citations CHANGE explication explication TEXT DEFAULT NULL, CHANGE date_modif date_modif DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE auteur_id_id auteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE citations ADD CONSTRAINT citations_ibfk_1 FOREIGN KEY (auteur_id) REFERENCES auteurs (id) ON UPDATE CASCADE ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX citation ON citations (citation)');
        $this->addSql('CREATE INDEX auteurs_id ON citations (auteur_id)');
        $this->addSql('ALTER TABLE utilisateurs DROP roles, CHANGE mot_de_passe mot_de_passe VARCHAR(255) DEFAULT NULL, CHANGE date_modif date_modif DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE admin admin TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('DROP INDEX uniq_497b315e5126ac48 ON utilisateurs');
        $this->addSql('CREATE UNIQUE INDEX mail ON utilisateurs (mail)');
    }
}
