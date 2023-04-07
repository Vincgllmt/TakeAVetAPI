![banner](README_files/banner.png)
# Take A Vet (API)

Ce projet est une amélioration de [Take A Vet](https://iut-info.univ-reims.fr/gitlab/udyc0001/sae3-01), il s'agit de la partie [API Platform](https://api-platform.com/) de l'application.

---

**Take A Vet** est une application web qui permet principalement de gérer les rendez-vous des vétérinaires avec leurs clients, et propose bien d'autres fonctionnalités (Cf. [Fonctionnalités](#Fonctionnalités)).

## Contributeurs
- Alexis UDYCZ
- Vincent GUILLEMOT
- Clément PERROT
- Romain LEROY
- Benoît SOULIERE

## Installation

Étape 1 : Cloner le projet
```bash
git clone https://iut-info.univ-reims.fr/gitlab/udyc0001/sae3-01-api.git
```

Étape 2 : Installer les dépendances
```bash
composer install
```

Étape 3 : Créer la base de données fixtures (dev)
```bash
composer migrate
composer db
```

Étape 4 : Lancer le serveur
```bash
composer start
```

### Tests

La commande pour exécuter les tests varie selon le système d'exploitation.

| Linux           | Windows             |
|-----------------|---------------------|
| `composer test` | `composer test:win` |

Autres tests :
- `composer test:codeception` : Exécute les tests unitaires codeception
- `composer test:cs` : Exécute les tests de style de code

### Installation avec Docker

- Pour le lancer avec le docker compose :
```sh
docker-compose up
```

## Troubleshooting
Si vous n'avez pas les permissions executez le fichier `droits.sh`
Dans le cas de l'IUT vous pouvez executer : 
```sh
docker exec -ti sae4-01-api-php-1 /bin/sh
chmod -R o+rwx public vendor
```

## Informations

| Type de compte | Email              | Mot de passe |
|----------------|--------------------|--------------|
| Vétérinaire    | `veto@takea.vet`   | `test`       |
| Client         | `client@takea.vet` | `test`       |
| ... (Fixtures) | ...                | `test`       |


### Fonctionnalités
- [x] gérer les adresses
- [x] gérer les agendas, les indisponibilités et les vacances
- [x] gérer les recap des animaux
- [x] gérer les animaux et les vaccins
- [x] gérer les mediaObject tel que les images des animaux etc..
- [x] gérer les rendez-vous
- [x] gérer les utilisateurs comme les clients et les vétérinaires
- [x] gérer les threads et les réponses 
- [x] gérer les types d'animaux et les types de rendez-vous
## Utilisation
Pour utiliser l'api il suffit de se rendre sur :
``
localhost:8000/
``
Il faut le lancer le serveur avant ``composer start``

Certaine action nécessite un utilisateur spécifique ou simplement connecté. 