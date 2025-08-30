<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250724083358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY fk_product');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY fk_user_livraison');
        $this->addSql('ALTER TABLE livraison_produit DROP FOREIGN KEY fk_livraison');
        $this->addSql('ALTER TABLE livraison_produit DROP FOREIGN KEY fk_produit');
        $this->addSql('ALTER TABLE prélèvement DROP FOREIGN KEY fk_product_prélèvement');
        $this->addSql('ALTER TABLE prélèvement DROP FOREIGN KEY fk_user_prélèvement');
        $this->addSql('ALTER TABLE prélèvement_produit DROP FOREIGN KEY fk_prélèvement');
        $this->addSql('ALTER TABLE prélèvement_produit DROP FOREIGN KEY fk_produit_prélèvement');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE livraison_produit');
        $this->addSql('DROP TABLE prélèvement');
        $this->addSql('DROP TABLE prélèvement_produit');
        $this->addSql('ALTER TABLE familles CHANGE photo_famille photo_famille VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY fk_emplacement');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY fk_famille');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY fk_user');
        $this->addSql('DROP INDEX fk_user ON produits');
        $this->addSql('ALTER TABLE produits CHANGE photo_product photo_product VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CC4598A51 FOREIGN KEY (emplacement_id) REFERENCES emplacement (id_emplacement)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C97A77B84 FOREIGN KEY (famille_id) REFERENCES familles (id_famille)');
        $this->addSql('ALTER TABLE produits RENAME INDEX fk_emplacement TO IDX_BE2DDF8CC4598A51');
        $this->addSql('ALTER TABLE produits RENAME INDEX fk_famille TO IDX_BE2DDF8C97A77B84');
        $this->addSql('ALTER TABLE utilisateurs ADD roles JSON NOT NULL, DROP role, CHANGE username username VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497B315EF85E0677 ON utilisateurs (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497B315EE7927C74 ON utilisateurs (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livraison (id_livraison INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, user_id INT NOT NULL, quantity INT NOT NULL, entry_date DATETIME NOT NULL, INDEX fk_product (product_id), INDEX fk_user_livraison (user_id), PRIMARY KEY(id_livraison)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE livraison_produit (id_livraison_produit INT AUTO_INCREMENT NOT NULL, livraison_id INT NOT NULL, produit_id INT NOT NULL, quantite INT NOT NULL, INDEX fk_livraison (livraison_id), INDEX fk_produit (produit_id), PRIMARY KEY(id_livraison_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prélèvement (id_prélèvement INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, request_date DATETIME NOT NULL, INDEX fk_user_prélèvement (user_id), INDEX fk_product_prélèvement (product_id), PRIMARY KEY(id_prélèvement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prélèvement_produit (id_prélèvement_produit INT AUTO_INCREMENT NOT NULL, prélèvement_id INT NOT NULL, produit_id INT NOT NULL, quantite INT NOT NULL, INDEX fk_prélèvement (prélèvement_id), INDEX fk_produit_prélèvement (produit_id), PRIMARY KEY(id_prélèvement_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT fk_product FOREIGN KEY (product_id) REFERENCES produits (id_produit) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT fk_user_livraison FOREIGN KEY (user_id) REFERENCES utilisateurs (id_utilisateur) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison_produit ADD CONSTRAINT fk_livraison FOREIGN KEY (livraison_id) REFERENCES livraison (id_livraison) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison_produit ADD CONSTRAINT fk_produit FOREIGN KEY (produit_id) REFERENCES produits (id_produit) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prélèvement ADD CONSTRAINT fk_product_prélèvement FOREIGN KEY (product_id) REFERENCES produits (id_produit) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prélèvement ADD CONSTRAINT fk_user_prélèvement FOREIGN KEY (user_id) REFERENCES utilisateurs (id_utilisateur) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prélèvement_produit ADD CONSTRAINT fk_prélèvement FOREIGN KEY (prélèvement_id) REFERENCES prélèvement (id_prélèvement) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prélèvement_produit ADD CONSTRAINT fk_produit_prélèvement FOREIGN KEY (produit_id) REFERENCES produits (id_produit) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE familles CHANGE photo_famille photo_famille VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CC4598A51');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C97A77B84');
        $this->addSql('ALTER TABLE produits CHANGE photo_product photo_product VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT fk_emplacement FOREIGN KEY (emplacement_id) REFERENCES emplacement (id_emplacement) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT fk_famille FOREIGN KEY (famille_id) REFERENCES familles (id_famille) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES utilisateurs (id_utilisateur) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX fk_user ON produits (user_id)');
        $this->addSql('ALTER TABLE produits RENAME INDEX idx_be2ddf8cc4598a51 TO fk_emplacement');
        $this->addSql('ALTER TABLE produits RENAME INDEX idx_be2ddf8c97a77b84 TO fk_famille');
        $this->addSql('DROP INDEX UNIQ_497B315EF85E0677 ON utilisateurs');
        $this->addSql('DROP INDEX UNIQ_497B315EE7927C74 ON utilisateurs');
        $this->addSql('ALTER TABLE utilisateurs ADD role VARCHAR(255) NOT NULL, DROP roles, CHANGE username username VARCHAR(255) NOT NULL');
    }
}
