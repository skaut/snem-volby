![GitHub Workflow Status](https://img.shields.io/github/workflow/status/skaut/snem-volby/main)
[![codecov](https://codecov.io/gh/skaut/snem-volby/branch/master/graph/badge.svg?token=BW6FS72IU2)](https://codecov.io/gh/skaut/snem-volby)


# Sněm - hlasování a volby
Systém pro zajištění elektronického hlasování o změně Stanov a pro zajištění případných elektronických voleb do ústředních orgánů (systém určený delegátkám a delegátům Valného sněmu 2020).

## O aplikaci
Realizace i funkčnost této webové aplikace je rozdělena do dvou částí: 1. fáze umožňuje elektronické hlasování o změně Stanov Junáka - českého skauta, 2. fáze v případě nutnosti poskytne systém pro zajištění elektronických voleb do ústředních orgánů Junáka - českého skauta.

### Fáze 1
Aplikace umožňuje oprávněným delegátkám a delegátům Valného sněmu 2020 se přihlásit svým účtem jako do skautISu. Systém ověří jejich oprávnění (role "1 - Sněm: účastník  sněmu (18.9.2020)", zkontroluje že jsou v seznamu delegátů (a tedy nedošlo k odhlášení či změně za náhradníka) a ve vymezeném čase umožní hlasovat ohledně navrženého dodatku Stanov (pro / proti / zdržuji se).
V systému lze nastavit čas určený pro hlasování (reálně probíhá několik dní), před začátkem samotného hlasování je stáhnut seznam řádných delegátek a delegátů VSJ 2020 ze skautISu (aby během voleb nemohlo dojít ke změně zaregistrovaných oprávněných hlasujících), a pak již mohou oprávněné osoby hlasovat. Při hlasování se zaznamená informace, že daná osoba hlasovala (každý smí hlasovat jen jednou) a zaznamená se daný hlas (pro / proti / zdržel se). Systém nijak neukládá informaci o tom, jak který delegát hlasoval. Je tak už v této fázi zajištěna úplná tajnost hlasování (což je i příprava na druhou fázi, kde tajnost volby bude nutností).
Po skončení hlasování odpovědný zpravodaj Výkonné rady zveřejní výsledek hlasování o změně Stanov (systém zobrazí počet hlasujících, počet delegátů pro návrh s hlídáním 3/5 většiny, počet proti i počet zdržujících se či neúčastnících se hlasování).

Kompletní release verze webové aplikace, jak zajišťovala hlasování o změně Stanov je dostupná na https://github.com/skaut/snem-volby/releases/tag/volby-zmenastanov

### Fáze 2 (připravuje se)
V případě, že bude schválena mimořádná změna Stanov a bude umožněna vzdálená volba do ústředních orgánů, pak bude tato část projektu realizována v průběhu července až poloviny září 2020 a zajistí možnost elektronických voleb.


## Instalace (zapojení do vývoje a testování)

### Spuštění
- composer install
- yarn install
- yarn build
- zkopírovat si `config/config.sample.neon` do `config/config.local.neon`
- nastavit si v /etc/hosts `127.0.0.1    snem-volby.loc`
- `docker volume create snem_mysql`
- `docker-compose up -d`
- `docker exec -ti snem.app bash -c "mkdir /var/www/html/temp/sessions;chmod 777 /var/www/html/temp/sessions"`
- `docker exec -ti snem.app bash -c "bin/console migrations:migrate"`

### Vypnutí
- `docker-compose down`
