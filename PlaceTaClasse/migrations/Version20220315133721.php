<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220315133721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE controle (id INT AUTO_INCREMENT NOT NULL, referent_id INT DEFAULT NULL, module_id INT DEFAULT NULL, horaire_ttdebut VARCHAR(255) NOT NULL, horaire_ttfin VARCHAR(255) NOT NULL, horaire_non_ttdebut VARCHAR(255) NOT NULL, horaire_non_ttfin VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_E39396E35E47E35 (referent_id), INDEX IDX_E39396EAFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE controle_placement (controle_id INT NOT NULL, placement_id INT NOT NULL, INDEX IDX_8246A552758926A6 (controle_id), INDEX IDX_8246A5522F966E9D (placement_id), PRIMARY KEY(controle_id, placement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE controle_enseignant (controle_id INT NOT NULL, enseignant_id INT NOT NULL, INDEX IDX_E3B0AFAE758926A6 (controle_id), INDEX IDX_E3B0AFAEE455FCC0 (enseignant_id), PRIMARY KEY(controle_id, enseignant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE controle_promotion (controle_id INT NOT NULL, promotion_id INT NOT NULL, INDEX IDX_B80AD8D758926A6 (controle_id), INDEX IDX_B80AD8D139DF194 (promotion_id), PRIMARY KEY(controle_id, promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enseignant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, vacataire TINYINT(1) NOT NULL, mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, promotion_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, tp INT NOT NULL, tier_temps TINYINT(1) NOT NULL, ordinateur TINYINT(1) NOT NULL, mail VARCHAR(255) NOT NULL, INDEX IDX_717E22E3139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, nom_long VARCHAR(255) NOT NULL, nom_court VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_enseignant (module_id INT NOT NULL, enseignant_id INT NOT NULL, INDEX IDX_1442043CAFC2B591 (module_id), INDEX IDX_1442043CE455FCC0 (enseignant_id), PRIMARY KEY(module_id, enseignant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_promotion (module_id INT NOT NULL, promotion_id INT NOT NULL, INDEX IDX_9B7C6219AFC2B591 (module_id), INDEX IDX_9B7C6219139DF194 (promotion_id), PRIMARY KEY(module_id, promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, salle_id INT NOT NULL, numero INT NOT NULL, prise TINYINT(1) NOT NULL, INDEX IDX_741D53CDDC304035 (salle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE placement (id INT AUTO_INCREMENT NOT NULL, etudiant_id INT DEFAULT NULL, place_id INT DEFAULT NULL, INDEX IDX_48DB750EDDEAB1A3 (etudiant_id), INDEX IDX_48DB750EDA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, nom_long VARCHAR(255) NOT NULL, nom_court VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, plan VARCHAR(255) NOT NULL, amphi TINYINT(1) NOT NULL, emplacement_prise VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, nb_place INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE controle ADD CONSTRAINT FK_E39396E35E47E35 FOREIGN KEY (referent_id) REFERENCES enseignant (id)');
        $this->addSql('ALTER TABLE controle ADD CONSTRAINT FK_E39396EAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE controle_placement ADD CONSTRAINT FK_8246A552758926A6 FOREIGN KEY (controle_id) REFERENCES controle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE controle_placement ADD CONSTRAINT FK_8246A5522F966E9D FOREIGN KEY (placement_id) REFERENCES placement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE controle_enseignant ADD CONSTRAINT FK_E3B0AFAE758926A6 FOREIGN KEY (controle_id) REFERENCES controle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE controle_enseignant ADD CONSTRAINT FK_E3B0AFAEE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE controle_promotion ADD CONSTRAINT FK_B80AD8D758926A6 FOREIGN KEY (controle_id) REFERENCES controle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE controle_promotion ADD CONSTRAINT FK_B80AD8D139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE module_enseignant ADD CONSTRAINT FK_1442043CAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_enseignant ADD CONSTRAINT FK_1442043CE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_promotion ADD CONSTRAINT FK_9B7C6219AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_promotion ADD CONSTRAINT FK_9B7C6219139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CDDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE placement ADD CONSTRAINT FK_48DB750EDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE placement ADD CONSTRAINT FK_48DB750EDA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE controle_placement DROP FOREIGN KEY FK_8246A552758926A6');
        $this->addSql('ALTER TABLE controle_enseignant DROP FOREIGN KEY FK_E3B0AFAE758926A6');
        $this->addSql('ALTER TABLE controle_promotion DROP FOREIGN KEY FK_B80AD8D758926A6');
        $this->addSql('ALTER TABLE controle DROP FOREIGN KEY FK_E39396E35E47E35');
        $this->addSql('ALTER TABLE controle_enseignant DROP FOREIGN KEY FK_E3B0AFAEE455FCC0');
        $this->addSql('ALTER TABLE module_enseignant DROP FOREIGN KEY FK_1442043CE455FCC0');
        $this->addSql('ALTER TABLE placement DROP FOREIGN KEY FK_48DB750EDDEAB1A3');
        $this->addSql('ALTER TABLE controle DROP FOREIGN KEY FK_E39396EAFC2B591');
        $this->addSql('ALTER TABLE module_enseignant DROP FOREIGN KEY FK_1442043CAFC2B591');
        $this->addSql('ALTER TABLE module_promotion DROP FOREIGN KEY FK_9B7C6219AFC2B591');
        $this->addSql('ALTER TABLE placement DROP FOREIGN KEY FK_48DB750EDA6A219');
        $this->addSql('ALTER TABLE controle_placement DROP FOREIGN KEY FK_8246A5522F966E9D');
        $this->addSql('ALTER TABLE controle_promotion DROP FOREIGN KEY FK_B80AD8D139DF194');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3139DF194');
        $this->addSql('ALTER TABLE module_promotion DROP FOREIGN KEY FK_9B7C6219139DF194');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CDDC304035');
        $this->addSql('DROP TABLE controle');
        $this->addSql('DROP TABLE controle_placement');
        $this->addSql('DROP TABLE controle_enseignant');
        $this->addSql('DROP TABLE controle_promotion');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE module_enseignant');
        $this->addSql('DROP TABLE module_promotion');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE placement');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
