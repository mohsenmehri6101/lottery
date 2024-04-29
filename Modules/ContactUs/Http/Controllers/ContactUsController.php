<?php

namespace Modules\ContactUs\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Routing\Controller;
use Modules\ContactUs\Services\ContactUsService;
use Modules\ContactUs\Http\Requests\ContactUs\ContactUsIndexRequest;
//use Modules\ContactUs\Http\Requests\ContactUs\ContactUsShowRequest;
use Modules\ContactUs\Http\Requests\ContactUs\ContactUsStoreRequest;
use Modules\ContactUs\Http\Requests\ContactUs\ContactUsUpdateRequest;
use Illuminate\Http\JsonResponse;

class ContactUsController extends Controller
{
    public function __construct(public ContactUsService $contactUsService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/contact-us",
     *     tags={"contact-us"},
     *     summary="list contact-us",
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="integer"),description="name"),
     *     @OA\Parameter(name="email",in="query",required=false, @OA\Schema(type="integer"),description="email"),
     *     @OA\Parameter(name="phone",in="query",required=false, @OA\Schema(type="integer"),description="phone"),
     *     @OA\Parameter(name="text",in="query",required=false, @OA\Schema(type="integer"),description="text"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="creeated_at",in="query",required=false, @OA\Schema(type="string"),description="creeated_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(ContactUsIndexRequest $request): JsonResponse
    {
        $contact_uses = $this->contactUsService->index($request);
        return ResponseHelper::responseSuccessIndex(data: $contact_uses);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/contact-us/{id}",
     *     tags={"contact-us"},
     *     summary="show contact-us",
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(/*ContactUsShowRequest $request, */ $contact_us_id): JsonResponse
    {
        $contact_us = $this->contactUsService->show(/*$request, */ $contact_us_id);
        return $contact_us ? ResponseHelper::responseSuccessShow(data: $contact_us) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post (
     *     path="/api/v1/contact-us",
     *     tags={"contact-us"},
     *     summary="store contact_us",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *     @OA\Parameter(name="email",in="query",required=false, @OA\Schema(type="string"),description="email"),
     *     @OA\Parameter(name="phone",in="query",required=false, @OA\Schema(type="string"),description="phone"),
     *     @OA\Parameter(name="text",in="query",required=false, @OA\Schema(type="string"),description="text"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(ContactUsStoreRequest $request): JsonResponse
    {
        $contact_us = $this->contactUsService->store($request);
        return $contact_us ? ResponseHelper::responseSuccessStore(data: $contact_us) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *      path="/api/v1/contact-us/{id}",
     *      tags={"contact-us"},
     *      summary="update contact_us",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="string"),description="id"),
     *      @OA\Parameter(name="name",in="query",required=false, @OA\Schema(type="string"),description="name"),
     *      @OA\Parameter(name="email",in="query",required=false, @OA\Schema(type="string"),description="email"),
     *      @OA\Parameter(name="phone",in="query",required=false, @OA\Schema(type="string"),description="phone"),
     *      @OA\Parameter(name="text",in="query",required=false, @OA\Schema(type="string"),description="text"),
     *      @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *      @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *      @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(ContactUsUpdateRequest $request, $contact_us_id): JsonResponse
    {
        $contact_us = $this->contactUsService->update($request, $contact_us_id);
        return $contact_us ? ResponseHelper::responseSuccessUpdate(data: $contact_us) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/contact-us/{id}",
     *     tags={"contact-us"},
     *     summary="delete contact_us",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="number"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($contact_us_id): JsonResponse
    {
        $status_delete = $this->contactUsService->destroy($contact_us_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }
}
