## PackageMomo

PackageMomo Un package simple en php pour l'integration de l'api MTN Mobile Money.

## Produits supportés

* Collections
* Disbursements (pas encore disponible)


## Avant toute chose

* S'inscrire sur momodeveloper ou avoir des accès momoapi(prod)
* Souscrire aux produit de collection


## Pour s'inscrire

Veuillez cliquer sur ce lien  [signup](https://momodeveloper.mtn.com/signup)

## Souscrire aux Produits

Sur la page [Products](https://momodeveloper.mtn.com/products) sur le portail des développeurs, vous devriez voir les éléments auxquels vous pouvez vous abonner:

* Collections
* Disbursements
* Remittances

## Installation

La méthode recommandée pour installer PackageMomo est [composer](http://getcomposer.org).



```TERMINAL
composer require kouyatekarim/momoapi
composer install
```
Vous pouvez maintenant ajouter l'autoloader et vous aurez accès à la bibliothèque:
n'oublier surtout pas de mettre si c'est un projet en php natif

```php
<?php
require 'vendor/autoload.php';
```

 toujours au dessus de tout

```php
<?php
require 'vendor/autoload.php';
```

pour un projet avec un framework require 'vendor/autoload' n'est pas à faire

## Utilisation
### Créer une instance de produit

```php
<?php
use Kouyatekarim\Momoapi\Products\Collection;


$options = [
    // 'callbackHost' => '', //(optionel)  http://localhost:8000 est par defaut
    // 'callbackUrl' => '', //(optionel)  http://localhost:8000/callback est par defaut
    // 'environment' => '', //(optionel) sandbox est par defaut
    // 'accountHolderIdType' => '', //(optionel)  msisdn est par defaut
    'subscriptionKey' => '', //la clée produit de souscription
    'xReferenceId' => '', //Api user  (en  format UUID )
    'apiKey' => '', // Api key (obtenu après l'avoir généré à 'Créer une clé API')
   
    //'accessToken' => '' //obligatoire pour requestopay
];

// avec collection
$collection = Collection::create($options);



```

### Sandbox User Provisioning
#### Créer API User
```php
<?php
use Kouyatekarim\Momoapi\Products\Collection;


// avec collection
$product = Collection::create($options);

$response = $product->createApiUser(); //{"statusCode": 201}

echo $response

```

#### Obtenir les details API User
```php
<?php
use Kouyatekarim\Momoapi\Products\Collection;


// Using collection
$product = Collection::create($options);



$apiUser = $product->getApiUser();
echo $apiUser->getProviderCallbackHost(); //http://localhost:8000
echo '</br>';
echo $apiUser->getTargetEnvironment(); //sandbox

```

#### Créer API Key
```php
<?php
use Kouyatekarim\Momoapi\Products\Collection;


// Avec collection
$product = Collection::create($options);


$apiKey = $product->createApiKey();
echo $apiKey->getApiKey(); //apiKey

```

### Oauth 2.0
#### Obtenir le token(jéton)
```php
<?php
use Kouyatekarim\Momoapi\Products\Collection;


// Avec collection
$product = Collection::create($options);



$token = $product->getToken();
echo $token->getAccessToken(); //accessToken
echo '</br>';
echo $token->getTokenType(); //tokenType
echo '</br>';
echo $token->getExpiresIn(); //expiry in seconds

```

### Transactions


### Collections
#### Request to pay
```php
<?php
use Kouyatekarim\Momoapi\Products\Collection;

// Avec collection
$product = Collection::create($options);

$product->requestToPay($externalId, $partyId, $amount, $currency, $payerMessage = '', $payeeNote = ''); // *

```
### Exemple complet
#### Exemple complet de test(sandbox)

```php
<?php
require 'vendor/autoload.php'; // à enlever si vous travailler avec un framework
use Kouyatekarim\Momoapi\Products\Collection;

$xReferenceId = "afd59405-1bbe-4928-99d2-0c7060956358"; // à générer sur le site [UUID](https://www.uuidgenerator.net/).
$options = [
     'callbackHost' => 'clinic.com', 
    // 'callbackUrl' => '', 
    //'environment' => '', 
    // 'accountHolderIdType' => '', 
    'subscriptionKey' => '<votre_primary_key>', 
    'xReferenceId' =>  $xReferenceId, 
 

];



// Avec collection
$product = Collection::create($options);

$product->createApiUser(); 
$apiUser = $product->getApiUser();
echo $apiUser->getProviderCallbackHost(); //clinic.com
echo '</br>';
echo $apiUser->getTargetEnvironment(); //sandbox
echo '</br>';



$apiKey = $product->createApiKey();


echo $apiKey->getApiKey();






$options = [
    // 'callbackHost' => '', //(optional)
    // 'callbackUrl' => '', //(optional) 
    //'environment' => 'mtnivorycoast', 
    // 'accountHolderIdType' => '', 
    'subscriptionKey' => '<votre_primary_key>', 
    'xReferenceId' => $xReferenceId, //
    'apiKey' => $apiKey->getApiKey(), //  
   
];





$product = Collection::create($options);


$token = $product->getToken();
echo $token->getAccessToken(); //accessToken
$token->getTokenType(); //tokenType
$token->getExpiresIn(); //expiry in seconds




$options = [
    // 'callbackHost' => '', 
    // 'callbackUrl' => '', 
    //'environment' => '', 
    // 'accountHolderIdType' => '', 
    'subscriptionKey' => '', 
    'xReferenceId' =>$xReferenceId, 
    'apiKey' => $apiKey->getApiKey(), 
    'accessToken' => $token 
];


$product = Collection::create($options);

$externalId ="12345";
$partyId = '<un_numero_mtn_mobile_money>'; //numero sans l'indicateur du pays
$amount = 10;
$currency = "EUR";
$product->requestToPay($externalId, $partyId, $amount, $currency, $payerMessage = '', $payeeNote = ''); 


```



#### Exemple complet de production

```php
<?php
require 'vendor/autoload.php'; // à enlever si vous travailler avec un framework
use Kouyatekarim\Momoapi\Products\Collection;


$options = [
     'callbackHost' => '<nom_de_domaine>', 
    'callbackUrl' => '<nom_de_domaine>/callback', 
    'environment' => 'mtnivorycoast', //pour la côte d'ivoire
    // 'accountHolderIdType' => '', 
    'subscriptionKey' => '<votre_primary_key_prod>', 
    'xReferenceId' =>  '<votre_api_user>', //Api user obtenu depuis le portail partner de MTN [Partner](https://partnermobilemoney.mtn.ci/partner/).
    'apiKey' => '<votre_api_user>', // Api  key obtenu depuis le portail partner de MTN [Partner](https://partnermobilemoney.mtn.ci/partner

];









$product = Collection::create($options);


$token = $product->getToken();
echo $token->getAccessToken(); //accessToken
$token->getTokenType(); //tokenType
$token->getExpiresIn(); //expiry in seconds




$options = [
     'callbackHost' => '<nom_de_domaine>', 
    'callbackUrl' => '<nom_de_domaine>/callback', 
    'environment' => 'mtnivorycoast', //pour la côte d'ivoire
    // 'accountHolderIdType' => '', 
    'subscriptionKey' => '<votre_primary_key_prod>', 
    'xReferenceId' =>  '<votre_api_user>', //Api user obtenu depuis le portail partner de MTN [Partner](https://partnermobilemoney.mtn.ci/partner/).
    'apiKey' => '<votre_api_user>', // Api  key obtenu depuis le portail partner de MTN [Partner](https://partnermobilemoney.mtn.ci/partner
    'accessToken' =>  $token  ,//obligatoire

];


$product = Collection::create($options);

$externalId ="12345";
$partyId = '<votre_numero_mobile_money>'; //numero sans l'indicateur du pays
$amount = 10;
$currency = "XOF";
$product->requestToPay($externalId, $partyId, $amount, $currency, $payerMessage = '', $payeeNote = ''); 


```

### Bugs
Pour tout bug trouvé, veuillez m'envoyer un e-mail à kouyatekarim02@gmail.com ou enregistrer un problème sur [issues] (https://github.com/kkstar34/momoapi/issues)
