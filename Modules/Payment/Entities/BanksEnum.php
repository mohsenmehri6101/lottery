<?php

namespace Modules\Payment\Entities;

use App\Permissions\EnumToArray;
enum BanksEnum: string
{
    use EnumToArray;
    case MELLI = 'ملی';
    case MELLAT = 'ملت';
    case SADERAT = 'صادرات';
    case KESHAVARZI = 'کشاورزی';
    case MASKAN = 'مسکن';
    case SANAT_O_MADAN = 'صنعت و معدن';
    case TOSEE_SADERAT = 'توسعه صادرات ایران';
    case SAMAN = 'سامان';
    case PARSIAN = 'پارسیان';
    case KARAFARIN = 'کارآفرین';
    case SEPAH = 'سپه';
    case EGHTESAD_NOVIN = 'اقتصاد نوین';
    case ANSAR = 'انصار';
    case SARMAYEH = 'سرمایه';
    case HEKMAT_IRANIAN = 'حکمت ایرانیان';
    case REFAH_KARGARAN = 'رفاه کارگران';
    case GHARZOOLHASANE_MEHR_EQTESAD = 'قرض‌الحسنه مهر اقتصاد';
    case SHAHR = 'شهر';
    case TOSEE_TAAVON = 'توسعه تعاون';
    case POST_BANK = 'پست بانک ایران';
    case TEJARAT = 'تجارت';
    case EDBI = 'توسعه صادرات بانک ملی ایران';
    case PASARGAD = 'پاسارگاد';
    case SINA = 'سینا';
    case SARMAYEH_IRAN = 'سرمایه ایران';
    case KHAVARMIANE = 'خاورمیانه';
    case IRAN_ZAMIN = 'ایران زمین';
    case KOSAR = 'کوثر';
    case MEHR_IRAN = 'مهر ایران';
}
