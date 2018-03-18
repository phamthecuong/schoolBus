# LBSideMenu

### Step 1: Install LBSideMenu

composer require libressltd/lbsidemenu

### Step 2: Add service provider to config/app.php

```php

LIBRESSLtd\LBSideMenu\LBSideMenuServiceProvider::class,

```

### Step 3: Publish vendor

```php

php artisan vendor:publish --tag=lbsidemenu --force
php artisan migrate

```

### Step 4: include to views/app.blade.php of LBSA
	
	
```php

@include("libressltd.lbsidemenu.sidemenu")

```

### Step 5: start coding with http://<your-domain>/lbsm/item