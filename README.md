# Blog

Projekt to prosta aplikacja typu blog, która umożliwia dodanie posta z poziomu API, CLI i formularza WWW. Post składa się z tytułu, treści i obrazka w formacie JPG. Aplikacja do działania wykorzystuje Docker, Docker Compose, PHP7.4 i bazę danych SQLite. 

## Uruchomienie projektu

**1. Uruchomienie kontenerów (z poziomu katalogu aplikacji):**

```bash
docker-compose -p blog up -d
```

**2. Wejście do powłoki kontenera PHP:**

```bash
docker exec -it blog_php_1 bash
```

**3. Inicjalizacja projektu (z poziomu kontenera PHP):**

```
sh script/init.sh
```

**4. Wygenerowanie przykładowych danych (opcjonalnie):**

```
php bin/console doctrine:fixtures:load
```

Serwer WWW uruchamia się na porcie 80. 
Adres po uruchomieniu: http://localhost/ lub http://127.0.0.1/.

**Zatrzymanie kontenerów (z poziomu katalogu aplikacji):**

```bash
docker-compose -p blog down
```

## Aplikacja

**Dodanie posta z poziomu CLI:**

```bash
php bin/console app:add-blog-post "To jest tytuł testowy z konsoli" "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua." "/var/www/html/tests/example.jpg"
```

## Dokumentacja API

Dokumentacja API znajduje się w katalogu **/doc-api** i jest importowalną kolekcją Postman.

## Asynchroniczność

Do obsługi asynchroniczności został użyty komponent Symfony Messenger. Aby command i event były procesowane asynchronicznie, należy w pliku **config/packages/messenger.yaml** odkomentować poniższe linie w sekcji "routing":

```yaml
'App\Blog\Application\Command\AddBlogPostCommand': async
'App\Blog\Application\Event\BlogPostHasBeenAddedEvent': async
```

A następnie z poziomu kontenera PHP uruchomić komendę:

```bash
php bin/console messenger:consume async -vv
```

## Analiza kody

Statyczną analizę kodu można uruchomić wywołując polecenie z poziomu kontenera PHP (PHP Stan, PHP CS Fixer):

```bash
composer static:analyze
```

Automatyczne poprawienie styli:

```bash
composer fix-cs
```

## Testy

```bash
composer tests
```



## TODO

- [x] Write README.md
- [x] Add infrastructure as code
- [x] Add API documentation
- [x] Add Unit test
