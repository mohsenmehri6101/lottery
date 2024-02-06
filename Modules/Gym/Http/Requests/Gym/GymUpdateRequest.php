<?php

namespace Modules\Gym\Http\Requests\Gym;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\ReserveTemplate;
use Modules\Gym\Entities\Gym;

class GymUpdateRequest extends FormRequest
{
    use CustomFormRequestTrait;

    protected function prepareForValidation(): void
    {
        if ($this->has('is_ball')) {
            $is_ball = $this->get('is_ball');
            $this->merge(['is_ball' => $is_ball ? 1 : 0]);
        }
    }

    public function authorize(): bool
    {
        $gym_id = request('id');
        $logged_in_user_id = get_user_id_login();

        return
            is_admin()
            || (is_gym_manager() &&
                Gym::query()
                    ->where('gym_id', $gym_id)
                    ->where('user_id', $logged_in_user_id)
                    ->exists());
    }

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_update_unique();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        $list_status_allowable = trim(implode(',', Gym::getStatusGym()));
        $status_gender_acceptances = implode(',', ReserveTemplate::getStatusGenderAcceptance());

        return [
            'name' => 'nullable|filled',
            'description' => 'nullable',
            'price' => 'nullable',
            'status' => "nullable|numeric|in:$list_status_allowable",
            'gender_acceptance' => "nullable|numeric|in:$status_gender_acceptances",
            'user_id' => 'nullable|exists:users,id',
            'profit_share_percentage' => 'nullable|min:0|max:100',
            'is_ball' => 'nullable|in:0,1',
            'ball_price' => 'nullable',
            'city_id' => 'nullable|numeric|exists:cities,id',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'short_address' => 'nullable',
            'address' => 'nullable',

            # tags
            'tag_id' => 'nullable|filled|exists:tags,id',
            'tags' => 'nullable|array',
            'tags.*' => 'required|filled|exists:tags,id',

            # categories
            'category_id' => 'nullable|filled|exists:categories,id',
            'categories' => 'nullable|array',
            'categories.*' => 'required|filled|exists:categories,id',

            # keywords
            'keyword_id' => 'nullable|filled|exists:keywords,id',
            'keywords' => 'nullable|array',
            'keywords.*' => 'required|filled|exists:keywords,id',

            # sports
            'sport_id' => 'nullable|filled|exists:sports,id',
            'sports' => 'nullable|array',
            'sports.*' => 'required|filled|exists:sports,id',

            # attributes
            'attribute_id' => 'nullable|filled|exists:gyms_attributes,id',
            'attributes' => 'nullable|array',
            'attributes.*' => 'required|filled|exists:gyms_attributes,id',

            # images
            'images' => 'nullable|array',
            'images.*' => 'required',
        ];
    }

}
