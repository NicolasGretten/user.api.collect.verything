# Collect&Verything API
## Starting new project
```bash
composer install
```

```bash
npm install
```

## environnement de Dev en Localhost

prérequis: télécharger le zip et suivre les instructions du README.md

https://drive.google.com/file/d/12BPhUyn6tKy5MmknZ0EEDFTGBWGErvyv/view?usp=sharing

Si besoin d'un postgreSQL avec docker : https://github.com/NicolasGretten/docker-compose-postgresql.git

## .ENV

Les champ **** sont à modifier dans le .env.example et il faut le renommer en .env

La clé Bugsnag sont ici : https://docs.google.com/spreadsheets/d/1vPGWGhQnj9uDbyWU1lRZvis07IYlWoU5MP8oS3OWZ9E/edit?usp=sharing

```dotenv
APP_NAME=*****************
APP_ENV=local
APP_KEY=base64:H75acfx0EynqsZ14yMNJpORmdrZhX8kdWLTr46+gU2c=
APP_DEBUG=true
APP_URL=**************************

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

BUGSNAG_API_KEY=********************************

JWT_SECRET=rgaWbM90Ft5OIduFf4CoOROjUmWvkzxFUxT5TylDMrhcbsE6CaO1WpkFgqFJrNwE
JWT_TTL=3600

DB1_CONNECTION=data
DB1_HOST=*********************
DB1_PORT=5432
DB1_DATABASE=*****************
DB1_USERNAME=**************
DB1_PASSWORD=******************

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

```

## Migrations

Créer une migration

```bash 
php artisan make:migration create_user_table
```

Migrér les migration et seed
```bash 
php artisan migrate:fresh --seed
```

Rollback la base de données
```bash 
php artisan migrate:rollback
```

## Controllers
```php 
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(title="Template API Collect&Verything", version="0.1")
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * * @OA\Get(
     *     path="/",
     *     description="Example",
     *     @OA\Response(response="default", description="Welcome page")
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try{
            $this->validate($request, [
                'example' => 'string',
            ]);

            $resultSet = User::select('*');

            return response()->json($resultSet, 200);

        } catch(Exception $e){
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), 500);
        }
    }
}
```

## Routes

Les routes sont à reinseigner dans /routes/api.php

```php 
<?php

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(Controller::class)->group(function () {
//    Route::get("users/", 'index')->middleware('auth');
    Route::get("users/", 'index');
});

```

## Docs

consultable sur /api/documentation

```bash
php artisan l5-swagger:generate
```

## Import swagger vers Postman

Sur le lien /docs/api-docs.json il est possible de récupérer le texte brut et de l'importer dans Postman.

## Ajouter la remote template

````bash
git remote add template https://github.com/NicolasGretten/template.api.collect.verything.git
````

