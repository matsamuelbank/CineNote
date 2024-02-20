CREATE TABLE utilisateur (
    uId INT PRIMARY KEY AUTO_INCREMENT,
    uNom VARCHAR(255),
    uPrenom VARCHAR(255),
    uPseudo VARCHAR(255),
    uCodePostal VARCHAR(10),
    uAdresse VARCHAR(255),
    uLogin VARCHAR(255),
    uMdp VARCHAR(255)
) ENGINE=InnoDB;


CREATE TABLE commentaire (
    coId INT PRIMARY KEY AUTO_INCREMENT,
    coText TEXT,
    coDateDePublication DATE,
    coIdFilm INT,
    coIdUtilisateur INT,
    FOREIGN KEY (coIdUtilisateur) REFERENCES utilisateur(uId)
) ENGINE=InnoDB;

CREATE TABLE notation (
    nId INT PRIMARY KEY AUTO_INCREMENT,
    nIdFilm INT,
    nIdUtilisateur INT,
    note FLOAT,
    moyenneNote FLOAT,
    dateNotation DATE,
    FOREIGN KEY (nIdUtilisateur) REFERENCES utilisateur(uId)
) ENGINE=InnoDB;


ALTER TABLE commentaire
ADD FOREIGN KEY (coIdUtilisateur) REFERENCES utilisateur(uId);

ALTER TABLE notation
ADD FOREIGN KEY (nIdUtilisateur) REFERENCES utilisateur(uId);
