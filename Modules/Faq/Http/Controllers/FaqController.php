<?php

namespace Modules\Faq\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Modules\Faq\Http\Requests\Faq\FaqIndexRequest;
use Modules\Faq\Http\Requests\Faq\FaqShowRequest;
use Modules\Faq\Http\Requests\Faq\FaqStoreRequest;
use Modules\Faq\Http\Requests\Faq\FaqUpdateRequest;
use Modules\Faq\Services\FaqService;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
    public function __construct(public FaqService $faqService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/faqs",
     *     tags={"faqs"},
     *     summary="لیست سوالات متداول",
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="question",in="query",required=false, @OA\Schema(type="string"),description="question"),
     *     @OA\Parameter(name="answer",in="query",required=false, @OA\Schema(type="string"),description="answer"),
     *     @OA\Parameter(name="order",in="query",required=false, @OA\Schema(type="integer"),description="order"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(FaqIndexRequest $request): JsonResponse
    {
        $faqs = $this->faqService->index($request);
        return ResponseHelper::responseSuccess(data: $faqs);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/faqs/{id}",
     *     tags={"faqs"},
     *     summary="نمایش یک سوال متداول",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(FaqShowRequest $request, $faq_id): JsonResponse
    {
        $faq = $this->faqService->show($request, $faq_id);
        return $faq ? ResponseHelper::responseSuccessShow(data: $faq) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/faqs",
     *     tags={"faqs"},
     *     summary="ذخیره سوال متداول",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="question",in="query",required=true, @OA\Schema(type="string"),description="question"),
     *     @OA\Parameter(name="answer",in="query",required=true, @OA\Schema(type="string"),description="answer"),
     *     @OA\Parameter(name="order",in="query",required=false, @OA\Schema(type="integer"),description="order"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(FaqStoreRequest $request): JsonResponse
    {
        $faq = $this->faqService->store($request);
        return $faq ? ResponseHelper::responseSuccessStore(data: $faq) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/faqs/{id}",
     *     tags={"faqs"},
     *     summary="ویرایش سوال متداول",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="question",in="query",required=false, @OA\Schema(type="string"),description="question"),
     *     @OA\Parameter(name="answer",in="query",required=false, @OA\Schema(type="string"),description="answer"),
     *     @OA\Parameter(name="order",in="query",required=false, @OA\Schema(type="integer"),description="order"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(FaqUpdateRequest $request, $faq_id): JsonResponse
    {
        $faq = $this->faqService->update($request, $faq_id);
        return $faq ? ResponseHelper::responseSuccessUpdate(data: $faq) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/faqs/{id}",
     *     tags={"faqs"},
     *     summary="حذف سوال متداول",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($faq_id): JsonResponse
    {
        $status_delete = $this->faqService->destroy($faq_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }
}
