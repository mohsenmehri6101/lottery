<?php

namespace Modules\Payment\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Payment\Http\Requests\Bank\PaymentIndexRequest;
use Modules\Payment\Http\Requests\Bank\PaymentShowRequest;
use Modules\Payment\Http\Requests\Bank\BankStoreRequest;
use Modules\Payment\Http\Requests\Bank\BankUpdateRequest;
use Modules\Payment\Entities\Bank;
use Modules\Payment\Http\Repositories\BankRepository;

class BankService
{
    public function __construct(public BankRepository $bankRepository)
    {
    }

    public function index(PaymentIndexRequest $request)
    {
        try {
            $fields = $request->validated();
            return $this->bankRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(PaymentShowRequest $request, $bank_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);

            $withs = $withs ?? [];
            return $this->bankRepository->withRelations(relations: $withs)->findOrFail($bank_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(BankStoreRequest $request): Bank
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();
            /** @var Bank $bank */
            $bank = $this->bankRepository->create($fields);
            DB::commit();
            return $bank;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(BankUpdateRequest $request, $bank_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();
            /** @var Bank $bank */
            $bank = $this->bankRepository->findOrFail($bank_id);
            $this->bankRepository->update($bank, $fields);
            DB::commit();
            return $this->bankRepository->findOrFail($bank_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($bank_id): bool
    {
        DB::beginTransaction();
        try {
            # find bank
            /** @var Bank $bank */
            $bank = $this->bankRepository->findOrFail($bank_id);
            # ###########
            # delete bank
            $this->bankRepository->delete($bank);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
