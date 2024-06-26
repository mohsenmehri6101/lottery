<?php

namespace App\Providers\Customize;

use Faker\Provider\Base;

class PersianDataProvider extends Base
{
    public static array $names = [
        'محمد',
        'علی',
        'زهرا',
        'مریم',
        'رضا',
        'سارا',
        'امیر',
        'نازنین',
        'پارسا',
        'مهدی',
        'فاطمه',
        'حسن',
        'نگین',
        'بهزاد',
        'شیما',
        'علیرضا',
        'سمیرا',
        'کیان',
        'نادر',
        'لیلا',
        'محسن',
        'نسترن',
        'پیمان',
        'نگار',
        'علی‌رضا',
        'سحر',
        'امید',
        'نیما',
        'یاسمن',
        'مانا',
        'احسان',
        'ناهید',
        'آرمین',
        'آتنا',
        'امین',
        'راحله',
        'شهرام',
        'سمانه',
        'آرش',
        'شهلا',
        'مرتضی',
        'پریسا',
        'میلاد',
        'شادی',
        'امیرحسین',
        'رها',
        'کامران',
        'شایان',
        'مریم‌زهرا',
    ];

    public static array $cities = [
        'تهران',
        'اصفهان',
        'شیراز',
        'مشهد',
        'تبریز',
        'کرج',
        'قم',
        'اهواز',
        'کرمانشاه',
        'رشت',
        'زاهدان',
        'کرمان',
        'ارومیه',
        'همدان',
        'یزد',
        'ساری',
        'بندرعباس',
        'قزوین',
        'زنجان',
        'گرگان',
        'ملارد',
        'شهریار',
        'نجف آباد',
        'خرم‌آباد',
        'اردبیل',
        'قائم‌شهر',
        'خمینی‌شهر',
        'شاهرود',
        'قمصر',
        'نیشابور',
        'بابل',
        'یاسوج',
        'کرمان',
        'رامسر',
        'آمل',
        'ساری',
        'گیلانغرب',
        'دزفول',
        'بوشهر',
        'شهرکرد',
        'کاشان',
        'بجنورد',
        'مهاباد',
        'گلپایگان',
        'شوشتر',
        'میاندوآب',
        'شهریار',
        'ملایر',
        'ساوه',
    ];

    public static array $provinces = [
        'تهران',
        'اصفهان',
        'فارس',
        'خراسان رضوی',
        'آذربایجان شرقی',
        'کرج',
        'قم',
        'خوزستان',
        'کرمانشاه',
        'گیلان',
        'سیستان و بلوچستان',
        'کرمان',
        'آذربایجان غربی',
        'همدان',
        'یزد',
        'مازندران',
        'هرمزگان',
        'قزوین',
        'زنجان',
        'گلستان',
        'لرستان',
        'کهگیلویه و بویراحمد',
        'کردستان',
        'سمنان',
        'کوهگلو',
        'چهارمحال و بختیاری',
        'اردبیل',
        'خراسان شمالی',
        'البرز',
        'کرمانشاه',
    ];
    public static array $addresses = [
        'خیابان نارمک، پلاک 123',
        'خیابان انقلاب، ساختمان 45',
        'بلوار ولیعصر، واحد 789',
        'خیابان شهید بهشتی، طبقه 2، واحد 15',
        'خیابان فلسطین، ساختمان 30/4',
        'خیابان پاسداران، پلاک 67، واحد 3',
        'بلوار کشاورز، واحد 102',
        'خیابان امام خمینی، بنیاد رفاه، پلاک 5',
        'خیابان ولی عصر، برج میلاد، طبقه 7',
        'خیابان آزادی، واحد 22',
        'خیابان 17 شهریور، کوچه 12، پلاک 8',
        'خیابان سهروردی، ساختمان گلستان، واحد 30',
        'خیابان 15 خرداد، پلاک 55',
        'خیابان شریعتی، واحد 10A',
        'خیابان امیرکبیر، پلاک 25',
        'خیابان جمهوری، ساختمان انقلاب، طبقه 4',
        'خیابان لاله زار، واحد 7',
        'خیابان آیت‌الله کاشانی، برج چراغی، طبقه 3',
        'خیابان کارگر، پلاک 36',
        'خیابان جمالزاده، ساختمان آرمان، واحد 18',
        'خیابان جوانمرد قصاب، پلاک 10',
        'خیابان مطهری، واحد 42B',
        'خیابان گلستان، ساختمان شهیدان، طبقه 5',
        'خیابان شهید بهشتی، پلاک 30',
        'خیابان پیروزی، ساختمان امید، واحد 9',
        'خیابان سعدی، پلاک 7',
        'خیابان انقلاب، برج انقلاب، طبقه 10',
        'خیابان دکتر شریعتی، پلاک 18',
        'خیابان فتحی‌شقاقی، ساختمان پرواز، واحد 25',
        'خیابان مطهری، ساختمان میلاد، طبقه 6',
        'خیابان شریعتی، ساختمان شهریار، واحد 14',
        'خیابان کشاورز، پلاک 40',
        'خیابان جمهوری، برج پارسیان، طبقه 3',
        'خیابان لاله زار، پلاک 12',
        'خیابان آیت‌الله کاشانی، ساختمان دیانا، واحد 7',
        'خیابان کارگر، برج صنعت، طبقه 8',
        'خیابان جمالزاده، پلاک 22',
        'خیابان جوانمرد قصاب، ساختمان ترکمانی، واحد 11',
        'خیابان مطهری، واحد 55C',
        'خیابان گلستان، برج بهاران، طبقه 4',
        'خیابان شهید بهشتی، ساختمان رهام، واحد 8',
        'خیابان پیروزی، پلاک 15',
        'خیابان سعدی، برج سعدی، طبقه 12',
        'خیابان انقلاب، ساختمان آریانا، واحد 21',
        'خیابان دکتر شریعتی، ساختمان تهرانیان، طبقه 5',
        'خیابان فتحی‌شقاقی، واحد 8',
        'خیابان مطهری، پلاک 60',
        'خیابان شریعتی، ساختمان نگین، واحد 31',
        'خیابان کشاورز، برج سبز، طبقه 9',
        'خیابان جمهوری، پلاک 25',
        'خیابان لاله زار، ساختمان تیموری، واحد 6',
        'خیابان آیت‌الله کاشانی، برج روشنا، طبقه 7',
        'خیابان کارگر، ساختمان کیمیا، واحد 14',
        'خیابان جمالزاده، پلاک 40',
        'خیابان جوانمرد قصاب، ساختمان پارس، واحد 19',
        'خیابان مطهری، واحد 70A',
        'خیابان گلستان، پلاک 20',
        'خیابان شهید بهشتی، برج ایران، طبقه 14',
        'خیابان نارمک، پلاک 123',
        'خیابان انقلاب، ساختمان 45',
    ];
    public static array $short_addresses = [
        'کشتان',
        'میدان بسیج',
        'شهرک شاهد',
        'خیابان خیام',
        'گود زینل خان',
        'محله باقر',
        'علی گودرز',
        'شفاهی',
        'چهار راه مخابرات',
        'تلفنچی',
        'خیابان کوتاه',
        'خیابان کده',
        'محله گرباچی ها',
        'محله لرستان',
        'قله ساختمان قدیم',
        'قله ساختمان جدید',
        'محله باباچی ها',
        'نفیسکده ی محمدی',
        'ارزان چی محمد',
        'ساختمان عرب ها',
        'کیان سنتر ها',
        'سمت راست',
        'سمت چپ',
        'بن بست',
        'خیابان کوچک',
        'بازار',
        'کوچه باغ',
        'میدان مرکزی',
        'میدان شهدا',
        'خیابان اصلی',
        'بلوار مرکزی',
        'بلوار امیر',
        'خیابان سبز',
        'خیابان گل',
        'خیابان شهری',
        'بازار مرکزی',
        'خیابان پاساژ',
        'کوچه ساحلی',
        'خیابان کارگر',
        'خیابان صنعتی',
        'خیابان نبش',
        'خیابان کوچک',
        'کوچه کوچک',
        'کوچه بزرگ',
        'خیابان فرعی',
        'میدان بزرگ',
        'خیابان اصلی',
        'بلوار امیرکبیر',
        'کوچه مرکزی',
        'خیابان باغچه',
        'خیابان باران',
        'خیابان معمولی',
        'بازار شهر',
        'خیابان پاساژ',
        'خیابان گلستان',
        'میدان مرکزی',
        'کوچه آرامش',
        'خیابان همت',
        'خیابان صداقت',
        'خیابان صلح',
    ];

    public static array $sports = [
        'فوتبال',
        'دو میدانی',
        'شنا',
        'بسکتبال',
        'والیبال',
        'تنیس',
        'بدنسازی',
        'کیک‌باکسینگ',
        'تکواندو',
        'کاراته',
        'هاکی',
        'بوکس',
        'جودو',
        'پینگ‌پنگ',
        'کریکت',
        'گلف',
        'اسکیت',
        'اسکیت‌بورد',
        'اسکی آلپاین',
        'اسکی نوردی',
        'بیسبال',
        'رگبی',
        'اسکواش',
        'بولینگ',
        'فوتسال',
        'بدمینتون',
        'پزشکی',
        'فنون رزمی',
        'پرش با چمن',
        'پارکور',
    ];

    public static array $categories = [
        'ورزش‌های تیمی',
        'فیتنس و بدنسازی',
        'ورزش‌های آبی',
        'ورزش‌های رزمی',
        'ورزش‌های مکمل',
        'ورزش‌های زمستانی',
        'ورزش‌های همگانی',
        'ورزش‌های دوچرخه‌سواری',
        'ورزش‌های ماهیگیری',
        'ورزش‌های اسکیت',
        'توریسم و کمپینگ',
        'بازی‌های رایانه‌ای',
        'ورزش‌های اسب سواری',
        'ورزش‌های هوایی',
        'ورزش‌های تفریحی',
        'ورزش‌های طبیعت',
        'ورزش‌های آب‌وهوا',
        'ورزش‌های اسپرت',
        'ورزش‌های ژیمناستیک',
        'ورزش‌های بوکس',
        'ورزش‌های تنیس',
        'ورزش‌های بسکتبال',
        'ورزش‌های والیبال',
        'ورزش‌های تکواندو',
        'ورزش‌های کاراته',
        'ورزش‌های بدمینتون',
        'ورزش‌های استخر',
        'ورزش‌های سوارکاری',
        'ورزش‌های اسکی',
        'ورزش‌های کاتا',
        'ورزش‌های هاکی',
        'ورزش‌های شنا',
        'ورزش‌های جیکوندو',
        'ورزش‌های تیراندازی',
    ];

    public static array $tags = [
        'ورزش‌های تیمی',
        'فیتنس و بدنسازی',
        'شنا و آب‌رزمی',
        'رزمیات',
        'ورزش‌های مکمل',
        'ورزش‌های زمستانی',
        'ورزش‌های همگانی',
        'دوچرخه‌سواری',
        'ماهیگیری',
        'اسکیت',
        'کوه‌نوردی',
        'پرندگان‌شناسی',
        'سپکتاکل',
        'یوگا',
        'پیاده‌روی',
        'تیراندازی',
        'پارکور',
        'بازی‌های رومی',
        'فوتبال دستی',
        'پنکه‌آیروبیک',
        'بسکتبال',
        'والیبال',
        'تنیس',
        'بدنسازی',
        'کیک‌باکسینگ',
        'تکواندو',
        'کاراته',
        'هاکی',
        'بوکس',
        'جودو',
    ];

    public static array $keywords = [
        'تناسب اندام',
        'غذای سالم',
        'ورزش روزانه',
        'سلامتی',
        'روانشناسی',
        'تغذیه',
        'دوره‌های آموزشی',
        'ورزشگاه',
        'یوگا',
        'پیاده‌روی',
        'ماساژ',
        'کراتینه',
        'پرانتز',
        'فیتنس',
        'کاراته',
        'بدنسازی',
        'پیلاتس',
        'شنا',
        'کوهنوردی',
        'کایاک',
        'پینگ‌پنگ',
        'فوتبال',
        'بسکتبال',
        'تنیس',
        'بیس بال',
        'والیبال',
        'باشگاه ورزشی',
        'ترایاتلون',
        'تکواندو',
        'ژیمناستیک',
    ];
    public static array $descriptions = [
        'محل تمرین ورزشی با امکانات عالی',
        'بهترین باشگاه ورزشی در منطقه',
        'سالن ورزشی با امکانات حرفه‌ای',
        'مکان مناسب برای تمرینات ورزشی',
        'باشگاه ورزشی با چشم انداز زیبا',
        'سالن تمرینات ورزشی با استخر شنا',
        'فضای دل‌پذیر ورزش در باشگاه',
        'تمرینات ورزشی با مربیان حرفه‌ای',
        'ورزشگاه مجهز با تجهیزات برتر',
        'فضای سبز ورزشی در شهر',
        'سالن ورزشی با تخفیف ویژه',
        'ورزشگاه با امکانات ارتفاعی',
        'باشگاه با انواع تجهیزات تمرین',
        'مکان ورزشی در دل طبیعت',
    ];
    public static array $latitudes = [
        35.6895,
        48.8566,
        40.7128,
        51.5074,
        34.0522,
        41.8781,
        52.5200,
        37.7749,
        55.7558,
        38.9072,
        41.8919,
        37.7749,
        48.8588,
        52.3667,
        48.8566,
        51.1657,
        41.9028,
        34.0522,
        40.7128,
        37.7749,
        48.8566,
        52.5200,
        34.0522,
        41.8781,
        35.6895,
        38.9072,
        40.7128,
        51.5074,
        34.0522,
        48.8566,
        40.7128,
        51.5074,
        34.0522,
        41.8781,
        52.5200,
        37.7749,
        55.7558,
        38.9072,
        41.8919,
        37.7749,
        48.8588,
        52.3667,
        48.8566,
        51.1657,
        41.9028,
        34.0522,
        40.7128,
        37.7749,
        48.8566,
        52.5200,
        34.0522,
        41.8781,
        35.6895,
        38.9072,
        40.7128,
        51.5074,
        34.0522,
        48.8566,
        40.7128,
        51.5074,
        34.0522,
        41.8781,
        52.5200,
        37.7749,
        55.7558,
        38.9072,
        41.8919,
        37.7749,
        48.8588,
        52.3667,
        48.8566,
        51.1657,
        41.9028,
        34.0522,
        40.7128,
        37.7749,
        48.8566,
        52.5200,
        34.0522,
        41.8781,
        35.6895,
        38.9072,
        40.7128,
        51.5074,
        34.0522,
        48.8566,
        40.7128,
        51.5074,
        34.0522,
        41.8781,
        52.5200,
        37.7749,
        55.7558,
        38.9072,
        41.8919,
        37.7749,
        48.8588,
        52.3667,
        48.8566,
        51.1657,
        41.9028,
        34.0522,
        40.7128,
        37.7749,
        48.8566,
        52.5200,
        34.0522,
        41.8781,
        35.6895,
        38.9072,
        40.7128,
    ];
    public static array $longitudes = [
        51.3289,
        2.3522,
        74.0060,
        -0.1276,
        -118.2437,
        -87.6298,
        13.4050,
        -122.4194,
        37.6176,
        -77.0370,
        -87.6240,
        -122.4194,
        2.3522,
        4.9041,
        2.3522,
        10.4515,
        12.4964,
        -118.2437,
        -74.0060,
        -122.4194,
        2.3522,
        13.4050,
        51.3289,
        -77.0370,
        -74.0060,
        -0.1276,
        -87.6298,
        -118.2437,
        2.3522,
        -74.0060,
        2.3522,
        13.4050,
        -87.6298,
        -87.6240,
        13.4050,
        37.6176,
        -77.0370,
        -0.1276,
        74.0060,
        4.9041,
        2.3522,
        51.3289,
        -74.0060,
        10.4515,
        -122.4194,
        12.4964,
        -118.2437,
        -87.6298,
        -77.0370,
        -87.6240,
        2.3522,
        -0.1276,
        51.3289,
        4.9041,
        -74.0060,
        -122.4194,
        13.4050,
        -87.6298,
        51.3289,
        10.4515,
        2.3522,
        -74.0060,
        12.4964,
        -0.1276,
        -77.0370,
        -118.2437,
        37.6176,
        -0.1276,
        51.3289,
        -87.6240,
        2.3522,
        -87.6298,
        4.9041,
        2.3522,
        13.4050,
        10.4515,
        2.3522,
        51.3289,
        -74.0060,
        -77.0370,
        -0.1276,
        -118.2437,
        -87.6298,
        37.6176,
        4.9041,
        -87.6240,
        -0.1276,
        -74.0060,
        2.3522,
        -87.6298,
        10.4515,
        -122.4194,
        -118.2437,
        13.4050,
        2.3522,
        -77.0370,
        51.3289,
        -0.1276,
        -74.0060,
        4.9041,
        -87.6240,
        13.4050,
        37.6176,
        -87.6298,
        10.4515,
        2.3522,
        -0.1276,
        51.3289,
        -77.0370,
        -118.2437,
        -122.4194,
        -74.0060,
        2.3522,
        4.9041,
        13.4050,
        -87.6298,
        -0.1276,
        -87.6240,
        51.3289,
        10.4515,
        2.3522,
    ];

    public static array $gyms = [
        'باشگاه ورزشی شکوفه',
        'سالن فیتنس گلدن',
        'باشگاه بدنسازی آریا',
        'فیتنس کلاب ورزشی',
        'باشگاه ورزشی فرهنگ',
        'سالن تنیس ورزشی تابان',
        'باشگاه کاراته دیانا',
        'فیتنس ورزشگاه ریچارد',
        'باشگاه بوکس تایسون',
        'سالن بدنسازی شیراز',
        'باشگاه تنیس میکس',
        'سالن ورزشی اسکی',
        'باشگاه پینگ‌پنگ ایران',
        'فیتنس کلاب ورزشی تهران',
        'باشگاه ورزشی دلفین',
        'سالن بدنسازی آبان',
        'باشگاه کاراته شیران',
        'فیتنس ورزشگاه چمن',
        'باشگاه بوکس هندی',
        'سالن تنیس رویال',
        'باشگاه بدنسازی گلستان',
        'فیتنس ورزشگاه دی',
        'باشگاه تنیس آرش',
        'سالن ورزشی آسمان',
        'باشگاه کاراته گرگان',
        'فیتنس ورزشگاه خسروی',
        'باشگاه بوکس شهاب',
        'سالن بدنسازی بام',
        'باشگاه تنیس نسیم',
        'فیتنس ورزشگاه ستاره',
        'باشگاه ورزشی دانا',
        'سالن تنیس سفید',
        'باشگاه کاراته کیان',
        'فیتنس ورزشگاه یزد',
        'باشگاه بوکس اسپرت',
        'سالن بدنسازی زمان',
        'باشگاه تنیس علی',
        'فیتنس ورزشگاه مانا',
        'باشگاه ورزشی احسان',
        'سالن تنیس ناهید',
        'باشگاه کاراته آرمین',
        'فیتنس ورزشگاه آتنا',
        'باشگاه بوکس امیر',
        'سالن بدنسازی راحله',
        'باشگاه تنیس شهرام',
        'فیتنس ورزشگاه سمانه',
        'باشگاه ورزشی آرش',
        'سالن تنیس شهلا',
        'باشگاه کاراته مرتضی',
        'فیتنس ورزشگاه پریسا',
        'باشگاه بوکس میلاد',
        'سالن بدنسازی شادی',
        'باشگاه تنیس امیرحسین',
        'فیتنس ورزشگاه رها',
        'باشگاه ورزشی کامران',
        'سالن تنیس شایان',
        'باشگاه کاراته مریم‌زهرا',
        'فیتنس ورزشگاه نیما',
        'باشگاه بوکس یاسمن',
        'سالن بدنسازی مانا',
        'باشگاه تنیس احسان',
        'فیتنس ورزشگاه ناهید',
        'باشگاه ورزشی آرمین',
        'سالن تنیس آتنا',
        'باشگاه کاراته امین',
        'فیتنس ورزشگاه راحله',
        'باشگاه بوکس شهرام',
        'سالن بدنسازی سمانه',
        'باشگاه تنیس آرش',
        'فیتنس ورزشگاه شهلا',
        'باشگاه کاراته مرتضی',
    ];

    public static array $attributes = [
        'تجهیزات ورزشی حرفه‌ای',
        'سالن تمرین با اتاق خوش‌بو',
        'سالن تمرین با تجهیزات برنده',
        'کلاس‌های آموزشی متنوع',
        'سرویس‌های بهداشتی به روز',
        'مربیان حرفه‌ای و صاحب تجربه',
        'کلاس‌های گروهی متنوع',
        'برنامه‌های تمرین شخصی',
        'مکانی مناسب برای آموزش کودکان',
        'سالن با دمای کنترل شده',
        'تجهیزات ورزشی برند',
        'سرویس دهی 24 ساعته',
        'سرویس دهی 20 ساعته',
        'سرویس دهی 22 ساعته',
        'سرویس دهی 21 ساعته',
        'تناسب اندام و کاهش وزن',
        'تناسب اندام',
        'مسیرهای دوچرخه‌سواری',
        'دوچرخه سواری ها',
        'آبگرمکن و استخر',
        'پنجره‌های بزرگ با منظره طبیعت',
        'کافی‌شاپ و منطقه نشستن',
        'زون مخصوص تمرینات تریکس',
        'تجهیزات آماده‌سازی بدن',
        'واحدهای سلامتی و زیبایی',
        'باشگاه کودک و نگهداری از کودکان',
        'آموزش‌های تخصصی تنیس',
        'آموزش‌های تخصصی والیبال',
        'محل تمرینات کاراته',
        'کافی‌شاپ و منطقه تغذیه سالم',
        'سالن استراحت با جلسات آرامش',
        'تجهیزات حرفه‌ای ورزشی',
        'پیشخوان با وضوح بالا',
        'مراکز آموزشی پرورش اندام',
        'سالن با تجهیزات ایروبیک',
        'کلاس‌های تفریحی برای کودکان',
        'آموزش‌های تخصصی بوکس',
        'آموزش‌های تخصصی جیکوندو',
        'مراکز آموزشی بدمینتون',
        'تجهیزات بازی‌های تیمی',
        'کافی‌شاپ با منوی متنوع',
        'سرویس‌های بهداشتی ایمن',
        'تناسب اندام با مناظر طبیعت',
        'کمپ تفریحی در طبیعت',
        'کلاس‌های آموزش ترکیبی',
        'برنامه‌های تمرین متنوع',
        'مراکز آموزشی شنا',
        'تجهیزات تمرینات کاردیو',
        'سرویس‌های بهداشتی لوکس',
        'پیشخوان با دستگاه‌های مدرن',
        'مربیان با تجربه در بوکس',
        'سالن با دمای سفارشی',
        'تخت‌های آرامش در استراحتگاه',
        'کلاس‌های تمرین در آب',
        'کافی‌شاپ با منظره گلف',
        'زون مخصوص تمرینات جسمانی',
        'تجهیزات بازی‌های ورزشی',
        'مراکز تفریحی با مناظر طبیعی',
        'سالن تمرین با نورپردازی',
        'کلاس‌های آموزشی یوگا',
        'برنامه‌های تمرینی متخصصانه',
        'مکانی مناسب برای تیم‌های ورزشی',
        'کافی‌شاپ با انواع نوشیدنی‌ها',
        'سرویس‌های بهداشتی مدرن',
        'تناسب اندام و خدمات اسپا',
        'باشگاه برند و معتبر',
        'آموزش‌های تخصصی اسکی',
        'آبگرمکن با تنظیم دما',
        'مسیرهای دوچرخه‌سواری شهری',
        'پنجره‌های طبیعت باز',
        'کافی‌شاپ با منوی سالم',
        'زون مخصوص تمرینات بدنسازی',
        'تجهیزات آماده‌سازی بدن حرفه‌ای',
        'واحدهای زیبایی و آرایشگاه',
        'باشگاه کودک با اتاق بازی',
        'آموزش‌های تخصصی تکواندو',
        'آموزش‌های تخصصی کراته',
        'مراکز آموزشی بدمینتون حرفه‌ای',
    ];

    public static array $slider_titles = [
        'محصولات ورزشی برتر در اینجا',
        'ورزش با انرژی و سلامتی',
        'تجهیزات ورزشی برای هر سطح فعالیت',
        'بهبود عضلات و اندام با ما',
        'پوشاک ورزشی را با ما تجربه کنید',
        'بهترین ورزش‌های تناسب اندام',
        'ماشین‌آلات و تجهیزات ژیمناستیک',
        'ورزش‌های آبی برای سلامتی',
        'مکمل‌های ورزشی برای حداکثر کارایی',
        'بدن‌سازی و تقویت عضلات با ما',
        'لباس‌های ورزشی را برای شما آورده‌ایم',
        'ورزش در خانه با تجهیزات مناسب',
        'ورزش‌های هوایی برای شادی و سلامتی',
        'پرسش‌های رایج در مورد تمرینات ورزشی',
        'ورزش‌های تیمی و جماعی برای همگان',
        'ورزش برای تناسب اندام و آرامش روحی',
        'محافظت از جسم در تمرینات ورزشی',
        'ورزش‌های فیتنس و کاردیو برای شادابی',
        'ورزش‌های رزمی و خوددفاع برای مهارت',
        'ماشین‌آلات تمرینات کاردیویی برتر',
        'ورزش‌های انقلابی و متفاوت',
        'ورزش‌های چالشی برای افراد ماجراجو',
        'ورزش‌های استراحت و آرامش برای ذهن',
        'ورزش‌های روزمره برای سلامتی مداوم',
    ];

    public static array $slider_texts = [
        'از ما تجهیزات ورزشی برتری را تجربه کنید. محصولات با کیفیت برای تمرینات شما در دسترس است. با ما همراه شوید و تمرینات خود را به سطح بالاتری برسانید.',
        'فیتنس و سلامتی در دستان شماست. با تمرینات روزانه و مکمل‌های ورزشی مناسب، بهترین شکل خود را بیابید. برای آرامش روحی و جسمی سالم، از ما راهنمایی بخواهید.',
        'ورزش در محیطی دلپذیر و متنوع. با تجهیزات ژیمناستیک و تمرینات تناسب اندام، به راحتی به اهداف خود برسید. شروع کنید و مشاهده کنید چقدر فرق می‌کند!',
        'برای شروع به تمرینات ورزشی، لباس‌های مناسب آماده کنید. ما لباس‌های ورزشی با طراحی‌های جذاب و راحتی بی‌نظیر داریم. با ما همیشه بهترین را داشته باشید.',
        'ورزش‌های آبی یکی از بهترین راه‌ها برای تقویت عضلات و سلامتی است. با شنا و سایر ورزش‌های آبی، به یک زندگی فعال و سالم خوش آمدید.',
        'تمرینات کاردیو و فیتنس می‌توانند به شما کمک کنند تا چربی‌های اضافی را سوزانده و قلب و عروق خود را بهبود ببخشید. شروع کنید و تغییرات را تجربه کنید!',
        'در ورزش همیشه به چالش کشیده می‌شویم و بهترین خود را نشان می‌دهیم. از ورزش‌های رزمی تا ورزش‌های تیمی، همه را با انگیزه و انرژی شروع کنید.',
        'مراقبت از جسم و روح در طول تمرینات ورزشی بسیار مهم است. با تجهیزات محافظتی و ماساژ‌های آرامش بخش، بدن خود را محافظت کنید.',
        'ورزش‌های چالشی مثل پارکور و پاراگلایدینگ برای افراد ماجراجو و علاقه‌مند به اکتشافات مناسب هستند. با ما این تجربه هیجان‌انگیز را تجربه کنید!',
        'ورزش‌های استراحتی مثل یوگا و مدیتیشن به شما کمک می‌کنند که روحیه‌تان را بهبود ببخشید و استرس را کاهش دهید. شاد و سلامت باشید.',
        'ورزش در خانه همیشه یک انتخاب خوب است. با ماشین‌آلات و تجهیزات مناسب، می‌توانید در خانه تمرین کرده و به فرم ایده‌آل خود برسید.',
        'ورزش‌های هوایی مثل پاراگلایدینگ و اسکای‌دایوینگ برای علاقه‌مندان به آسمان پریدن و حس آزادی مناسب هستند. با ما این حس را تجربه کنید!',
        'ورزش برای ما یک عشق است. از ورزشاتان لذت ببرید و همیشه بهترین خود را نشان دهید. ما اینجا هستیم تا شما را در این مسیر یاری کنیم.',
        'تمرینات فیتنس در فضای باز همیشه جذابیت دارد. با تمرینات در پارک‌ها و فضاهای طبیعی، تازه‌هوایی بخشیده و به تناسب اندام خود برسید.',
        'ورزش‌های زمستانی مثل اسکی و اسکی نوردی برای علاقه‌مندان به برف و تجربه‌ی این امکانات دیدنی مناسب هستند. شما همراه ما باشید.',
    ];

    public static function sliderTitleUnique()
    {
        $random_title = static::sliderTitle();
        while (\Modules\Slider\Entities\Slider::query()->where('title', $random_title)->exists()) {
            $random_title = static::sliderTitle();
        }
        return $random_title;
    }

    public static function sliderTextUnique()
    {
        $random_text = static::sliderText();
        while (\Modules\Slider\Entities\Slider::query()->where('text', $random_text)->exists()) {
            $random_text = static::sliderText();
        }
        return $random_text;
    }

    public static function sliderTitle()
    {
        return static::randomElement(static::$slider_titles);
    }

    public static function sliderText()
    {
        return static::randomElement(static::$slider_texts);
    }

    public static array $mobilePrefixes = [
        '0936',
        '0935',
        '0937',
        '0915',
        '0912',
        '0901',
        '0910',
        '0913',
    ];

    public function persianAttribute()
    {
        return static::randomElement(static::$attributes);
    }

    public function persianAttributeUnique()
    {
        $uniqueAttribute = $this->persianAttribute();
        $i = 0;
        $count_attributes = count(static::$attributes);
        while ($i < $count_attributes && \Modules\Gym\Entities\Attribute::query()->where('name', $uniqueAttribute)->doesntExist()) {
            $i++;
            $uniqueAttribute = $this->persianAttribute();
        }
        return $uniqueAttribute;
    }

    public function persianGym()
    {
        return static::randomElement(static::$gyms);
    }

    public function persianGymUnique()
    {
        $uniqueGym = $this->persianGym();
        while (\Modules\Gym\Entities\Gym::query()->where('name', $uniqueGym)->doesntExist()) {
            $uniqueGym = $this->persianGym();
        }
        return $uniqueGym;
    }

    public function persianDescription()
    {
        return static::randomElement(static::$descriptions);
    }

    public function persianLatitude()
    {
        return static::randomElement(static::$latitudes);
    }

    public function persianLongitude()
    {
        return static::randomElement(static::$longitudes);
    }

    public function persianKeyword()
    {
        return static::randomElement(static::$keywords);
    }

    public function persianKeywordUnique()
    {
        $uniqueKeyword = $this->persianKeyword();
        while (\Modules\Gym\Entities\Keyword::query()->where('keyword', $uniqueKeyword)->doesntExist()) {
            $uniqueKeyword = $this->persianKeyword();
        }
        return $uniqueKeyword;
    }

    public function persianTag()
    {
        return static::randomElement(static::$tags);
    }

    public function persianTagUnique()
    {
        $uniqueTag = $this->persianTag();
        while (\Modules\Gym\Entities\Tag::query()->where('tag', $uniqueTag)->doesntExist()) {
            $uniqueTag = $this->persianTag(); // Regenerate the tag name
        }
        return $uniqueTag;
    }

    public function persianCategory()
    {
        return static::randomElement(static::$categories);
    }

    public function persianCategoryUnique()
    {
        $uniqueName = $this->persianCategory();
        while (\Modules\Gym\Entities\Category::query()->where('name', $uniqueName)->doesntExist()) {
            $uniqueName = $this->persianCategory(); // Regenerate the name
        }
        return $uniqueName;
    }

    public function persianSport()
    {
        return static::randomElement(static::$sports);
    }

    public function persianSportUnique()
    {
        $uniqueName = $this->persianSport();
        while (\Modules\Gym\Entities\Sport::query()->where('name', $uniqueName)->doesntExist()) {
            $uniqueName = $this->persianSport(); // Regenerate the name
        }
        return $uniqueName;
    }

    public function persianName()
    {
        return static::randomElement(static::$names);
    }

    public function persianCity()
    {
        return static::randomElement(static::$cities);
    }

    public function persianMobileNumber(): string
    {
        $prefix = static::randomElement(static::$mobilePrefixes);
        $randomDigits = mt_rand(1000000, 9999999); // Generates a random 10-digit number
        return $prefix . str_pad($randomDigits, 7, '0', STR_PAD_LEFT);
    }

    public function persianMobileUniqueNumber(): string
    {
        do {
            $mobileNumber = $this->persianMobileNumber();
        } while (\Modules\Authentication\Entities\User::query()->where('mobile', $mobileNumber)->exists());

        return $mobileNumber;
    }

    public function persianProvince()
    {
        return static::randomElement(static::$provinces);
    }

    public function persianAddress()
    {
        return static::randomElement(static::$addresses);
    }

    public function persianُShortAddress()
    {
        return static::randomElement(static::$short_addresses);
    }

}
