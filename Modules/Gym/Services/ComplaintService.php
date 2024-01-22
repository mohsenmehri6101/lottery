<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Gym\Http\Requests\Complaint\ComplaintIndexRequest;
use Modules\Gym\Http\Requests\Complaint\ComplaintShowRequest;
use Modules\Gym\Http\Requests\Complaint\ComplaintStoreRequest;
use Modules\Gym\Http\Requests\Complaint\ComplaintUpdateRequest;
use Modules\Gym\Entities\Complaint;
use Modules\Gym\Http\Repositories\ComplaintRepository;

class ComplaintService
{
    public function __construct(public ComplaintRepository $complaintRepository)
    {
    }

    public function index(ComplaintIndexRequest|array $request)
    {
        try {
            $fields = $request->validated();
            return $this->complaintRepository->resolve_paginate(inputs: $fields);
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
            return $this->complaintRepository->withRelations(relations: $withs)->findOrFail($complaint_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(ComplaintStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();
            $complaint = $this->complaintRepository->create($fields);
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
            /** @var Complaint $complaint */
            $complaint = $this->complaintRepository->findOrFail($complaint_id);
            $this->complaintRepository->update($complaint, $fields);
            DB::commit();
            return $this->complaintRepository->findOrFail($complaint_id);
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
            /** @var Complaint $complaint */
            $complaint = $this->complaintRepository->findOrFail($complaint_id);
            # delete complaint
            $status_delete_complaint = $this->complaintRepository->delete($complaint);
            DB::commit();
            return $status_delete_complaint;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
