<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\IdTrait;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PDOException;

class UserController extends Controller
{
    /**
     * @OA\Info(title="User API Collect&Verything", version="0.1")
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, IdTrait;

    /**
     * @OA\Get(
     *      path="/api/users/{id}",
     *      operationId="retrieve",
     *      tags={"Users"},
     *      summary="Get user information",
     *      description="Returns user data",
     *      @OA\Parameter(name="id",description="User id",required=true,in="path"),
     *      @OA\Response(response=200, description="successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Account not found."),
     *      @OA\Response(response=409, description="Conflict"),
     *      @OA\Response(response=500, description="Servor Error"),
     *      security={{"bearer_token":{}}}
     * )
     */
    public function retrieve(Request $request): JsonResponse
    {
        try{
            $request->validate([
                'id' => 'string',
            ]);

            $resultSet = User::select('*')->where('id', $request->id)->first();

            return response()->json($resultSet, 200);

        } catch(Exception $e){
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/users",
     *      operationId="list",
     *      tags={"Users"},
     *      summary="Get all users information",
     *      description="Returns user data",
     *      @OA\Response(response=200, description="successful operation"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=409, description="Conflict"),
     *      @OA\Response(response=500, description="Servor Error"),
     *      security={{"bearer_token":{}}}
     * )
     */
    public function list(Request $request): JsonResponse
    {
        try{
            $resultSet = User::select('*');

            return response()->json($resultSet->get(), 200);

        } catch(Exception $e){
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/users",
     *      operationId="create",
     *      tags={"Users"},
     *      summary="Post a new user",
     *      description="Create a new user",
     *      @OA\Parameter(name="addressId", description="User's address", required=true, in="query"),
     *      @OA\Parameter(name="storeId", description="User's store", required=true, in="query"),
     *      @OA\Parameter(name="accountId", description="User's account", required=true, in="query"),
     *      @OA\Response(response=201,description="Account created"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function create(Request $request): JsonResponse
    {
        try{
            $request->validate([
                'addressId' => 'string|required',
                'storeId' => 'string|required',
                'accountId' => 'string|required',
            ]);

            DB::beginTransaction();

            $user = new User;

            $user->id = $this->generateId('user', $user);
            $user->addressId = $request->input('addressId');
            $user->storeId = $request->input('storeId');
            $user->accountId = $request->input('accountId');

            $user->save();

            DB::commit();

            return response()->json($user->fresh(), 201);
        }
        catch (PDOException $e) {
            Bugsnag::notifyException($e);
            throw new PDOException($e);
        }
        catch (ModelNotFoundException | ValidationException $e) {
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), 409);
        }
        catch (JsonEncodingException $e) {
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), $e->getCode());
        } catch (DecryptException | Exception $e) {
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Patch (
     *      path="/api/users/{id}",
     *      operationId="update",
     *      tags={"Users"},
     *      summary="Patch a user",
     *      description="Update a user",
     *      @OA\Parameter(name="addressId", description="First name", in="query"),
     *      @OA\Parameter(name="storeId", description="Last name", in="query"),
     *      @OA\Parameter(name="accountId", description="gender", in="query"),
     *      @OA\Response(
     *          response=200,
     *          description="Account updated"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{"bearer_token":{}}}
     * )
     */
    public function update(Request $request): JsonResponse
    {
        try{
            $request->validate([
                'addressId' => 'string',
                'storeId' => 'string',
                'accountId' => 'string',
            ]);

            DB::beginTransaction();

            $resultSet = User::select('*')
                ->where('id', $request->id);

            $user = $resultSet->first();

            if (empty($user)) {
                throw new ModelNotFoundException('User not found.', 404);
            }

            $user->addressId = $request->input('addressId', $user->getOriginal('addressId'));
            $user->storeId = $request->input('storeId', $user->getOriginal('storeId'));
            $user->accountId = $request->input('accountId', $user->getOriginal('accountId'));

            $user->save();

            DB::commit();

            return response()->json($user->fresh(), 200);
        }
        catch (PDOException $e) {
            Bugsnag::notifyException($e);
            throw new PDOException($e);
        }
        catch (ModelNotFoundException | JsonEncodingException $e) {
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), $e->getCode());
        }
        catch (ValidationException $e) {
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), 409);
        }
        catch (AuthenticationException $e) {
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), 403);
        }
        catch (Exception $e) {
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Delete  (
     *      path="/api/users/{id}",
     *      operationId="delete",
     *      tags={"Users"},
     *      summary="Delete a user",
     *      description="Soft delete a user",
     *      @OA\Parameter(
     *          name="id",
     *          description="Account id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="String"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Account deleted"
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={{"bearer_token":{}}}
     * )
     */
    public function delete(Request $request): JsonResponse
    {
        try{
            DB::beginTransaction();

            $resultSet = User::select('*')
                ->where('id', $request->id);

            $user = $resultSet->first();

            if (empty($user)) {
                throw new ModelNotFoundException('User not found.', 404);
            }

            $user->delete();

            DB::commit();

            return response()->json($user->fresh(), 200);
        }
        catch (PDOException $e) {
            Bugsnag::notifyException($e);
            throw new PDOException($e);
        }
        catch (ModelNotFoundException $e) {
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), $e->getCode());
        }
        catch (ValidationException | Exception $e) {
            Bugsnag::notifyException($e);
            return response()->json($e->getMessage(), 409);
        }
    }
}
