<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407230058 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_team_promo (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, team_promo_id INT DEFAULT NULL, INDEX IDX_D0721557A76ED395 (user_id), INDEX IDX_D07215576882A681 (team_promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE allsession (id INT AUTO_INCREMENT NOT NULL, structure_id INT DEFAULT NULL, date VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, teams JSON DEFAULT NULL, concerner VARCHAR(255) NOT NULL, lesteams JSON NOT NULL, INDEX IDX_AD94CAD62534008B (structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, evaluer_id INT DEFAULT NULL, evaluateur_id INT DEFAULT NULL, session_id INT DEFAULT NULL, perseverance VARCHAR(255) NOT NULL, confiance VARCHAR(255) NOT NULL, collaboration VARCHAR(255) NOT NULL, autonomie VARCHAR(255) NOT NULL, problemsolving VARCHAR(255) NOT NULL, transmission VARCHAR(255) NOT NULL, performance VARCHAR(255) NOT NULL, team VARCHAR(255) NOT NULL, INDEX IDX_1323A57555A18BD3 (evaluer_id), INDEX IDX_1323A575231F139 (evaluateur_id), INDEX IDX_1323A575613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, structure_id INT DEFAULT NULL, mentor_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, statut VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, poste VARCHAR(255) DEFAULT NULL, image VARCHAR(255) NOT NULL, nomtuteur VARCHAR(255) DEFAULT NULL, telephonetuteur VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D6492534008B (structure_id), INDEX IDX_8D93D649DB403044 (mentor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_promo (id INT AUTO_INCREMENT NOT NULL, structure_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_7715D3BB2534008B (structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historiquesession (id INT AUTO_INCREMENT NOT NULL, session_id INT DEFAULT NULL, team VARCHAR(255) NOT NULL, user VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_E035920B613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_team_promo ADD CONSTRAINT FK_D0721557A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_team_promo ADD CONSTRAINT FK_D07215576882A681 FOREIGN KEY (team_promo_id) REFERENCES team_promo (id)');
        $this->addSql('ALTER TABLE allsession ADD CONSTRAINT FK_AD94CAD62534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57555A18BD3 FOREIGN KEY (evaluer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575231F139 FOREIGN KEY (evaluateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575613FECDF FOREIGN KEY (session_id) REFERENCES allsession (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DB403044 FOREIGN KEY (mentor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE team_promo ADD CONSTRAINT FK_7715D3BB2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE historiquesession ADD CONSTRAINT FK_E035920B613FECDF FOREIGN KEY (session_id) REFERENCES allsession (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575613FECDF');
        $this->addSql('ALTER TABLE historiquesession DROP FOREIGN KEY FK_E035920B613FECDF');
        $this->addSql('ALTER TABLE user_team_promo DROP FOREIGN KEY FK_D0721557A76ED395');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57555A18BD3');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575231F139');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DB403044');
        $this->addSql('ALTER TABLE user_team_promo DROP FOREIGN KEY FK_D07215576882A681');
        $this->addSql('ALTER TABLE allsession DROP FOREIGN KEY FK_AD94CAD62534008B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492534008B');
        $this->addSql('ALTER TABLE team_promo DROP FOREIGN KEY FK_7715D3BB2534008B');
        $this->addSql('DROP TABLE user_team_promo');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE allsession');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE team_promo');
        $this->addSql('DROP TABLE structure');
        $this->addSql('DROP TABLE historiquesession');
    }
}
