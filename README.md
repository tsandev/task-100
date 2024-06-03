# Symfony Docker

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up --pull always -d --wait` to set up and start a fresh Symfony project
4. Open https://localhost in your web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Import the logs with executing `docker compose exec php bin/console app:process_logs`. This will import all records from the sample log file.
6. Documentation is available under https://localhost/api/doc. 
7. Endpoint for consuming the API is https://localhost/api/count
8. Running the tests is happening with executing `docker compose exec php bin/phpunit`
8. Run `docker compose down --remove-orphans` to stop the Docker containers.

## License

Symfony Docker is available under the MIT License.

## Credits

Created by [Kévin Dunglas](https://dunglas.dev), co-maintained by [Maxime Helias](https://twitter.com/maxhelias) and sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
