ToDoList
========

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

## Commandes Docker Compose

### Lancer le projet
```bash
docker-compose up -d --build
```

### Arrêter le projet

```bash
docker-compose down
```

### Afficher les logs du projet

```bash
docker-compose logs
```

### Se connecter en bash dans le conteneur PHP

```bash
docker-compose exec -it php bash
```

### Lancer l'installation des vendors via Composer

```bash
docker-compose exec -it php composer install
```

### Se connecter dans le terminal MySQL

```bash
docker-compose exec -it mysql mysql
```
