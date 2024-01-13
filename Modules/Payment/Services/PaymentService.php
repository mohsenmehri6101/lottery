<?php

namespace Modules\Payment\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Authentication\Entities\User;
use Modules\Gym\Entities\Reserve;
use Modules\Payment\Entities\Factor;
use Modules\Payment\Http\Repositories\FactorRepository;
use Modules\Payment\Http\Repositories\PaymentRepository;
use Modules\Payment\Http\Requests\Payment\PaymentCreateLinkRequest;
use Modules\Payment\Http\Requests\Payment\PaymentIndexRequest;
use Modules\Payment\Http\Requests\Payment\PaymentShowRequest;
use Modules\Payment\Entities\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PaymentService
{
    public function __construct(public PaymentRepository $paymentRepository)
    {
    }

    public function index(PaymentIndexRequest $request)
    {
        try {
            $fields = $request->validated();
            return $this->paymentRepository->resolve_paginate(inputs: $fields);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(PaymentShowRequest $request, $payment_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);

            $withs = $withs ?? [];
            return $this->paymentRepository->withRelations(relations: $withs)->findOrFail($payment_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function createLinkPayment(PaymentCreateLinkRequest|array $request): ?string
    {
        try {
            if (is_array($request)) {
                $loginRequest = new PaymentCreateLinkRequest();
                $fields = Validator::make(data: $request,
                    rules: $loginRequest->rules(),
                    attributes: $loginRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $factor_id
             */
            extract($fields);

            /** @var FactorRepository $factorRepository */
            $factorRepository = resolve('FactorRepository');

            /** @var Factor $factor */
            $factor = $factorRepository->findOrFail($factor_id);
            /* --------------------------------------------------------- */
            /** @var PaymentPaypingService $PaymentPaypingService */
            $PaymentPaypingService = resolve('PaymentPaypingService');

            $clientRefId = $factor->id ?? null;
            $mobile = $factor?->user?->mobile ?? null;
            $amount = $factor->total_price ?? null;
            /** @var User $user */
            $user = $factor->user;
            $returnUrl = route('api_v1_payment_payments_confirm_payment_get') ?? null;
            $payerName = $factor?->user?->full_name ?? null;
            $description = isset($description) && filled($description) ? $description : $mobile;

            /** @var Payment $payment */
            $payment = $factor->payments()->create([
                'status'=>Payment::status_unpaid,
                'resnumber'=>Payment::resnumberUnique(),
                'amount'=>$amount,
                'user_id'=>$user->id,
            ]);


            $url = Str::random();
            /*$url = $PaymentPaypingService->createLinkPayment(
                clientRefId: $payment->resnumber,
                mobile: $mobile,
                amount: $amount,
                returnUrl: $returnUrl,
                description: $description,
                payerName: $payerName,
            );*/

            if(filled($url)){
                /** @var Factor $factor */
//                $factor = $payment->factor;
                $factor->reserves()->update(['status' => Reserve::status_reserving]);
            }

            return $url;
        } catch (Exception $exception) {
            dd($exception->getMessage());
            throw new $exception;
        }
    }

    public function confirmPayment(Request $request): bool
    {
        $fields = $request->all();
        $ref_id = $fields['refid'] ?? null;
        $resnumber = $client_ref_id = $fields['clientrefid'] ?? null;

        /** @var PaymentPaypingService $PaymentPaypingService */
        $PaymentPaypingService = resolve('PaymentPaypingService');

        ####################################################################
        /** @var Payment $payment */
        $payment = Payment::query()->where('resnumber',$resnumber)->firstOrFail();
        ####################################################################
        /** @var Factor $factor */
        $factor = $payment->factor;
        ####################################################################

        if($PaymentPaypingService->confirmPayment($ref_id,$factor->total_price)){
            $factor->status = Factor::status_paid;
            $payment->status = Payment::status_paid;
            $payment->save();
            $factor->payment_id_paid =$payment->id;
            $factor->save();
            return true;
        }
        return false;
    }

    public function destroy($payment_id): bool
    {
        DB::beginTransaction();
        try {
            # find payment
            /** @var Payment $payment */
            $payment = $this->paymentRepository->findOrFail($payment_id);
            # ###########
            # delete payment
            $this->paymentRepository->delete($payment);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function createLinkPaymentSadad(PaymentCreateLinkRequest $request): ?string
    {
        try {
            $fields = $request->validated();

            /**
             * @var $factor_id
             */
            extract($fields);

            /** @var FactorRepository $factorRepository */
            $factorRepository = resolve('FactorRepository');

            /** @var Factor $factor */
            $factor = $factorRepository->findOrFail($factor_id);
            /* --------------------------------------------------------- */

            /** @var PaymentPaypingService $PaymentPaypingService */
            $PaymentPaypingService = resolve('PaymentPaypingService');

            $clientRefId = $factor->id ?? null;
            $mobile = $factor?->user?->mobile ?? null;
            $amount = $factor->total_price ?? null;
            /** @var User $user */
            $user = $factor->user;
            $returnUrl = route('api_v1_payment_payments_confirm_payment_get') ?? null;
            $payerName = $factor?->user?->full_name ?? null;
            $description = isset($description) && filled($description) ? $description : $mobile;

            /** @var $payment $payment */
            $payment = $factor->payments()->create([
                'status'=>Payment::status_unpaid,
                'resnumber'=>Payment::resnumberUnique(),
                'amount'=>$amount,
                'user_id'=>$user->id,
            ]);

            $url = $PaymentPaypingService->createLinkPayment(
                clientRefId: $payment->resnumber,
                mobile: $mobile,
                amount: $amount,
                returnUrl: $returnUrl,
                description: $description,
                payerName: $payerName,
            );

            return $url;
        } catch (Exception $exception) {
            throw new $exception;
        }
    }
}
