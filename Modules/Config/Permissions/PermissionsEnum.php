<?php

namespace Modules\Config\Permissions;

use App\Permissions\EnumToArray;

enum PermissionsEnum: string
{
    use EnumToArray;

    # config
    case route_config_index = 'روت نمایش لیست کانفیگ ها';
    case route_config_show = 'روت نمایش کانفیگ(تک کانفیگ)';
    case route_config_store = 'روت ذخیره کانفیگ';
    case route_config_update = 'روت ویرایش کانفیگ';
    case route_config_delete = 'روت حذف کانفیگ';
}
