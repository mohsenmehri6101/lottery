<?php

namespace Modules\Faq\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Faq\Http\Requests\Faq\FaqIndexRequest;
use Modules\Faq\Http\Requests\Faq\FaqShowRequest;
use Modules\Faq\Http\Requests\Faq\FaqStoreRequest;
use Modules\Faq\Http\Requests\Faq\FaqUpdateRequest;
use Modules\Faq\Entities\Faq;
use Modules\Faq\Http\Repositories\FaqRepository;

class FaqService
{
    public function __construct(public FaqRepository $faqRepository)
    {
    }

    public function index(FaqIndexRequest $request): LengthAwarePaginator|Collection
    {
        try {
            $fields = $request->validated();
            return $this->faqRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(FaqShowRequest $request, $faq_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);

            $withs = $withs ?? [];
            return $this->faqRepository->withRelations(relations: $withs)->findOrFail($faq_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(FaqStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            $faq = $this->faqRepository->create($fields);
            DB::commit();
            return $faq;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(FaqUpdateRequest $request, $faq_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /** @var Faq $faq */
            $faq = $this->faqRepository->findOrFail($faq_id);

            $this->faqRepository->update($faq, $fields);
            DB::commit();

            return $this->faqRepository->findOrFail($faq_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($faq_id)
    {
        DB::beginTransaction();
        try {
            # find faq
            /** @var Faq $faq */
            $faq = $this->faqRepository->findOrFail($faq_id);

            # delete faq
            $this->faqRepository->delete($faq);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
