<?php

namespace Modules\Exception\Services;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Exception\Entities\Error;
use Modules\Exception\Http\Repositories\ErrorRepository;
use Modules\Exception\Http\Requests\Error\ErrorIndexRequest;
use Modules\Exception\Http\Requests\Error\ErrorStoreRequest;
use Exception;
use Modules\Exception\Http\Requests\Error\ErrorUpdateRequest;

class ErrorService extends Controller
{
    public function __construct(public ErrorRepository $errorRepository)
    {
    }

    public function index(ErrorIndexRequest $request)
    {
        try {
            $fields = $request->validated();

            $message = $fields['message'] ?? null;

            if ($message && filled($message)) {
                $fields['message'] = (object)[
                    'col' => 'message',
                    'value' => $message,
                    'like' => true,
                ];
            }

            return $this->errorRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show($error_id)
    {
        try {
            return $this->errorRepository->findOrFail($error_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(ErrorStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $inputs = $request->validated();
            $error = $this->errorRepository->create($inputs);
            DB::commit();
            return $error;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }


    public function update(ErrorUpdateRequest $request, $error_id)
    {
        DB::beginTransaction();
        try {
            /** @var Error $error */
            $error = $this->errorRepository->findOrFail($error_id);
            $fields = $request->validated();
            $this->errorRepository->update($error, $fields);
            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($error_id)
    {
        DB::beginTransaction();
        try {
            # find Error
            /** @var Error $error */
            $error = $this->errorRepository->findOrFail($error_id);
            # delete error
            $status_delete_error = $this->errorRepository->delete($error);

            DB::commit();
            return $status_delete_error;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
