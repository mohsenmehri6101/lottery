<?php

namespace Modules\Payment\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Authentication\Entities\User;
use Modules\Gym\Entities\Reserve;
use Modules\Payment\Entities\Factor;
use Modules\Payment\Entities\Transaction;
use Modules\Payment\Http\Repositories\FactorRepository;
use Modules\Payment\Http\Repositories\PaymentRepository;
use Modules\Payment\Http\Requests\Payment\PaymentCreateLinkRequest;
use Modules\Payment\Http\Requests\Payment\PaymentIndexRequest;
use Modules\Payment\Http\Requests\Payment\PaymentShowRequest;
use Modules\Payment\Entities\Payment;
use Illuminate\Support\Facades\Validator;

class PaymentService
{
    const USER_ID_SYSTEM = 1;
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
            # todo better set description of factor and total price and dated at and reserve template id and gym_id
            $description = isset($description) && filled($description) ? $description : $mobile;
            # Updated description to include more relevant information

            /** @var Payment $payment */
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

            if(filled($url)){
                /** @var Factor $factor */
                $factor->reserves()->update(['status' => Reserve::status_reserving]);
            }

            return $url;
        } catch (Exception $exception) {
            throw new $exception;
        }
    }
    public function confirmPayment(Request $request): bool
    {
        $fields = $request->all();
        $ref_id = $fields['refid'] ?? null;
        $resnumber = $client_ref_id = $fields['clientrefid'] ?? null;
        ####################################################################
        /** @var PaymentPaypingService $PaymentPaypingService */
        $PaymentPaypingService = resolve('PaymentPaypingService');
        ####################################################################
        /** @var Payment $payment */
        $payment = Payment::query()->where('resnumber',$resnumber)->firstOrFail();
        ####################################################################
        /** @var Factor $factor */
        $factor = $payment->factor;
        ####################################################################
        $factor_id=$factor->payments()->latest()->first()->id;
        if($PaymentPaypingService->confirmPayment(authority: $ref_id,amount: $factor->total_price,factor_id: $factor_id)){
            $factor->status = Factor::status_paid;
            $payment->status = Payment::status_paid;
            $payment->save();
            $factor->payment_id_paid =$payment->id;
            $factor->save();

            self::save_transactions($factor);

            return true;
        }
        ####################################################################
        return false;
    }

    public static function save_transactions(Factor $factor): void
    {
        // محاسبه مقدار سود مسئول سالن
        $profit_share_percentage = $factor->gym->profit_share_percentage;
        $gym_profit = $factor->total_price * ($profit_share_percentage / 100);

        // ثبت رکورد در جدول تراکنش‌ها برای مسئول سالن
        $gym_transaction = new Transaction();
        $gym_transaction->user_id = $factor->gym->user_id;
        $gym_transaction->amount = $gym_profit;
        $gym_transaction->save();

        // محاسبه مقدار درآمد مسئول سایت
        $site_income = $factor->total_price - $gym_profit;

        // ثبت رکورد در جدول تراکنش‌ها برای مسئول سایت
        $site_transaction = new Transaction();

        $site_transaction->user_id = self::USER_ID_SYSTEM; // مسئول سایت با id 1
        $site_transaction->amount = $site_income;
        $site_transaction->save(); // محاسبه مقدار سود مسئول سالن
        $profit_share_percentage = $factor->gym->profit_share_percentage;
        $gym_profit = $factor->total_price * $profit_share_percentage / 100;

        // ثبت رکورد در جدول تراکنش‌ها برای مسئول سالن
        $gym_transaction = new Transaction();
        $gym_transaction->user_id = $factor->gym->user_id;
        $gym_transaction->amount = $gym_profit;
        $gym_transaction->save();

        // محاسبه مقدار درآمد مسئول سایت
        $site_income = $factor->total_price - $gym_profit;

        // ثبت رکورد در جدول تراکنش‌ها برای مسئول سایت
        $site_transaction = new Transaction();
        $site_transaction->user_id = 1; // مسئول سایت با id 1
        $site_transaction->amount = $site_income;
        $site_transaction->save();
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
