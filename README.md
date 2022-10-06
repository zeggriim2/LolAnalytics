# LolAnalytics

- [Etat d'avancement](#etat-davancement)
- [Design](#design)

## Etat d'avancement

L'avancement peut être suivi [board trello](https://trello.com/invite/b/SjioKq5W/2953cccc0307330d94a14020443eb3e9/league-of-legends-analytics)

## Design

Pour le design j'utilise [Figma](https://www.figma.com) car c'est l'outil le plus simple à utiliser pour collaborer rapidement.

- [Maquettes Figma](https://www.figma.com/file/Vh1pSLF0CGuMeiJZStxUZp/LolAnalytics?node-id=334%3A16)

## Pré-requis

* [**PHP 8.0**][php8.0]
* [**Docker**][docker_install]
* [**Symfony Cli**][symfony.exe]

## Installation

 - Récupération du projet
 
```bash
git clone git@github.com:zeggriim2/LolAnalytics.git
```

- Installer les dépendances

```bash
composer install
```

- Lancer docker

```bash
docker-compose up -d
```

- Creation de la base de donnée (en DEV)

```bash
make db-create-dev
```

- Lancer l'application :
```bash
make  server-start
```


[php8.0]: https://www.php.net/releases/8.0/en.php
[docker_install]: https://docs.docker.com/docker-for-windows/install
[symfony.exe]: https://symfony.com/download