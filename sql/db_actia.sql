CREATE TABLE usertype(
   id_usertype INT,
   lib_usertype VARCHAR(50) ,
   description TEXT,
   PRIMARY KEY(id_usertype)
);

CREATE TABLE produit(
   code_ax VARCHAR(50) ,
   code_movex VARCHAR(50) ,
   designation_produit VARCHAR(50) ,
   reference_commerciale VARCHAR(50) ,
   stock_secu_attendu INT,
   stock_secu_reel INT,
   commentaire_stock VARCHAR(255) ,
   PRIMARY KEY(code_ax)
);

CREATE TABLE production(
   idproduction INT,
   date_production DATE,
   qte_production INT,
   code_ax VARCHAR(50)  NOT NULL,
   PRIMARY KEY(idproduction),
   FOREIGN KEY(code_ax) REFERENCES produit(code_ax)
);

CREATE TABLE engagement(
   idengagement INT,
   date_engagement DATE,
   qte_engagement INT,
   code_ax VARCHAR(50)  NOT NULL,
   PRIMARY KEY(idengagement),
   FOREIGN KEY(code_ax) REFERENCES produit(code_ax)
);

CREATE TABLE utilisateur(
   id_utilisateur INT,
   login VARCHAR(50)  NOT NULL,
   mdp VARCHAR(255)  NOT NULL,
   nom VARCHAR(50) ,
   prenom VARCHAR(50) ,
   unite_production VARCHAR(50) ,
   secteur VARCHAR(50) ,
   nom_prenom_manager VARCHAR(50) ,
   type_emploi VARCHAR(50) ,
   type_contrat VARCHAR(50) ,
   type_equipe VARCHAR(50) ,
   statut VARCHAR(50) ,
   id_usertype INT NOT NULL,
   PRIMARY KEY(id_utilisateur),
   FOREIGN KEY(id_usertype) REFERENCES usertype(id_usertype)
);

CREATE TABLE absence(
   id_absence INT,
   type_absence VARCHAR(50) ,
   date_absence DATE,
   lib_absence VARCHAR(50) ,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_absence),
   FOREIGN KEY(id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
);
