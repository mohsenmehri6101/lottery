<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Gym\Http\Requests\QrCode\QrCodeIndexRequest;
use Modules\Gym\Http\Requests\QrCode\QrCodeShowRequest;
use Modules\Gym\Http\Requests\QrCode\QrCodeStoreRequest;
use Modules\Gym\Http\Requests\QrCode\QrCodeUpdateRequest;
use Modules\Gym\Entities\QrCode;
use Modules\Gym\Http\Repositories\QrCodeRepository;

class QrCodeService
{
    public function __construct(public QrCodeRepository $qrCodeRepository)
    {
    }

    public function index(QrCodeIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $qrCodeStoreRequest = new QrCodeIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $qrCodeStoreRequest->rules(),
                    attributes: $qrCodeStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            return $this->qrCodeRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(QrCodeShowRequest $request, $qrCode_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->qrCodeRepository->withRelations(relations: $withs)->findOrFail($qrCode_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(QrCodeStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $qrCode = $this->qrCodeRepository->create($fields);
            DB::commit();
            return $qrCode;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(QrCodeUpdateRequest $request, $qrCode_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var QrCode $qrCode */
            $qrCode = $this->qrCodeRepository->findOrFail($qrCode_id);

            $this->qrCodeRepository->update($qrCode, $fields);
            DB::commit();

            return $this->qrCodeRepository->findOrFail($qrCode_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($qrCode_id)
    {
        DB::beginTransaction();
        try {
            # find qrCode
            /** @var QrCode $qrCode */
            $qrCode = $this->qrCodeRepository->findOrFail($qrCode_id);

            # delete qrCode
            $status_delete_qrCode = $this->qrCodeRepository->delete($qrCode);

            DB::commit();
            return $status_delete_qrCode;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
