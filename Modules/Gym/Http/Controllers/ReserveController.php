<?php

namespace Modules\Gym\Http\Controllers;

use App\Helper\Response\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Gym\Http\Requests\Reserve\MyGymReserveRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveBetweenDateRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveIndexRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveShowRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveStoreFactorLinkPaymentRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveStoreBlockRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveStoreRequest;
use Modules\Gym\Http\Requests\Reserve\ReserveUpdateRequest;
use Modules\Gym\Http\Requests\Reserve\MyReserveRequest;
use Modules\Gym\Services\ReserveService;
use Illuminate\Http\JsonResponse;

class ReserveController extends Controller
{
    public function __construct(public ReserveService $reserveService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserves",
     *     tags={"reserves"},
     *     summary="list reserves",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="reserve_template_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="dated_at",in="query",required=false, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is:userCreator,userEditor,user,reserveTemplate,gym,factors"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function index(ReserveIndexRequest $request): JsonResponse
    {
        $reserves = $this->reserveService->index($request);
        return ResponseHelper::responseSuccess(data: $reserves);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserves/my-reserves",
     *     tags={"reserves"},
     *     summary="list my reserves (user logged in)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="reserve_template_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="dated_at",in="query",required=false, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is: userCreator,userEditor,user,reserveTemplate,gym,factors"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function myReserve(MyReserveRequest $request): JsonResponse
    {
        $reserves = $this->reserveService->myReserve($request);
        return ResponseHelper::responseSuccess(data: $reserves);
    }

    
    /**
     * @OA\Get(
     *     path="/api/v1/reserves/my-gym-reserves",
     *     tags={"reserves"},
     *     summary="لیست رزور های سالن های من(مخصوص مسئول سالن ورزشی)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="paginate",in="query",required=false, @OA\Schema(type="string"),description="paginate"),
     *     @OA\Parameter(name="per_page",in="query",required=false, @OA\Schema(type="string"),description="per_page"),
     *     @OA\Parameter(name="page",in="query",required=false, @OA\Schema(type="string"),description="page"),
     *     @OA\Parameter(name="id",in="query",required=false, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="reserve_template_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="user_creator",in="query",required=false, @OA\Schema(type="integer"),description="user_creator"),
     *     @OA\Parameter(name="user_editor",in="query",required=false, @OA\Schema(type="integer"),description="user_editor"),
     *     @OA\Parameter(name="dated_at",in="query",required=false, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is:userCreator,userEditor,user,reserveTemplate,gym,factors"),
     *     @OA\Parameter(name="created_at",in="query",required=false, @OA\Schema(type="string"),description="created_at"),
     *     @OA\Parameter(name="updated_at",in="query",required=false, @OA\Schema(type="string"),description="updated_at"),
     *     @OA\Parameter(name="deleted_at",in="query",required=false, @OA\Schema(type="string"),description="deleted_at"),     *  )
     */
    public function myGymReserve(MyGymReserveRequest $request): JsonResponse
    {
        $reserves = $this->reserveService->myGymReserve($request);
        return ResponseHelper::responseSuccess(data: $reserves);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserves/{id}",
     *     tags={"reserves"},
     *     summary="show reserve",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="withs",in="query",required=false, @OA\Schema(type="string"),description="relations:list is"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function show(ReserveShowRequest $request, $reserve_id): JsonResponse
    {
        $reserve = $this->reserveService->show($request, $reserve_id);
        return $reserve ? ResponseHelper::responseSuccessShow(data: $reserve) : ResponseHelper::responseFailedShow();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reserves",
     *     tags={"reserves"},
     *     summary="save reserve",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="reserve_template_id",in="query",required=true, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="user_id",in="query",required=true, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="dated_at",in="query",required=true, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function store(ReserveStoreRequest $request): JsonResponse
    {
        $reserve = $this->reserveService->store($request);
        return $reserve ? ResponseHelper::responseSuccessStore(data: $reserve) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reserves/store-and-print-factor-and-create-link-payment",
     *     tags={"reserves"},
     *     summary="Store and do stuff for reserves",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="reserves", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="reserve_template_id", type="integer", description="ID of the reserve template", example=1),
     *                     @OA\Property(property="gym_id", type="integer", description="ID of the gyms", example=1),
     *                     @OA\Property(property="user_id", type="integer", description="ID of the users", example=1),
     *                     @OA\Property(property="dated_at", type="string", format="date", description="Date of the reserve", example="2024-01-12"),
     *                 )
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     * )
     */
    public function storeAndPrintFactorAndCreateLinkPayment(ReserveStoreFactorLinkPaymentRequest $request): JsonResponse
    {
        $reserve = $this->reserveService->storeAndPrintFactorAndCreateLinkPayment($request);
        return $reserve ? ResponseHelper::responseSuccessStore(data: ['url' => $reserve]) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reserves/blocks",
     *     tags={"reserves"},
     *     summary="save reserve blocks",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="reserve_template_id",in="query",required=true, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="dated_at",in="query",required=true, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function storeBlocks(ReserveStoreBlockRequest $request): JsonResponse
    {
        $reserve = $this->reserveService->storeBlocks($request);
        return $reserve ? ResponseHelper::responseSuccessStore(data: $reserve) : ResponseHelper::responseFailedStore();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/reserves/{id}",
     *     tags={"reserves"},
     *     summary="update reserve",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Parameter(name="reserve_template_id",in="query",required=false, @OA\Schema(type="integer"),description="reserve_template_id"),
     *     @OA\Parameter(name="user_id",in="query",required=false, @OA\Schema(type="integer"),description="user_id"),
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="integer"),description="status"),
     *     @OA\Parameter(name="dated_at",in="query",required=false, @OA\Schema(type="string"),description="dated_at"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function update(ReserveUpdateRequest $request, $reserve_id): JsonResponse
    {
        $reserve = $this->reserveService->update($request, $reserve_id);
        return $reserve ? ResponseHelper::responseSuccessUpdate(data: $reserve) : ResponseHelper::responseFailedUpdate();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/reserves/{id}",
     *     tags={"reserves"},
     *     summary="delete reserve",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id",in="path",required=true, @OA\Schema(type="integer"),description="id"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function destroy($reserve_id): JsonResponse
    {
        $status_delete = $this->reserveService->destroy($reserve_id);
        return $status_delete ? ResponseHelper::responseSuccessDelete() : ResponseHelper::responseFailedDelete();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserves/between-date",
     *     tags={"reserves"},
     *     summary="get reserve between two date",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="from", in="query", required=true, @OA\Schema(type="string"), description="Start date"),
     *     @OA\Parameter(name="to", in="query", required=true, @OA\Schema(type="string"), description="End date"),
     *     @OA\Parameter(name="gym_id", in="query", required=true, @OA\Schema(type="integer"), description="Gym ID"),
     *     @OA\Parameter(name="order_by", in="query", required=false, @OA\Schema(type="string"), description="order_by"),
     *     @OA\Parameter(name="direction_by", in="query", required=false, @OA\Schema(type="string"), description="direction_by"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function reserveBetweenDates(ReserveBetweenDateRequest $request): JsonResponse
    {
        $reserves = $this->reserveService->reserveBetweenDates($request);
        return ResponseHelper::responseSuccess(data: $reserves);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reserves/statuses",
     *     tags={"reserves"},
     *     summary="list statuses",
     *     @OA\Parameter(name="status",in="query",required=false, @OA\Schema(type="string"),description="status"),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent()),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent()),
     *  )
     */
    public function statuses(Request $request): JsonResponse
    {
        $statuses = $this->reserveService->statuses($request);
        $statuses = collect($statuses)->map(function ($name, $id) {
            return ['id' => $id, 'name' => $name];
        });
        return ResponseHelper::responseSuccess(data: $statuses);
    }
}
