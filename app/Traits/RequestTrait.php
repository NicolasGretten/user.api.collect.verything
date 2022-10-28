<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Trait FiltersTrait
 * @package App\Traits
 */
trait RequestTrait
{
    protected $savedRelations;

    public function resetRelations(Request $request, array $relations = null)
    {
        $savedRelations = $request->filters['relations'];
        unset($request['filters']);
        if($relations != null) {
            $request->request->add(['filters'=>['relations' => json_encode($relations)]]);
        }
        return $request;
    }

    public function originalRelations(Request $request)
    {
        unset($request['filters']);
        $request->request->add(['filters'=>['relations' => $this->savedRelations]]);
        return $request;
    }
}
