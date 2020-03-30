<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200327195934 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE team_promo (id INT AUTO_INCREMENT NOT NULL, structure_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_7715D3BB2534008B (structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_team_promo (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, team_promo_id INT DEFAULT NULL, INDEX IDX_D0721557A76ED395 (user_id), INDEX IDX_D07215576882A681 (team_promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, statut VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) NOT NULL, poste VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE team_promo ADD CONSTRAINT FK_7715D3BB2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE user_team_promo ADD CONSTRAINT FK_D0721557A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_team_promo ADD CONSTRAINT FK_D07215576882A681 FOREIGN KEY (team_promo_id) REFERENCES team_promo (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_team_promo DROP FOREIGN KEY FK_D07215576882A681');
        $this->addSql('ALTER TABLE team_promo DROP FOREIGN KEY FK_7715D3BB2534008B');
        $this->addSql('ALTER TABLE user_team_promo DROP FOREIGN KEY FK_D0721557A76ED395');
        $this->addSql('DROP TABLE team_promo');
        $this->addSql('DROP TABLE structure');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE user_team_promo');
        $this->addSql('DROP TABLE user');
    }
}
