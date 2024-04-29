<?php

namespace App\Permissions;

enum RolesEnum: string
{
    use EnumToArray;

    case super_admin = 'super_admin';
    case admin = 'مدیریت';
    case gym_manager = 'مسئول سالن ورزشی';

    case user = 'کاربر معمولی';
}
