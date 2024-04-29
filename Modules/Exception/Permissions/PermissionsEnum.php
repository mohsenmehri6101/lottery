<?php

namespace Modules\Exception\Permissions;

use App\Permissions\EnumToArray;

enum PermissionsEnum: string
{
    use EnumToArray;

    # exception
    case  route_permission_exception_index = 'نمایش exceptions';
    case  route_permission_exception_show = 'نمایش exception';
    case  route_permission_exception_store = 'ایجاد exception';
    case  route_permission_exception_update = 'ویرایش exception';
    case  route_permission_exception_delete = 'حذف exception';

    #    # error
    case  route_permission_error_index = 'نمایش لیست errors';
    case  route_permission_error_show = 'نمایش error';
    case  route_permission_error_store = 'ایجاد error';
    case  route_permission_error_update = 'ویرایش error';
    case  route_permission_error_delete = 'حذف error';
}
