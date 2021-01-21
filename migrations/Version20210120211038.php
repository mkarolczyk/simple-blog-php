<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120211038 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blogPost (title VARCHAR(80) NOT NULL, content CLOB NOT NULL, imageFilename VARCHAR(80) NOT NULL, version INTEGER DEFAULT 1 NOT NULL, postId CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(postId))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE blogPost');
    }
}
