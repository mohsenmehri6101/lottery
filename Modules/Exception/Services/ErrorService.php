<?php

namespace Modules\Exception\Services;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Exception\Entities\Error;
use Modules\Exception\Http\Repositories\ErrorRepository;
use Modules\Exception\Http\Requests\Error\ErrorIndexRequest;
use Exception;
use Modules\Exception\Http\Requests\Error\ErrorShowRequest;

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
            $selects = $fields['selects'] ?? [];
            unset($fields['selects']);

            // set like
            if ($message && filled($message)) {
                $fields['message'] = (object)[
                    'col' => 'message',
                    'value' => $message,
                    'like' => true,
                ];
            }

            return $this->errorRepository->resolve_paginate(inputs: $fields,selects: $selects);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(ErrorShowRequest $request,$error_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->errorRepository->withRelations(relations: $withs)->findOrFail($error_id);
        } catch (Exception $exception) {
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
