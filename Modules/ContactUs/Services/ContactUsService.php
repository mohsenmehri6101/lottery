<?php

namespace Modules\ContactUs\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\ContactUs\Entities\ContactUs;
use Modules\ContactUs\Http\Repositories\ContactUsRepository;
use Modules\ContactUs\Http\Requests\ContactUs\ContactUsIndexRequest;
use Modules\ContactUs\Http\Requests\ContactUs\ContactUsShowRequest;
use Modules\ContactUs\Http\Requests\ContactUs\ContactUsStoreRequest;
use Modules\ContactUs\Http\Requests\ContactUs\ContactUsUpdateRequest;

class ContactUsService
{
    public function __construct(public ContactUsRepository $contactUsRepository)
    {
    }

    public function index(ContactUsIndexRequest|array $request)
    {
        try {
            $fields = $request->validated();
            return $this->contactUsRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(/*ContactUsShowRequest $request, */$contactUs_id)
    {
        try {
//            $fields = $request->validated();

            /**
             * @var $withs
             */
//            extract($fields);
//            $withs = $withs ?? [];
//            return $this->contactUsRepository->withRelations(relations: $withs)->findOrFail($contactUs_id);
            return $this->contactUsRepository->findOrFail($contactUs_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(ContactUsStoreRequest $request)
    {
        try {
            $fields = $request->validated();

            $contactUs = $this->contactUsRepository->create($fields);
            return $contactUs;
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    public function update(ContactUsUpdateRequest $request, $contactUs_id)
    {
        try {
            $fields = $request->validated();
            # find contactUs
            /** @var ContactUs $contactUs */
            $contactUs = $this->contactUsRepository->find($contactUs_id);

            # update
            $this->contactUsRepository->update($contactUs, $fields);

            return $this->contactUsRepository->findOrFail($contactUs_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function destroy($contactUs_id)
    {
        DB::beginTransaction();
        try {
            # find contactUs
            /** @var ContactUs $contactUs */
            $contactUs = $this->contactUsRepository->findOrFail($contactUs_id);

            # delete contactUs
            $status_delete_contactUs = $this->contactUsRepository->delete($contactUs);

            DB::commit();
            return $status_delete_contactUs;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
