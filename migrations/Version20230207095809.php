<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207095809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decortiqueur ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE decortiqueur ADD CONSTRAINT FK_2BAD6B9AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2BAD6B9AA76ED395 ON decortiqueur (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B8AB9504');
        $this->addSql('DROP INDEX UNIQ_8D93D649B8AB9504 ON user');
        $this->addSql('ALTER TABLE user DROP decortiqueur_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decortiqueur DROP FOREIGN KEY FK_2BAD6B9AA76ED395');
        $this->addSql('DROP INDEX UNIQ_2BAD6B9AA76ED395 ON decortiqueur');
        $this->addSql('ALTER TABLE decortiqueur DROP user_id');
        $this->addSql('ALTER TABLE `user` ADD decortiqueur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649B8AB9504 FOREIGN KEY (decortiqueur_id) REFERENCES decortiqueur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649B8AB9504 ON `user` (decortiqueur_id)');
    }
}
