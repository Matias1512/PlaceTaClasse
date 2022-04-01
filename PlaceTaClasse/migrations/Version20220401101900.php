<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220401101900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE controle_placement (controle_id INT NOT NULL, placement_id INT NOT NULL, INDEX IDX_8246A552758926A6 (controle_id), INDEX IDX_8246A5522F966E9D (placement_id), PRIMARY KEY(controle_id, placement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE controle_placement ADD CONSTRAINT FK_8246A552758926A6 FOREIGN KEY (controle_id) REFERENCES controle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE controle_placement ADD CONSTRAINT FK_8246A5522F966E9D FOREIGN KEY (placement_id) REFERENCES placement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE controle DROP FOREIGN KEY FK_E39396E69E0C4B2');
        $this->addSql('DROP INDEX IDX_E39396E69E0C4B2 ON controle');
        $this->addSql('ALTER TABLE controle DROP placements_id');
        $this->addSql('ALTER TABLE placement DROP FOREIGN KEY FK_48DB750E758926A6');
        $this->addSql('DROP INDEX IDX_48DB750E758926A6 ON placement');
        $this->addSql('ALTER TABLE placement DROP controle_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE controle_placement');
        $this->addSql('ALTER TABLE controle ADD placements_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE controle ADD CONSTRAINT FK_E39396E69E0C4B2 FOREIGN KEY (placements_id) REFERENCES placement (id)');
        $this->addSql('CREATE INDEX IDX_E39396E69E0C4B2 ON controle (placements_id)');
        $this->addSql('ALTER TABLE placement ADD controle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE placement ADD CONSTRAINT FK_48DB750E758926A6 FOREIGN KEY (controle_id) REFERENCES controle (id)');
        $this->addSql('CREATE INDEX IDX_48DB750E758926A6 ON placement (controle_id)');
    }
}
