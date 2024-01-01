<?php

namespace Modules\Authorization\Permissions;

use App\Permissions\EnumToArray;

enum PermissionsEnum: string
{
    use EnumToArray;

    # permission
    case route_permission_index = 'روت نمایش لیست سطح دسترسی ها';
    case route_permission_show = 'روت نمایش سطح دسترسی(تک سطح دسترسی)';
    case route_permission_store = 'روت ذخیره سطح دسترسی';
    case route_permission_update = 'روت ویرایش سطح دسترسی';
    case route_permission_delete = 'روت حذف سطح دسترسی';
    case route_permission_import_excel = 'ورود اطلاعات به وسیله اکسل';
    case route_get_permissions_user = 'گرفتن سطوح دسترسی کاربر';
    case route_permission_export_excel = 'خروج سطوح دسترسی به وسیله اکسل';

    case route_permission_sync_to_user = 'تنظیم سطوح دسترسی برای کاربر';
    case route_permission_delete_to_user = 'حذف سطوح دسترسی برای کاربر';
    case route_permission_sync_to_role = 'تنظیم سطوح دسترسی برای نقش';
    case route_permission_delete_to_role = 'حذف سطوح دسترسی برای نقش';
    # permission

    # permission_block
    case route_permission_block_index = 'روت نمایش سطوح دسترسی بلاک شده';
    case route_permission_block_show = 'روت نمایش تکی سطح دسترسی بلاک شده';
    case route_permission_block_store = 'روت ذخیره سطح دسترسی بلاک شده';
    case route_permission_block_update = 'روت ویرایش سطح دسترسی بلاک شده';
    case route_permission_block_delete = 'روت حذف سطح دسترسی بلاک شده';
    # permission_block

    # role
    case route_role_index = 'روت نمایش لیست نقش ها';
    case route_role_show = 'روت نمایش نقش(تک نقش)';
    case route_role_store = 'روت ذخیره نقش';
    case route_role_update = 'روت ویرایش نقش';
    case route_role_delete = 'روت حذف نقش';

    case route_role_sync_to_user = 'تنظیم نقش برای کاربر';
    case route_role_sync_to_user_with_condition = 'تنظیم نقش برای کاربر به همراه محدودیت(هر کاربر در هر گروه فقط یک نقش میتواند بگیرد)';
    case route_role_sync_to_user_with_delete_with_condition = '(مثل بالایی ابتدا نقش در گروه را حذف میکند سپس اجازه اضافه کردن میدهد)تنظیم نقش برای کاربر به همراه محدودیت(هر کاربر در هر گروه فقط یک نقش میتواند بگیرد)';
    case route_role_delete_to_user = 'حذف نقش برای کاربر';

     # role_group
    case route_role_group_index = 'روت نمایش لیست گروه های مربوط به نقش';
    case route_role_group_show = 'روت نمایش گروه مربوط به نقش(تک گروه مربوط به نقش)';
    case route_role_group_store = 'روت ذخیره گروه مربوط به نقش';
    case route_role_group_update = 'روت ویرایش گروه مربوط به نقش';
    case route_role_group_delete = 'روت حذف گروه مربوط به نقش';

    case route_role_group_sync_to_role = 'تنظیم گروه برای نقش(روش sync)';
    case route_role_group_attach_to_role = 'تنظیم گروه برای نقش(روش attach)';
    case route_role_group_delete_to_role = 'حذف گروه برای نقش';

    case route_role_group_sync_to_permission = 'تنظیم گروه برای سطح دسترسی (sync)';
    case route_role_group_attach_to_permission = 'تنظیم گروه برای سطح دسترسی (attach)';
    case route_role_group_delete_to_permission = 'حذف گروه برای سطح دسترسی';
}
