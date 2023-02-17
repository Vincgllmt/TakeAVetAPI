![banner](README_files/banner.png)
# Take A Vet (API)

Ce projet est une amélioration de [Take A Vet](https://iut-info.univ-reims.fr/gitlab/udyc0001/sae3-01), il s'agit de la partie [API Platform](https://api-platform.com/) de l'application.

---

**Take A Vet** est une application web qui permet principalement de gérer les rendez-vous des vétérinaires avec leurs clients, et propose bien d'autres fonctionnalités (Cf. [Fonctionnalités](#Fonctionnalités)).

## Contributeurs
- Alexis UDYCZ
- Vincent GUILLEMOT
- Clement PERROT
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
TODO

## Informations
TODO
### Fonctionnalités
TODO
## Utilisation
TODO