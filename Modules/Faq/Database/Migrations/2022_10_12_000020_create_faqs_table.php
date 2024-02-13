<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Faq\Entities\Faq;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->comment('frequently asked questions');
            $table->id();
            $table->text('question')->unique()->comment('question');
            $table->text('answer')->nullable()->comment('answer');
            $table->tinyInteger('order')->nullable()->default(0)->comment('order');
            $table->tinyInteger('status')->nullable()->default(0)->comment('');
            $table->unsignedBigInteger('user_creator')->nullable()->comment('user_creator');
            $table->unsignedBigInteger('user_editor')->nullable()->comment('user_editor');
            $table->timestamps();
            $table->softDeletes();
        });
        $this->insertFakeFaqs();
    }

    public function insertFakeFaqs(): void
    {
        $faqs = [
            [
                'question' => 'چگونه می توانم رزرو کنم؟',
                'answer' => 'اماکن ورزشی مختلفی در سایت وجود دارد که با کلیک بر روی هر مکان می توانید امکانات، مشخصات و جدول رزرواسیون آن مکان را مشاهده کرده و با تکمیل مراحل، نسبت به رزرو/اجاره سانس مورد نظر خود اقدام نمایید.',
                'order' => 1,
                'status' => Faq::status_active,
            ],
            [
                'question' => 'آیا نیاز به هماهنگی خاصی بعد از انجام رزرو وجود دارد؟',
                'answer' => 'خیر؛ پس از پرداخت وجه و دریافت پیامک و کد پیگیری، رزرو شما تکمیل است و در صورت بروز هرگونه مشکل در سانس خریداری شده، همکاران ما با شما تماس خواهند گرفت.',
                'order' => 2,
                'status' => Faq::status_active,
            ],
            [
                'question' => 'آیا امکان پرداخت هزینه به صورت نقدی وجود دارد؟',
                'answer' => 'امکان پرداخت وجه به صورت نقدی میسر نبوده و هزینه باید به صورت آنلاین در فرایند خرید پرداخت شود.',
                'order' => 3,
                'status' => Faq::status_active,
            ],
            [
                'question' => 'آیا رزرو به صورت قطعی می باشد؟',
                'answer' => 'بله؛ رزرو ها و خرید های انجام شده قطعی تلقی می شوند و در صورت بروز هرگونه مشکل در سانس خریداری شده توسط شما، همکاران ما با شما تماس خواهند گرفت و هماهنگی های لازم با شما انجام می شود.',
                'order' => 4,
                'status' => Faq::status_active,
            ],
            [
                'question' => 'آیا امکان رزرو به صورت بلند مدت وجود دارد؟',
                'answer' => 'بله؛ در بخش مشاهده جدول رزرواسیون، می توانید با انتخاب دکمه "هفته بعد"، جدول هفته های آینده را مشاهده کرده و سانس مورد نظر خود را انتخاب و خریداری نمایید.',
                'order' => 5,
                'status' => Faq::status_active,
            ],
            [
                'question' => 'آیا امکان پیش پرداخت وجود دارد؟',
                'answer' => 'خیر؛ برای خرید و رزرو سالن، باید مبلغ به طور کامل در فرایند خرید پرداخت گردد.',
                'order' => 6,
                'status' => Faq::status_active,
            ],
            [
                'question' => 'پس از پرداخت، مبلغ از حساب بانکی کم شده اما رزرو ثبت نشده است. مشکل چیست؟',
                'answer' => 'درصورت بروز مشکل در فرایند پرداخت، وجه نهایتا تا 72 ساعت از سمت بانک به حساب شما بازگشت داده خواهد شد. در صورت عدم بازگشت وجه پس از مدت ذکر شده، با پشتیبانی تماس حاصل فرمایید.',
                'order' => 7,
                'status' => Faq::status_active,
            ],
            [
                'question' => 'آیا امکان کنسل کردن سانس خریداری شده وجود دارد؟',
                'answer' => 'کنسل کردن سانس به عوامل متعددی بستگی دارد که می توانید برای اطلاع از آنها، بخش "شرایط و قوانین" را مطالعه نمایید.',
                'order' => 8,
                'status' => Faq::status_active,
            ],
            [
                'question' => 'سانس های "در حال رزرو..." چه زمانی قابل رزرو هستند؟',
                'answer' => 'سانس های "در حال رزرو" حداکثر پس از 15 دقیقه، در دسترس و قابل خریداری خواهند بود.',
                'order' => 9,
                'status' => Faq::status_active,
            ],
            [
                'question' => 'سلام سالن چگونه کار می کند؟',
                'answer' => 'دسترسی آسان، مطمئن و سریع به اماکن ورزشی اعم از سالن های چند منظوره ، باشگاه های ورزشی جهت اجاره سانس به صورت حقیقی یا حقوقی از طریق سایت',
                'order' => 10,
                'status' => Faq::status_active,
            ],
        ];
        Faq::query()->insert($faqs);
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }

};
