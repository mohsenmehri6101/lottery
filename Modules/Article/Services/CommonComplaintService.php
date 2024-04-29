<?php

namespace Modules\Article\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Article\Entities\CommonComplaint;
use Modules\Article\Http\Requests\CommonComplaint\CommonComplaintIndexRequest;
use Modules\Article\Http\Requests\CommonComplaint\CommonComplaintShowRequest;
use Modules\Article\Http\Requests\CommonComplaint\CommonComplaintStoreRequest;
use Modules\Article\Http\Requests\CommonComplaint\CommonComplaintUpdateRequest;
use Modules\Article\Http\Repositories\CommonComplaintRepository;

class CommonComplaintService
{
    public function __construct(public CommonComplaintRepository $commonComplaintRepository)
    {
    }

    public function index(CommonComplaintIndexRequest|array $request)
    {
        try {
            $fields = $request->validated();
            return $this->commonComplaintRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(CommonComplaintShowRequest $request, $common_complaint_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->commonComplaintRepository->withRelations(relations: $withs)->findOrFail($common_complaint_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(CommonComplaintStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $common_complaint = $this->commonComplaintRepository->create($fields);
            DB::commit();
            return $common_complaint;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(CommonComplaintUpdateRequest $request, $common_complaint_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var CommonComplaint $common_complaint */
            $common_complaint = $this->commonComplaintRepository->findOrFail($common_complaint_id);

            $this->commonComplaintRepository->update($common_complaint, $fields);

            DB::commit();

            return $this->commonComplaintRepository->findOrFail($common_complaint_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($common_complaint_id)
    {
        DB::beginTransaction();
        try {
            # find complaint
            /** @var CommonComplaint $common_complaint */
            $common_complaint = $this->commonComplaintRepository->findOrFail($common_complaint_id);

            # delete complaint
            $status_delete_complaint = $this->commonComplaintRepository->delete($common_complaint);

            DB::commit();
            return $status_delete_complaint;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
