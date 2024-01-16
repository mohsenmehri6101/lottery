<?php

use JetBrains\PhpStorm\NoReturn;

if (!function_exists('dd_json')) {
    #[NoReturn] function dd_json($exception): void
    {
        $response = [
            'message' => 'An error occurred',
            'errors' => [],
            'data' => null,
            'status' => 500 // Set default status here or adjust accordingly
        ];

        if ($exception instanceof Throwable) {
            $response['errors'] = [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                //'trace' => $exception->getTrace(),
            ];
        } elseif (is_array($exception) || is_object($exception)) {
            $response['data'] = $exception;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
}

if (!function_exists('get_user_id_login')) {
    function get_user_id_login($user = null)
    {
        $user = $user ?? auth()?->user() ?? null;
        return $user?->id ?? null;
    }
}

if (!function_exists('get_user_id_login')) {
    function get_user_id_login($user = null)
    {
        $user = $user ?? auth()?->user() ?? null;
        return $user?->id ?? null;
    }
}

if (!function_exists('get_user_login')) {
    function get_user_login(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return auth()?->user() ?? null;
    }
}

if (!function_exists('set_user_creator')) {
    function set_user_creator($user = null)
    {
        return get_user_id_login($user);
    }
}

if (!function_exists('get_slug_string')) {
    function get_slug_string($string = null)
    {
        return str_replace(" ", "-", $string);
    }
}

if (!function_exists('get_short_text')) {
    function get_short_text($string = null): string
    {
        $length = config('configs.posts.length_short_text');
        return substr($string, 0, $length);
    }
}

if (!function_exists('set_user_editor')) {
    function set_user_editor($user = null)
    {
        return get_user_id_login($user);
    }
}

if (!function_exists('user_have_permission')) {
    function user_have_permission($permission, $user = null, $role = null): bool
    {
        return true;
//        return \Modules\Authorization\Services\PermissionService::user_have_permission(permission: $permission, user: $user) && !\Modules\Authorization\Services\PermissionBlockService::user_have_permission_block(permission: $permission, user: $user, role: $role);
    }
}

if (!function_exists('user_have_permission_block')) {
    function user_have_permission_block($permission, $user = null, $role = null): bool
    {
//        return \Modules\Authorization\Services\PermissionBlockService::user_have_permission_block(permission: $permission, user: $user, role: $role);
    }
}

if (!function_exists('user_have_role')) {
    function user_have_role($roles = [], $user = null): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }
        /** @var Modules\Authentication\Entities\User $user */
        $user = $user ?? get_user_login();
        return \Modules\Authorization\Services\RoleService::userHaveRoles(user: $user, roles: $roles);
    }
}

if (!function_exists('mobile')) {
    function mobile(string $mobile): bool|int
    {
        return (bool)preg_match('/^(((98)|(\+98)|(0098)|0)(9){1}[0-9]{9})+$/', $mobile) || (bool)preg_match('/^(9){1}[0-9]{9}+$/', $mobile);
    }
}

if (!function_exists('password')) {
    function password(string $password): bool|int
    {
        return preg_match('/(?=^.{8,}$)(?=.*\d)(?=.*[!@#$%^&*]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $password);
    }
}

if (!function_exists('email')) {
    function email(string $email): bool|int
    {
        return true;//todo should be fixed
    }
}

if (!function_exists('telephone')) {
    function telephone(string $telephone): bool|int
    {
        return preg_match('/(0)[0-9]{9}/', $telephone);
    }
}

if (!function_exists('is_string_english')) {
    function is_string_english(string $string, $strict = false): bool|int
    {
        $result = preg_match('/[^A-Za-z0-9]/', $string);
        return !$strict ? $result : $result && !is_string_persian($string);
    }
}

if (!function_exists('is_string_persian')) {
    function is_string_persian(string $string, $strict = false): bool|int
    {
        $result = preg_match('/^[آ ا ب پ ت ث ج چ ح خ د ذ ر ز ژ س ش ص ض ط ظ ع غ ف ق ک گ ل م ن و ه ی]/', $string);
        return !$strict ? $result : $result && !is_string_english($string);
    }
}

if (!function_exists('random_string')) {
    function random_string($length = 10, $start_with = '', $end_with = ''): string
    {
        $start_with = filled($start_with) ? $start_with . "_" : $start_with;
        $end_with = filled($end_with) ? "_" . $end_with : $end_with;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $start_with . $randomString . $end_with;
    }
}

if (!function_exists('between')) {
    function between($number, $min, $max, $equal = true): bool
    {
        if ($equal) {
            if ($number < $min) return false;
            if ($number > $max) return false;
        } else {
            if ($number <= $min) return false;
            if ($number >= $max) return false;
        }
        return true;
    }
}

if (!function_exists('flatten')) {
    function flatten(array $array): array
    {
        $return = array();
        array_walk_recursive($array, function ($a) use (&$return) {
            $return[] = $a;
        });
        return $return;
    }
}

if (!function_exists('fake_persian')) {
    function fake_persian()
    {
        return new \Database\Seeders\persianLib();
    }
}

if (!function_exists('convert_string_to_array')) {
    function convert_string_to_array($value = null): array|string|null
    {
        if ($value && filled($value) && is_string($value)) {
            $value = str_replace(' ', '', $value);
            $value = filled($value) ? explode(',', $value) : [];
            $value = array_map('trim', $value);
            return $value;
        }
        return $value;
    }
}

if (!function_exists('convert_withs_from_string_to_array')) {
    function convert_withs_from_string_to_array($withs = null): array|string|null
    {
        if ($withs && filled($withs) && is_string($withs)) {
            $withs = str_replace(' ', '', $withs);
            $withs = filled($withs) ? explode(',', $withs) : [];
            $withs = array_map('trim', $withs);
            return $withs;
        }
        return $withs;
    }
}

if (!function_exists('convert_array_to_string')) {
    function convert_array_to_string($input_array = null, $separator = ','): string|null
    {
        if ($input_array && is_array($input_array)) {
            $input_array = array_map('trim', $input_array);
            return implode($separator, $input_array);
        }
        return $input_array;
    }
}

if (!function_exists('convert_selects_from_string_to_array')) {
    function convert_selects_from_string_to_array($selects = null): array|string|null
    {
        return convert_withs_from_string_to_array(withs: $selects);
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return get_user_login()?->is_admin() ?? false;
    }
}

if (!function_exists('is_gym_manager')) {
    function is_gym_manager()
    {
        return get_user_login()?->is_gym_manager() ?? false;
    }
}
