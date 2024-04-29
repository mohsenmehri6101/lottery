<?php

namespace Modules\Payment\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Payment\Http\Requests\Account\AccountIndexRequest;
use Modules\Payment\Http\Requests\Account\AccountShowRequest;
use Modules\Payment\Http\Requests\Account\AccountStoreRequest;
use Modules\Payment\Http\Requests\Account\AccountUpdateRequest;
use Modules\Payment\Http\Requests\Account\MyAccountRequest;
use Modules\Payment\Services\AccountService;

class AccountController extends Controller
{
    public function __construct(public AccountService $accountService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/accounts",
     *     tags={"accounts"},
     *     summary="list accounts",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="account_number",in="query",required=false, @OA\Schema(type="integer"),description="account_number"),
     *     @OA\Parameter(name="card_number",in="query",required=false, @OA\Schema(type="integer"),description="card_number"),
     *     @OA\Parameter(name="shaba_number",in="query",required=false, @OA\Schema(type="integer"),description="shaba_number"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(AccountIndexRequest $request): JsonResponse
    {
        $accounts = $this->accountService->index($request);
        return ResponseHelper::responseSuccess(data: $accounts);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/accounts/my-account",
     *     tags={"accounts"},
     *     summary="list my-accounts",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="account_number",in="query",required=false, @OA\Schema(type="integer"),description="account_number"),
     *     @OA\Parameter(name="card_number",in="query",required=false, @OA\Schema(type="integer"),description="card_number"),
     *     @OA\Parameter(name="shaba_number",in="query",required=false, @OA\Schema(type="integer"),description="shaba_number"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function myAccount(MyAccountRequest $request): JsonResponse
    {
        $accounts = $this->accountService->myAccount($request);
        return ResponseHelper::responseSuccess(data: $accounts);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/accounts/{id}",
     *     tags={"accounts"},
     *     summary="show account",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(AccountShowRequest $request, $account_id): JsonResponse
    {
        $account = $this->accountService->show($request, $account_id);
        return $account ? ResponseHelper::responseSuccessShow(data: $account) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/accounts",
     *     tags={"accounts"},
     *     summary="save account",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="account_number",in="query",required=false, @OA\Schema(type="string"),description="شماره حساب"),
     *     @OA\Parameter(name="card_number",in="query",required=false, @OA\Schema(type="string"),description="شماره کارت"),
     *     @OA\Parameter(name="shaba_number",in="query",required=false, @OA\Schema(type="string"),description="شماره شبا"),
     *     @OA\Parameter(name="bank_id",in="query",required=false, @OA\Schema(type="integer"),description="bank_id"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(AccountStoreRequest $request): JsonResponse
    {
        $account = $this->accountService->store($request);
        return $account ? ResponseHelper::responseSuccessStore(data: $account) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/accounts/{id}",
     *     tags={"accounts"},
     *     summary="update account",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="account_number",in="query",required=false, @OA\Schema(type="string"),description="شماره حساب"),
     *     @OA\Parameter(name="card_number",in="query",required=false, @OA\Schema(type="string"),description="شماره کارت"),
     *     @OA\Parameter(name="shaba_number",in="query",required=false, @OA\Schema(type="string"),description="شماره شبا"),
     *     @OA\Parameter(name="bank_id",in="query",required=false, @OA\Schema(type="integer"),description="bank_id"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(AccountUpdateRequest $request, $account_id): JsonResponse
    {
        $account = $this->accountService->update($request, $account_id);
        return $account ? ResponseHelper::responseSuccessUpdate(data: $account) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/accounts/{id}",
     *     tags={"accounts"},
     *     summary="delete account",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($account_id): JsonResponse
    {
        $status_delete = $this->accountService->destroy($account_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

}
