# Gestionnaire de Tickets – SAE‑502

Application web légère en PHP (architecture MVC) permettant la gestion de tickets, de clients et de projets.
Elle inclut une gestion des rôles (`admin`, `rapporteur`, `developpeur`) avec droits d’accès différenciés et des statistiques globales.

---

## Prérequis

- PHP 8.1 ou supérieur
- SQLite3

---

## Installation et initialisation

### 1. Cloner le dépôt

```bash
git clone https://github.com/jader01/SAE-502
cd SAE-502
```

### 2. (Ré)initialiser la base de données

Pour repartir d’une base propre ou créer la base initiale :

```bash
rm ticketdb.sqlite
rm .applied_migrations
php database/migrate.php
```

Ces commandes :
- suppriment l’ancienne base SQLite ;
- réinitialisent l’historique des migrations ;
- créent une base vierge et appliquent les scripts de migration.

---

## Lancer l’application

Démarrez le serveur PHP intégré depuis la racine du projet :

```bash
php -S localhost:8080 -t public
```

Puis ouvrez votre navigateur à l’adresse :
[http://localhost:8080](http://localhost:8080)

---

## Structure du projet
```
 ├── app/
 │   ├── Controllers/     # Contrôleurs (logique applicative)
 │   ├── Models/          # Modèles (accès base de données)
 │   └── Views/           # Vues
 │
 ├── config/              # Paramètres de configuration
 ├── database/            # Contient migrate.php, les migrations  et la db
 ├── public/              # Point d’entrée public
 ├── routes/              # routeur
 └── README.md
```
---

## Rôles et accès

| Rôle | Description | Accès principal |
|------|--------------|----------------|
| **Admin** | Gestion complète des utilisateurs, projets, clients et tickets | `/admin` |
| **Rapporteur** | Création et suivi de ses propres tickets | `/ticket/list` |
| **Développeur** | Prise en charge et mise à jour des tickets assignés | `/ticket/list` |

---

## Comptes de base

Ces comptes sont créés automatiquement lors de l’exécution du script SQL de migration.

| Identifiant | Rôle | Mot de passe |
|--------------|------|--------------|
| `admin`  | Administrateur | `test1234` |
| `rap1`   | Rapporteur | `test1234` |
| `rap2`   | Rapporteur | `test1234` |
| `dev1`   | Développeur | `test1234` |
| `dev2`   | Développeur | `test1234` |
