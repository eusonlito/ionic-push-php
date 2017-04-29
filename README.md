# ionic-push-php [![Release](https://img.shields.io/github/release/tomloprod/ionic-push-php.svg)](https://github.com/tomloprod/ionic-push-php) [![License](https://img.shields.io/github/license/tomloprod/ionic-push-php.svg)](http://www.opensource.org/licenses/mit-license.php) 

ionic-push-php is a library that allows you to consume the *Ionic Cloud API* for **sending push notifications** (*normal and scheduled*), get a paginated **list of sending push notifications**,  get **information of registered devices**, **remove registered devices by token**, ...

Ionic official documentation: [Ionic HTTP API - Push](https://docs.ionic.io/api/endpoints/push.html).

## Requirements:

- PHP 5.1+
- cURL


## Installation:

    composer require tomloprod/ionic-push-php


## How to use:

First, make sure you have your $ionicAPIToken and your $ionicProfile:
- (string) **$ionicAPIToken:** The device token you [registered and saved](http://docs.ionic.io/services/push/#setup).
- (string) **$ionicProfile:** The Security Profile tag found in Settings › Certificates in the [Dashboard](https://apps.ionic.io).

> More information [here](https://github.com/tomloprod/ionic-push-php/issues/1).

<br>
Then, instance an object:

```php
use Tomloprod\IonicApi\Push;

$ionicPushApi = new Push($ionicProfile, $ionicAPIToken);
```

 
Then you can:

### Device Tokens

 **1) List tokens:**
```php
$tokens = $ionicPushApi->deviceTokens->paginatedList([
    // Determines whether to include invalidated tokens (boolean)
    'show_invalid' => 1,
    // Only display tokens associated with the User ID (string)
    'user_id' => $desiredUserId,
    // Sets the number of items to return per page (integer)
    'page_size' => 4,
    // Sets the page number (integer)
    'page' => 1
]);
```

**2) Create device token that was previously generated by a device platform:**
```php
$createdDeviceToken = $ionicPushApi->deviceTokens->create([
    // Device token (string)
    'token' => $newToken,
    // User ID. Associate the token with the User (string)
    'user_id' => $uuid 
]);
```

**3) Retrieve device information related to the device token:**
```php
$deviceInformation = $ionicPushApi->deviceTokens->retrieve($desiredDeviceToken);
```

**4) Update an specific token:**
```php
$updatedDeviceInformation = $ionicPushApi->deviceTokens->update($desiredDeviceToken, [
    // Determines whether the device token is valid (boolean)
    'valid' => 1
]);
```
 
**5) Delete a device related to the device token:**
```php
$deleteResult = $ionicPushApi->deviceTokens->delete($desiredDeviceToken);
```

### Messages

**1) Retrieve specific message:**
```php
$message = $ionicPushApi->messages->retrieve($desiredMessageId);
```


**2) Delete a message:**
```php
$deleteResult = $ionicPushApi->messages->delete($desiredMessageId);
```

### Notifications
 
**1) List notifications:**
```php
$notifications = $ionicPushApi->notifications->paginatedList([
    // Sets the number of items to return per page (integer)
    'page_size' => 1,
    // Sets the page number (integer)
    'page' => 1,
    // You can also pass other fields like "message_total" or "overview" (string[])
    'fields' => [
        'message_total'
    ]
]);
```

**2) Retrieve specific notification:**
```php
$notification = $ionicPushApi->notifications->retrieve($desiredNotificationId);
```
 
**3) Delete a notification:**
```php
$deleteResult = $ionicPushApi->notifications->delete($desiredNotificationId);
```

**4) List messages of a notification:**
```php
$messages = $ionicPushApi->notifications->listMessages($desiredNotificationId, [
    // Sets the number of items to return per page (integer)
    'page_size' => 1,
    // Sets the page number (integer)
    'page' => 1
])
 ```
 
**5) Send notifications:**
```php
// Configuration of the notification
$notificationConfig = [
    'title' => 'Your notification title',
    'message' => 'Your notification message. Bla, bla, bla, bla.',
    'android' => [
        'tag' => 'YourTagIfYouNeedIt'
    ],
    'ios' => [
        'priority' => 10,
        'badge' => 1
    ]
];



// [OPTIONAL] You can also pass custom data to the notification. Default => []
$payload = [ 
    'myCustomField' => 'This is the content of my customField',
    'anotherCustomField' => 'More custom content'
];

// [OPTIONAL] And define, if you need it, a silent notification. Default => false
$silent = true;

// [OPTIONAL] Or/and even a scheduled dateteime. Default => ''
$scheduled = '2016-12-10 10:30:10';


// Configure notification:
$ionicPushApi->notifications->setConfig($notificationConfig, $payload, $silent, $scheduled);

// Send notification...
$ionicPushApi->notifications->sendNotificationToAll(); // ...to all registered devices
$ionicPushApi->notifications->sendNotification([$desiredToken1, $desiredToken2, $desiredToken3]); // ...to some devices
```
    
##### *NOTE: You will need to parse the returned array of the methods that return information.*


<br>

## TODO:

1. Methods replace() ~~and listMessages()~~ of **Notifications**.
1. Mothods listAssociatedUsers(), associateUser() and dissociateUser() of **DeviceTokens**.
