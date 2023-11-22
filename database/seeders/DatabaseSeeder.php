<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    const COLORS = [
        ['id' => 1, 'title' => 'أحمر'],
        ['id' => 2, 'title' => 'أصفر'],
        ['id' => 3, 'title' => 'بمبي'],
        ['id' => 4, 'title' => 'بترولي'],
        ['id' => 5, 'title' => 'أوف وايت'],
        ['id' => 6, 'title' => 'أزرق'],
        ['id' => 7, 'title' => 'موف'],
        ['id' => 8, 'title' => 'كافيه'],
        ['id' => 9, 'title' => 'لبني'],
        ['id' => 10, 'title' => 'رصاصي'],
        ['id' => 11, 'title' => 'جنزاري'],
        ['id' => 12, 'title' => 'بدي روز'],
        ['id' => 13, 'title' => 'كشمير'],
    ];

    const SIZES = [
        ['id' => 1, 'title' => 'S'],
        ['id' => 2, 'title' => 'M'],
        ['id' => 3, 'title' => 'L'],
        ['id' => 4, 'title' => 'XL'],
        ['id' => 5, 'title' => '2XL'],
        ['id' => 6, 'title' => '3XL'],
        ['id' => 7, 'title' => '4XL'],
    ];

    const CLOTH_DEFECTS = [
        ['id' => 1, 'title' => 'الريجة'],
        ['id' => 2, 'title' => 'البانشر'],
        ['id' => 3, 'title' => 'العقدة'],
        ['id' => 4, 'title' => 'الطيرة'],
        ['id' => 5, 'title' => 'الثقوب'],
        ['id' => 6, 'title' => 'تسقيط'],
        ['id' => 7, 'title' => 'تنسيل'],
        ['id' => 8, 'title' => 'ثبوت اللون'],
        ['id' => 9, 'title' => 'الوصلات'],
        ['id' => 10, 'title' => 'الاتساخ'],
        ['id' => 11, 'title' => 'فلوك'],
        ['id' => 12, 'title' => 'عرض البرسل'],
        ['id' => 13, 'title' => 'الزيت'],
        ['id' => 14, 'title' => 'التيك الأسود'],
        ['id' => 15, 'title' => 'عروض مختلفة'],
        ['id' => 16, 'title' => 'الصداء'],
        ['id' => 17, 'title' => 'تنميل الصبغة'],
        ['id' => 18, 'title' => 'اختلاف اللون العرضي'],
        ['id' => 19, 'title' => 'اختلاف اللون الطولي'],
    ];

    const PREPARATION_DEFECTS = [
        ['id' => 1, 'title' => 'خطأ نمرة رفيعة'],
        ['id' => 2, 'title' => 'خطأ نمرة سميكة'],
        ['id' => 3, 'title' => 'فتلة مخلوطة'],
        ['id' => 4, 'title' => 'فتلة محلولة'],
        ['id' => 5, 'title' => 'عقد تراجي'],
        ['id' => 6, 'title' => 'نقط سواء في الفتل'],
        ['id' => 7, 'title' => 'نسبة الخلط غير منتظمة'],
        ['id' => 8, 'title' => 'لحمة متباعدة'],
        ['id' => 9, 'title' => 'حدفات غير منتظمة المسافات بين الخطوط'],
        ['id' => 10, 'title' => 'فراغ خال من اللحمات'],
        ['id' => 11, 'title' => 'دقات'],
        ['id' => 12, 'title' => 'اختلاف لحمة'],
        ['id' => 13, 'title' => 'ثقوب'],
        ['id' => 14, 'title' => 'عقد لحمة'],
        ['id' => 15, 'title' => 'قطع وصل'],
        ['id' => 16, 'title' => 'لحمة مقوسة'],
        ['id' => 17, 'title' => 'لحمة ليست على استقامة واحدة في طريق البرسل'],
        ['id' => 18, 'title' => 'اختلاف الشد على الخيوط'],
        ['id' => 19, 'title' => 'فتل زائدة'],
        ['id' => 20, 'title' => 'خطأ لقي'],
        ['id' => 21, 'title' => 'خطأ تطريح'],
    ];

    const CUTTING_DEFECTS = [
        ['id' => 1, 'title' => 'شرشرة'],
        ['id' => 2, 'title' => 'عدم تماثل'],
        ['id' => 3, 'title' => 'اعوجاج في شكل القص'],
        ['id' => 4, 'title' => 'قطع تالفة'],
        ['id' => 5, 'title' => 'عيب في البرسل'],
        ['id' => 6, 'title' => 'فورت غير موجود'],
        ['id' => 7, 'title' => 'فورت عميق'],
        ['id' => 8, 'title' => 'نقص طول أو عرض'],
        ['id' => 9, 'title' => 'قطع هربانة'],
        ['id' => 10, 'title' => 'قص غير جيد'],
    ];
    const NEEDLE_DEFECTS = [
        ['id' => 1, 'title' => 'تفويت غرزة'],
        ['id' => 2, 'title' => 'ظهور خيط المكوك على خيط الحرير'],
        ['id' => 3, 'title' => 'بقع زيت'],
        ['id' => 4, 'title' => 'ترحيل الرسمة'],
        ['id' => 5, 'title' => 'ترحيل الأبليك'],
        ['id' => 6, 'title' => 'تنسيل'],
        ['id' => 7, 'title' => 'عدم ضبط الشد'],
        ['id' => 8, 'title' => 'عدم ضبط ألوان الفيلم'],
        ['id' => 9, 'title' => 'كثافة الغرز'],
        ['id' => 10, 'title' => 'ترحيل في مكان التطريز'],
        ['id' => 11, 'title' => 'تشطيب أبليك سيء'],
    ];
    const OPERATION_DEFECTS = [
        ['id' => 1, 'title' => 'شريحه عريضه وكنزة'],
        ['id' => 2, 'title' => 'مقاس بنطلون'],
        ['id' => 3, 'title' => 'غرزة نطة'],
        ['id' => 4, 'title' => 'غرزة مقطوعة'],
        ['id' => 5, 'title' => 'أورليه مسقط'],
        ['id' => 6, 'title' => 'خياطة مفتوحة'],
        ['id' => 7, 'title' => 'تشريب في الكمر'],
        ['id' => 8, 'title' => 'قفلة غير منتظمة في أورليه'],
        ['id' => 9, 'title' => 'مقاس في مقاس'],
        ['id' => 10, 'title' => 'زرار مرحل'],
        ['id' => 11, 'title' => 'عروة غير سليمة'],
        ['id' => 12, 'title' => 'عروة مقفولة'],
        ['id' => 13, 'title' => 'بانده غير سليمة'],
        ['id' => 14, 'title' => 'فرق طول رجل ورجل'],
        ['id' => 15, 'title' => 'كتف عريض'],
        ['id' => 16, 'title' => 'كتف كنز'],
        ['id' => 17, 'title' => 'نكت ملوح'],
        ['id' => 18, 'title' => 'نكت ناقص'],
        ['id' => 19, 'title' => 'سوستة معوجة'],
        ['id' => 20, 'title' => 'اختلاف لون'],
        ['id' => 21, 'title' => 'غرز عائمة'],
        ['id' => 22, 'title' => 'تخريم إبرة'],
        ['id' => 23, 'title' => 'شريط غير منتظم'],
        ['id' => 24, 'title' => 'شريط منفلت'],
        ['id' => 25, 'title' => 'رش غير منتظم'],
        ['id' => 26, 'title' => 'أورليه غير منتظم'],
        ['id' => 27, 'title' => 'وضع الجيب سيئ'],
        ['id' => 28, 'title' => 'عدم تماثل للقطعة'],
        ['id' => 29, 'title' => 'تركيب بنده غير منتظمة'],
        ['id' => 30, 'title' => 'نكت عناية خطأ'],
        ['id' => 31, 'title' => 'نكت علامة تجارية خطأ'],
    ];

    const IRON_DEFECTS = [
        ['id' => 1, 'title' => 'اتساخ'],
        ['id' => 2, 'title' => 'لسعة'],
        ['id' => 3, 'title' => 'حرق'],
        ['id' => 4, 'title' => 'كرمشة'],
        ['id' => 5, 'title' => 'بقع معدنية'],
        ['id' => 6, 'title' => 'لمعان'],
        ['id' => 7, 'title' => 'تكسير'],
        ['id' => 8, 'title' => 'بخار زيادة'],
        ['id' => 9, 'title' => 'بلل'],
        ['id' => 10, 'title' => 'عدم ضبط المظهرية'],
        ['id' => 11, 'title' => 'علامات ضغط'],
        ['id' => 12, 'title' => 'انكماش'],
    ];
    const PACKAGING_DEFECTS = [
        ['id' => 1, 'title' => 'مظهرية سيئة'],
        ['id' => 2, 'title' => 'عدم وجود نكت عناية'],
        ['id' => 3, 'title' => 'عدم وجود نكت مقاس'],
        ['id' => 4, 'title' => 'عدم وجود نكت أساسي عميل'],
        ['id' => 5, 'title' => 'نسبة رشيو خطأ'],
        ['id' => 6, 'title' => 'زيادة أو نقص في العدد الكلي للمقاس'],
        ['id' => 7, 'title' => 'مكواه سيئة'],
        ['id' => 8, 'title' => 'تطبيق سيئ'],
        ['id' => 9, 'title' => 'اكسسوار مفقود'],
        ['id' => 10, 'title' => 'دبوس ناقص'],
        ['id' => 11, 'title' => 'بقعة زيت أو عرق أو اتساخ'],
        ['id' => 12, 'title' => 'قطع مبللة داخل الكيس'],
        ['id' => 13, 'title' => 'زيادة او نقص فى العدد الكلى للون'],
        ['id' => 14, 'title' => 'تعبئة خطأ'],
        ['id' => 15, 'title' => 'كارت علامة تجارية خطأ'],
        ['id' => 16, 'title' => 'كارت سعر خطأ'],
        ['id' => 17, 'title' => 'عدم توافق المقاسات'],
        ['id' => 18, 'title' => 'شماعة خطأ'],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Ramy',
            'email' => 'ramy@test.com',
            'password' => '0000'
        ]);

        DB::table('colors')->insert(self::COLORS);
        DB::table('sizes')->insert(self::SIZES);
        DB::table('cloth_defects')->insert(self::CLOTH_DEFECTS);
        DB::table('preparation_defects')->insert(self::PREPARATION_DEFECTS);
        DB::table('cutting_defects')->insert(self::CUTTING_DEFECTS);
        DB::table('needle_defects')->insert(self::NEEDLE_DEFECTS);
        DB::table('operation_defects')->insert(self::OPERATION_DEFECTS);
        DB::table('iron_defects')->insert(self::IRON_DEFECTS);
        DB::table('packaging_defects')->insert(self::PACKAGING_DEFECTS);
    }
}
