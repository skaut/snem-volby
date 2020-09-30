![GitHub Workflow Status](https://img.shields.io/github/workflow/status/skaut/snem-volby/main)
[![codecov](https://codecov.io/gh/skaut/snem-volby/branch/master/graph/badge.svg?token=BW6FS72IU2)](https://codecov.io/gh/skaut/snem-volby)


# Sněm - hlasování a volby
Systém pro zajištění elektronického hlasování o změně Stanov a pro zajištění případných elektronických voleb do ústředních orgánů (systém určený delegátkám a delegátům Valného sněmu 2020).

## O aplikaci
Realizace i funkčnost této webové aplikace je rozdělena do dvou částí: 1. fáze umožňuje elektronické hlasování o změně Stanov Junáka - českého skauta, 2. fáze v případě nutnosti poskytne systém pro zajištění elektronických voleb do ústředních orgánů Junáka - českého skauta.

### Fáze 1 (pro červen 2020)
Aplikace umožňuje oprávněným delegátkám a delegátům Valného sněmu 2020 se přihlásit svým účtem jako do skautISu. Systém ověří jejich oprávnění (role "1 - Sněm: účastník  sněmu (18.9.2020)", zkontroluje že jsou v seznamu delegátů (a tedy nedošlo k odhlášení či změně za náhradníka) a ve vymezeném čase umožní hlasovat ohledně navrženého dodatku Stanov (pro / proti / zdržuji se).
V systému lze nastavit čas určený pro hlasování (reálně probíhá několik dní), před začátkem samotného hlasování je stáhnut seznam řádných delegátek a delegátů VSJ 2020 ze skautISu (aby během voleb nemohlo dojít ke změně zaregistrovaných oprávněných hlasujících), a pak již mohou oprávněné osoby hlasovat. Při hlasování se zaznamená informace, že daná osoba hlasovala (každý smí hlasovat jen jednou) a zaznamená se daný hlas (pro / proti / zdržel se). Systém nijak neukládá informaci o tom, jak který delegát hlasoval. Je tak už v této fázi zajištěna úplná tajnost hlasování (což je i příprava na druhou fázi, kde tajnost volby bude nutností).
Po skončení hlasování odpovědný zpravodaj Výkonné rady zveřejní výsledek hlasování o změně Stanov (systém zobrazí počet hlasujících, počet delegátů pro návrh s hlídáním 3/5 většiny, počet proti i počet zdržujících se či neúčastnících se hlasování).

Kompletní release verze webové aplikace, jak zajišťovala hlasování o změně Stanov je dostupná na https://github.com/skaut/snem-volby/releases/tag/volby-zmenastanov

### Fáze 2 (pro říjen 2020)
Webová aplikace umožňuje kompletně realizaci elektronických voleb ústředních orgánů Junáka - českého skauta. Delegáti a delegátky Valného sněmu 2020 (přestože v prezenční formě byl v souladu s vnitřním právem zrušen) se do aplikace přihlásí svým účtem jako do skautISu. Systém ověří jejich oprávnění (dle role a účastníka delegáta VSJ), zkontroluje tedy přítomnost v seznamu delegátů (aby nedošlo k záměně za náhradníky či dobrovolného vzdání se mandátu) a ve vymezeném termínu může na elektronickém hlasovacím lístku provést volbu osob do ústředních orgánů. Stejně tak v případě potřeby mohou delegáti skrze aplikaci sepsat text námitky k průběhu či výsledku voleb (dle náležitostí vnitřního práva).
V rámci nastavení systému lze nastavit datum a čas pro probíhající volby (reálně probíhají několik dní), před začátkem voleb je ze skautISu stáhnut seznam platných delegátů VSJ 2020, složení mimořádné kandidátní komise (která získává přístup do správy systému a náhledu na volby) a také stažení přehledu kandidujících osob pro hlasovací lístek. Při samotném hlasování se zaznamenává u delegáta účast ve volbách (že se do systému přihlásil) a pak to, že provedl volbu (zaznamená se odevzdání hlasovacího lístu), kdy oba tyto kroky jsou paralelou k obdobným úkonům při prezenční volbě na VSJ. Systém ale nijak neukládá informaci o tom, jak který delegát hlasoval. Zaznamenává se pouze informace o tom, že nějaký kandidát dostal hlas, ale ne od koho. U odevzdaného hlasovacího lístku se ukládá hash pro vyhodnocení, v kolika volbách delegát hlasoval, ale opět bez jakéhokoli ukládání kdo jak hlasoval.
Po skončení voleb mimořádná volební komise vyhodnotí výsledky, může nahlédnout na prezenční listinu delegátů, informaci o účasti ve volbách, počtu odevzdaných hlasovacích lístků (a jejich částí ve volbách orgánů). Pokud je alespoň 50% účast ve volbách, může zkontrolovat a zveřejnit výsledky voleb. V případě shody hlasů pro některé z kandidátů může před vyhlášením voleb komise v nastavení určit v souladu s vnitřním právem finální pořadí takových kandidátů. 
Členové Rozhodčí a smírčí rady mají v systému přístup do přehledu podaných námitek.
Organizační zpravodaj VRJ má do systému přístup a je odpovědný za zajištění voleb v aplikaci a stejně tak je komisi i RSRJ k dispozici, včetně možnosti náhledu do systému se stejnou úrovní jako tyto dva orgány voleb.

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
