# LBForm
This is a form element pre-written with adminlte library of acacha.

# How to install

### Step 1: install

```php
composer require libressltd/lbsa
```

### Step 2: add service provider

In config/app.php, add following line to provider

```php
LIBRESSLtd\LBSA\LBSAServiceProvider::class,
```

### Step 3: Publish 

```php
php artisan vendor:publish --tag=lbsa_init --force
```

### Step 4: Copy app.blade.php file to root folder of view, extend in any view

```php
@extend("app")

```
