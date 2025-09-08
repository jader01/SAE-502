BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "membres" (
	"Nom"	TEXT NOT NULL,
	"id"	INTEGER,
	"entreprise"	TEXT,
	"role"	TEXT NOT NULL,
	"TOC"	TEXT
);
CREATE TABLE IF NOT EXISTS "ticket" (
	"id_ticket"	INTEGER,
	"stitre"	TEXT,
	"description"	TEXT,
	"status"	TEXT,
	"priorite"	TEXT,
	"id_rapporteur"	INTEGER,
	"id_developpeur"	INTEGER,
	"id_client"	INTEGER
);
INSERT INTO "membres" ("Nom","id","entreprise","role","TOC") VALUES ('Thomas',1,'Axians','devellopeur','TOC1'),
 ('Jade',2,'Axians','rapporteur','TOC1'),
 ('Alex',3,'Sdis','client','TOC2'),
 ('Thomas',1,'Axians','devellopeur','TOC2'),
 ('Thomas',1,'Axians','devellopeur','TOC3'),
 ('Alex',3,'Sdis','client','TOC3'),
 ('Alex',3,'Sdis','client','TOC1'),
 ('Jade',2,'Axians','rapporteur','TOC2'),
 ('Jade',2,'Axians','rapporteur','TOC3');
INSERT INTO "ticket" ("id_ticket","titre","description","status","priorite","id_rapporteur","id_developpeur","id_client") VALUES ('TOC1','ROS','on nous signale un défaut ROS sur le secteur 2 LTE 800','ouvert','P4',2,1,3),
 ('TOC2','EVT','defaut extracteur','ouvert','P3',2,1,3),
 ('TOC3','RSSI','on nous signale un defaut d''interference au niveau du S3','ouvert','P2',2,1,3);
CREATE VIEW "test" AS SELECT * FROM "main"."membres" WHERE "Nom" LIKE '%Thomas%' ESCAPE '\' AND "TOC" LIKE '%TOC1%' ESCAPE '\' AND "entreprise" LIKE '%Axians%' ESCAPE '\' AND "id" LIKE '%1%' ESCAPE '\' AND "role" LIKE '%developpeur%' ESCAPE '\' ORDER BY "role" ASC;
COMMIT;
