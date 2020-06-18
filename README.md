# Sněm - Volby
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/skaut/snem-volby/main)
[![codecov](https://codecov.io/gh/skaut/snem-volby/branch/master/graph/badge.svg?token=BW6FS72IU2)](https://codecov.io/gh/skaut/snem-volby)

## Spuštění
- composer install
- yarn install
- yarn build
- zkopírovat si `config/config.sample.neon` do `config/config.local.neon`
- nastavit si v /etc/hosts `127.0.0.1    snem-volby.loc`
- `docker-compose up -d`
- `docker exec -ti snem.app bash -c "mkdir /var/www/html/temp/sessions;chmod 777 /var/www/html/temp/sessions"`
- `docker exec -ti snem.app bash -c "bin/console migrations:migrate"`


## Vypnutí
- `docker-compose down`
