BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "client" (
	"id_client"	INTEGER UNIQUE,
	"nom"	TEXT NOT NULL,
	"ticket"	TEXT,
	"entreprise"	TEXT,
	PRIMARY KEY("id_client" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "developpeur" (
	"id_dev"	INTEGER,
	"nom"	TEXT NOT NULL,
	"ticket"	TEXT,
	PRIMARY KEY("id_dev" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "rapporteur" (
	"id_rapporteur"	INTEGER NOT NULL,
	"nom"	TEXT NOT NULL,
	"ticket"	TEXT,
	PRIMARY KEY("id_rapporteur" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "ticket" (
	"id_ticket"	INTEGER,
	"titre"	TEXT NOT NULL,
	"description"	TEXT,
	"status"	TEXT,
	"priorite"	TEXT,
	"id_rapporteur"	INTEGER,
	"id_clev"	INTEGER,
	"id_client"	INTEGER,
	FOREIGN KEY("id_clev") REFERENCES "developpeur"("id_dev"),
	FOREIGN KEY("id_rapporteur") REFERENCES "rapporteur"("id_rapporteur")
);
INSERT INTO "client" VALUES (1,'alex','TOC1','sdis');
INSERT INTO "developpeur" VALUES (1,'thomas','TOC1');
INSERT INTO "rapporteur" VALUES (1,'jade','TOC1');
INSERT INTO "ticket" VALUES (1,'ROS','on nous signale un défaut ROS secteur 1 LTE 800','ouvert','P3',1,1,1);
COMMIT;
