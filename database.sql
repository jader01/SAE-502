-- Table Client
CREATE TABLE client (
    id_client INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    entreprise TEXT,
    projet_associe TEXT
);
INSERT INTO client VALUES (1, 'test', 'test', '2508I2503');

-- Table Developpeur (nous)
CREATE TABLE developpeur (
    id_dev INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    projet_associe TEXT
);
INSERT INTO developpeur VALUES (1, 'thomas', '2508I2503');

-- Table Rapporteur 
CREATE TABLE rapporteur (
    id_rapporteur INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    projet_associe TEXT
);
INSERT INTO rapporteur VALUES (1, 'jade', '2508I2503');

-- Table Ticket 
CREATE TABLE ticket (
    id_ticket INTEGER PRIMARY KEY AUTOINCREMENT,
    titre TEXT NOT NULL,
    description TEXT,
    statut TEXT CHECK(statut IN ('Ouvert', 'En cours', 'Resolu', 'Ferme')) DEFAULT 'Ouvert',
    priorite TEXT CHECK(priorite IN ('P1', 'P2', 'P3', 'P4')) DEFAULT 'P3',
    id_rapporteur INTEGER,
    id_dev INTEGER,
    id_client INTEGER,
    FOREIGN KEY (id_rapporteur) REFERENCES rapporteur(id_rapporteur),
    FOREIGN KEY (id_dev) REFERENCES developpeur(id_dev),
    FOREIGN KEY (id_client) REFERENCES client(id_client)
);
