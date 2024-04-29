<?php

namespace Modules\Payment\Services;

use App\Permissions\RolesEnum;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Authorization\Entities\Role;
use Symfony\Component\HttpFoundation\Response;
use Modules\Payment\Http\Requests\Account\AccountIndexRequest;
use Modules\Payment\Http\Requests\Account\AccountShowRequest;
use Modules\Payment\Http\Requests\Account\AccountStoreRequest;
use Modules\Payment\Http\Requests\Account\AccountUpdateRequest;
use Modules\Payment\Entities\Account;
use Modules\Payment\Http\Repositories\AccountRepository;
use Modules\Payment\Http\Requests\Account\MyAccountRequest;

class AccountService
{
    public function __construct(public AccountRepository $accountRepository)
    {
    }

    public function index(AccountIndexRequest $request)
    {
        try {
            $fields = $request->validated();
            return $this->accountRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function myAccount(MyAccountRequest $request)
    {
        try {
            $fields = $request->validated();
            $fields['user_id']=get_user_id_login();
            return $this->accountRepository->resolve_paginate(inputs: $fields,my_auth: true);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(AccountShowRequest $request, $account_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);

            $withs = $withs ?? [];
            return $this->accountRepository->withRelations(relations: $withs)->findOrFail($account_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(AccountStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            if(!user_have_role(RolesEnum::admin->name)){
                unset($fields['user_id']);
            }

            /** @var Account $account */
            $account = $this->accountRepository->create($fields);

            DB::commit();
            return $account;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(AccountUpdateRequest $request, $account_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            if(true || !user_have_role(RolesEnum::admin->name) && isset($fields['user_id']) && filled($fields['user_id'])){
                $exception = new Exception(code:Response::HTTP_FORBIDDEN,message:'شما مجوز تغییر ندارید');
                report($exception);
                unset($fields['user_id']);
            }

            /** @var Account $account */
            $account = $this->accountRepository->findOrFail($account_id);

            $this->accountRepository->update($account, $fields);
            DB::commit();

            return $this->accountRepository->findOrFail($account_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($account_id)
    {
        DB::beginTransaction();
        try {
            # find account
            /** @var Account $account */
            $account = $this->accountRepository->findOrFail($account_id);

            # delete account
            $this->accountRepository->delete($account);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
