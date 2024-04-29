<?php

namespace Modules\Authentication\Entities;

use App\Models\Traits\UserCreator;
use App\Models\Traits\UserEditor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Support\Facades\Hash;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Sanctum\HasApiTokens;
use Modules\Gym\Entities\Gym;
use Modules\Gym\Entities\Score;
use Modules\Payment\Entities\Account;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $mobile
 * @property string $code
 * @property string $parent_code
 * @property integer $status
 * @property string $avatar
 * @property boolean $mobile_verified_at
 * @property boolean $email_verified_at
 * @property integer $user_creator
 * @property integer $user_editor
 * @property string $remember_token
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 */
class User extends Authenticatable implements JWTSubject
{
    use HasRoles;
    use HasApiTokens, HasFactory,/* Notifiable,*/
        UserCreator, UserEditor, SoftDeletes;
    use RoutesNotifications;
    use NotificationUserTrait;
    protected $table = 'users';

    const status_unknown = 0;
    const status_active = 1;
    const status_inactive = 2;
    const status_block = 3;

    protected $fillable = [
        'id',
        'username',
        'password',
        'email',
        'mobile',
        'status',
        'avatar',

        'mobile_verified_at',
        'email_verified_at',

        'user_creator',
        'user_editor',

        'remember_token',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'password' => 'hashed',
        'username' => 'string',
        'email' => 'string',
        'mobile' => 'string',
        'code' => 'string',
        'parent_code' => 'string',
        'status' => 'integer',
        'avatar' => 'string',

        'email_verified_at' => 'timestamp',
        'mobile_verified_at' => 'timestamp',

        'user_creator' => 'integer',
        'user_editor' => 'integer',

        'remember_token' => 'string',

        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    protected $hidden = [
        'password',
        'mobile_verified_at',
        'email_verified_at',
        'remember_token',
    ];

    public static array $relations_ = [
        'userCreator',
        'userEditor',
        'gyms',
        'userDetail',
        'events',
        'notifications',
        'readNotifications',
        'unreadNotifications',
        'roles',
        'permissions',
        'accounts',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($item) {
            # user_creator
            if (is_null($item?->user_creator)) {
                $item->user_creator = set_user_creator();
            }

            # set unique code
            $item->code = self::generate_unique_code();

            # user_editor
            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }

        });
        static::created(function (User $user) {
            // todo event fire about welcome-to-group-hy-gym.
            // todo should be active when project is production.
            # ChannelService::createUserSetTableChannelUser($user);
            $message = trans('notifications_template.welcome_message', [
                'web_site_name' => env('APP_NAME', 'سلام سالن'),
            ]);
            $mobile = $user->mobile;
            // todo event - set event welcomeUserEvent not send message.
            send_sms($mobile, $message);
        });
        static::updating(function ($item) {
            # user_editor
            if (is_null($item->user_editor)) {
                $item->user_editor = set_user_creator();
            }
        });
    }

    public function setPasswordAttribute($password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'user_details' => $this?->userDetail()->first(),
            'roles' => $this?->roles()?->get(['name', 'persian_name']) ?? [],
        ];
    }

    public static function getStatusUserTitle($status = null): array|bool|int|string|null
    {
        $statuses = self::getStatusUserPersian();
        if (!is_null($status)) {
            if (is_string_persian($status)) {
                return array_search($status, $statuses) ?? null;
            }
            if (is_int($status) && in_array($status, array_keys($statuses))) {
                return $statuses[$status] ?? null;
            }
            return null;
        }
        return $statuses;
    }

    public static function getStatusUser(): array
    {
        return [
            self::status_unknown,
            self::status_active,
            self::status_inactive,
            self::status_block,
        ];
    }

    #[ArrayShape([self::status_unknown => "string", self::status_active => "string", self::status_inactive => "string", self::status_block => "string"])]
    public static function getStatusUserPersian(): array
    {
        # status_unknown = 0;
        # status_active = 1;
        # status_inactive = 2;
        # status_block = 3;
        return [
            self::status_unknown => 'نامشخص',
            self::status_active => 'فعال',
            self::status_inactive => 'غیرفعال',
            self::status_block => 'بلاک شده',
        ];
    }

    # relations
    public function userDetail(): HasOne
    {
        return $this->hasOne(UserDetail::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'user_id');
    }

    public function gyms(): HasMany
    {
        return $this->hasMany(Gym::class);
    }

    public function is_admin(): bool
    {
        return $this->roles()->where('name', 'admin')->exists();
    }

    public function is_super_admin(): bool
    {
        return $this->roles()->where('name', 'super_admin')->exists();
    }

    public function is_gym_manager(): bool
    {
        return $this->roles()->where('name', 'gym_manager')->exists();
    }

    public function is_user(): bool
    {
        return $this->roles()->where('name', 'user')->exists();
    }

    public static function generate_unique_code(): string
    {
        $code = '';
        $length=config('configs.authentication.users.length_code');
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuwxyz';
        while(true){
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
            if(self::query()->where('code', $code)->doesntExist()){
                break;
            }
        }
        return $code;
    }

    public function getFullNameAttribute(): string
    {
        $name = $this?->userDetail?->name;
        $family = $this?->userDetail?->family;
        return filled($name) || filled($family) ? "$name $family" : ' ';
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

}
