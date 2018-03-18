# DeepPermission

### Step 1: Install DeepPermission

composer require libressltd/deeppermission

### Step 2: Add service provider to config/app.php

```php
//Provider

LIBRESSLtd\DeepPermission\DeepPermissionServiceProvider::class, 
LIBRESSLtd\LBForm\LBFormServiceProvider::class,
Maatwebsite\Excel\ExcelServiceProvider::class,
Collective\Html\HtmlServiceProvider::class,
LIBRESSLtd\LBSA\LBSAServiceProvider::class,

//Fadecade

'Excel' => Maatwebsite\Excel\Facades\Excel::class,
'Form' => Collective\Html\FormFacade::class,
'Html' => Collective\Html\HtmlFacade::class,

```

### Step 3: Publish vendor

php artisan vendor:publish --tag=lbsa_init --force
php artisan vendor:publish --tag=deeppermission --force

php artisan migrate

### Step 4: Add following line to User Model (Remember to move User.php to app/Models) or you can find the newest version in vendor/libresslt/deeppermission/src/User.php
	
	
```php


use LIBRESSLtd\DeepPermission\Traits\DPUserModelTrait;

class User extends Authenticatable
{
    use DPUserModelTrait;
}


```

Add this row in the end of scripts.blade.php 

```php
@yield('dp_script')

```

Add this row to Http\Kernel.php in $routeMiddleware

```php

'dppermission' => \App\Http\Middleware\DPPermissionMiddleware::class,
'dprole' => \App\Http\Middleware\DPRoleMiddleware::class,

```

### Step 5 (optional): If you want a user pass all the permission, add LIBRE_DP_ADMIN_ID to your .env file

```bash
LIBRE_DP_ADMIN_ID={{user_id}}
#for example
LIBRE_DP_ADMIN_ID=1

```

### Step 6: (for LBSA only) Move example/app.blade.php to views root folder and include libressltd.deeppermission.sidebar view

### Supported function

```php

//Check role of user
$user->hasRole("role.code");

//Check permission of user (include if user has role which has permission)
$user->hasPermission("permission.code");

//Query with role code
User::withRole("role.code");

//Query with permission code (include user who has role which has permission)
User::withPermission("permission.code");

// Using middleware

middleware("dppermission:admin.read");
middleware("dprole:admin");

```