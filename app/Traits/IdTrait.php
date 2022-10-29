<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait FiltersTrait
 * @package App\Traits
 */
trait IdTrait
{
    public function generateId($prefix, Model $model): string
    {
        static $counter = 0;

        $id = $prefix . '-' . Str::uuid();

        $idAlreadyAllowed = !($model::where('id', $id)->count() == 0);

        while($idAlreadyAllowed == true) {
            if($counter == 5) {
                throw new Exception('Unable to generate ID', 409);
            }

            $counter++;
            $this->generateId($prefix, $model);
        }

        $counter = 0;
        return $id;
    }
}
