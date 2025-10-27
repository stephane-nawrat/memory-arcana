# Installation Base de Données

## Prérequis
- MySQL 8.0+ ou MariaDB
- PHP 8.0+
- Accès root MySQL

## Installation

### 1. Configurer les variables d'environnement

```bash
# Copier le template
cp .env.example .env

# Éditer .env avec vos vraies valeurs
nano .env
```

Exemple de `.env` :
```
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=phase01_memory
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
DB_CHARSET=utf8mb4
```

### 2. Créer la base de données

```bash
mysql -u root -p < database/schema.sql
```

### 3. Vérifier

```bash
php test-db.php
```

## Résultat attendu

```
✅ Connexion réussie !
📊 Tables : games, players
=== TOP 5 SCORES ===
✅ Tout fonctionne !
```

## Sécurité

⚠️ **IMPORTANT** : Le fichier `.env` contient vos credentials et ne doit **JAMAIS** être commité.  
✅ Il est automatiquement ignoré par `.gitignore`

## Note

⚠️ Ne pas utiliser les extensions VSCode pour exécuter schema.sql  
✅ Toujours utiliser la ligne de commande MySQL