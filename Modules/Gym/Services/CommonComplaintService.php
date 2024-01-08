<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Gym\Entities\CommonComplaint;
use Modules\Gym\Http\Requests\Complaint\ComplaintIndexRequest;
use Modules\Gym\Http\Requests\Complaint\ComplaintShowRequest;
use Modules\Gym\Http\Requests\Complaint\ComplaintStoreRequest;
use Modules\Gym\Http\Requests\Complaint\ComplaintUpdateRequest;
use Modules\Gym\Http\Repositories\CommonComplaintRepository;

class CommonComplaintService
{
    public function __construct(public CommonComplaintRepository $commonComplaintRepository)
    {
    }

    public function index(ComplaintIndexRequest|array $request)
    {
        try {
            $fields = $request->validated();
            return $this->commonComplaintRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(ComplaintShowRequest $request, $complaint_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            return $this->commonComplaintRepository->withRelations(relations: $withs)->findOrFail($complaint_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(ComplaintStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $complaint = $this->commonComplaintRepository->create($fields);
            DB::commit();
            return $complaint;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(ComplaintUpdateRequest $request, $complaint_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var CommonComplaint $complaint */
            $complaint = $this->commonComplaintRepository->findOrFail($complaint_id);

            $this->commonComplaintRepository->update($complaint, $fields);

            DB::commit();

            return $this->commonComplaintRepository->findOrFail($complaint_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($complaint_id)
    {
        DB::beginTransaction();
        try {
            # find complaint
            /** @var CommonComplaint $complaint */
            $complaint = $this->commonComplaintRepository->findOrFail($complaint_id);

            # delete complaint
            $status_delete_complaint = $this->commonComplaintRepository->delete($complaint);

            DB::commit();
            return $status_delete_complaint;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
