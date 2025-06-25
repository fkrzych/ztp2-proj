1. docker compose up -d
2. docker compose exec php bash
3. dodanie danych dostępowych do bazy danych w pliku .env
4. cd app -> composer install
5. bin/console doctrine:migrations:migrate
6. bin/console doctrine:fixtures:load
