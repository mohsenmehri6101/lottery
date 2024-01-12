<?php

namespace Modules\Gym\Http\Requests\Reserve;

use Illuminate\Foundation\Http\FormRequest;

# use Illuminate\Support\Facades\DB;

class ReserveStoreAndDoStuffRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // todo i should be check in table reserve we dont have record have gym_id,reserve_template_id,dated_at similar in this list
            'reserves' => 'nullable|array',
            'reserves.*' => 'nullable|array',
            'reserves.*.reserve_template_id' => 'required|exists:reserve_templates,id',
            'reserves.*.gym_id' => 'required|exists:gyms,id',
            'reserves.*.user_id' => 'nullable|exists:users,id',
            'reserves.*.dated_at' => 'required|unique:reserves,dated_at',
            
            //            'reserves.*.dated_at' => [
            //                'required',
            //                function ($attribute, $value, $fail) {
            //                    $gymId = $this->input('reserves.*.gym_id');
            //                    $templateId = $this->input('reserves.*.reserve_template_id');
            //                    dd($gymId,$templateId);
            //
            //                    $exists = DB::table('reserves')
            //                        ->where('gym_id', $gymId)
            //                        ->where('reserve_template_id', $templateId)
            //                        ->where('dated_at', $value)
            //                        ->exists();
            //
            //                    if ($exists) {
            //                        $fail("The combination of gym_id, reserve_template_id, and dated_at must be unique.");
            //                    }
            //                }
            //            ]
            //            'reserves.*.dated_at' => 'required|unique:reserves,dated_at,NULL,id,gym_id,' . $this->input('reserves.*.gym_id') . ',reserve_template_id,' . $this->input('reserves.*.reserve_template_id'),
            //            'reserves.*.dated_at' => 'required|unique:reserves,dated_at,NULL,id,gym_id,' . $this->input('reserves.*.gym_id') . ',reserve_template_id,' . $this->input('reserves.*.reserve_template_id'),
            //            'reserves.*.dated_at' => 'required|unique:reserves,dated_at',
        ];
    }

}
