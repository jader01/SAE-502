-- Création de la table Client
CREATE TABLE client (
    id_client INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    entreprise TEXT,
    projet_associé TEXT
);

-- Création de la table Développeur
CREATE TABLE Developpeur (
    id_dev INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    projet_associé TEXT,
    date_embauche DATE DEFAULT CURRENT_DATE
);

-- Création de la table Rapporteur (peut être un client ou un membre interne)
CREATE TABLE Rapporteur (
    id_rapporteur INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    projet_associé TEXT,
    date_embauche DATE DEFAULT CURRENT_DATE
);

-- Création de la table Ticket
CREATE TABLE Ticket (
    id_ticket INTEGER PRIMARY KEY AUTOINCREMENT,
    titre TEXT NOT NULL,
    description TEXT,
    statut TEXT CHECK(statut IN ('Ouvert', 'En cours', 'Résolu', 'Fermé')) DEFAULT 'Ouvert',
    priorite TEXT CHECK(priorite IN ('P1', 'P2', 'P3', 'P4')) DEFAULT 'P3',
    id_rapporteur INTEGER,
    id_dev INTEGER,
    id_client INTEGER,
    FOREIGN KEY (id_rapporteur) REFERENCES Rapporteur(id_rapporteur),
    FOREIGN KEY (id_dev) REFERENCES Developpeur(id_dev),
    FOREIGN KEY (id_client) REFERENCES client(id_dev),
);
