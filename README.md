# Projekt obchodu s domácimi miláčikmi

## Krátky opis môjho riešenia zadania:
Projekt som vypracoval s použitím Nette frameworku na strane backendu a na strane frontendu som použil React.
Na spracovanie restových požiadaviek som na strane servera použil balíček tomaj/nette-api.
Všetky dáta o zvieratkách ukladám a udržiavam v XML súbore v /data/pets.xml. Na vytváranie unikátneho ID-čka zvieratka, ktoré sa vždy automaticky inkrementuje podľa posledného použitého ID-čka, som vytvoril v modeli PetModel obslužné funkcie
a naposledy použité ID-čko si zapisujem do konfiguračného súboru /config/config.json, kde mám zapísané aj iné konfiguračné dáta, ktoré v aplikáci využívam.
API endpointy som vytvoril presne podľa volaní definovaných na https://petstore3.swagger.io. Ku každému API volaniu som vytvoril príslušné Handlery, ktoré som definoval v konfiguračnom súbore common.neon. 
V konfiguračnom súbore common.neon som zaregistroval aj ApiPresenter, ktorý je použivaný balíčkom tomaj/nette-api. Okrem spomínaných vecí som v common.neon súbore musel povoliť to, 
aby bolo možné tieto endpointy možné volať ajaxovo z reactu - enableGlobalPreflight().
Obrázky jednotlivých zvieratiek ukladám do /data/uploads/. Samozrejmosťou je aj fyzické mazanie z tohto priečinka pri zmazaní záznamu zvieratka.
Na zobrazenie obrázkov pri detaile zvieratka na strane reactu som použil volanie Home presentera a jeho metódu show: 'http://localhost:8000/home/show?image=${pet.imageName}'
Všetky funkcie na ukladanie, editovanie, mazanie a zobrazovanie zvieratok na strane nette robí model PetModel, ktorý je volaný z jednotlivých handlerov podľa potreby. PetModel dedí od
EntityModel, ktorý je abstraktnou triedou a definuje základné metódy, ktoré by mal podediť každý potomok a načítava dáta z konfigov.
Pre pridanie nového atribútu zvieratka ako napríklad 'age' je potrebné vykonať tieto kroky:
1. pridanie atribútu 'age' a zadefinovanie getAge() metódy v triede Pet.php
2. pridanie atribútu 'age' do metódy createFromArray() v triede Pet.php
3. pridanie atribútu 'age' do metód create() a getAll() v triede PetModel.php
4. pridanie 'age' do triedy PetsCreateHandler.php ako nový PostInputParam
5. pridanie 'age' atribútu do súboru config/config.json, aby ho vedel updatnúť PetsUpdateHandler.php

## Postup k inicializácii projektu a spusteniu aplikácie:
### 1. git clone https://github.com/Petrus2929/efabrica.git
### 2. zmazanie celého priečinka efabrica/vendor a zamazanie súbora efabrica/composer.lock (dôvod: command composer install nevie stiahnúť nejaké súbory od tomaj api)
### 3. presun do priečinka efabrica/nette-api a následné spustenie príkazu composer install  
### 4. presun do priečinka efabrica/client a následné spustenie príkazu npm install
### 5. spustenie príkazu 'php -S localhost:8000 -t www' v priečinku efabrica/nette-api
### 6. spustenie príkazu 'npm start' v priečinku efabrica/client


