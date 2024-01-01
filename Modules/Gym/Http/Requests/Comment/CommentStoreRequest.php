<?php

namespace Modules\Gym\Http\Requests\Comment;

use App\Http\Requests\CustomFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Gym\Entities\Comment;

class CommentStoreRequest extends FormRequest
{
    use CustomFormRequestTrait;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->set_validator_censorship();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function rules(): array
    {
        $statuses_comment = implode(',', Comment::getStatusComment());

        return [
            'text' => 'required|filled|censorship',
            'gym_id' => 'required|exists:gyms,id',
            'parent' => 'nullable|exists:comments,id',
            'status' => "nullable|numeric|in:$statuses_comment",
        ];
    }

}
