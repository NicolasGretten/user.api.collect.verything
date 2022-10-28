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
     *      path="/",
     *      description="Example",
     *      @OA\Response(response="default", description="Welcome page")
     *      security={{"bearer_token":{}}}
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
