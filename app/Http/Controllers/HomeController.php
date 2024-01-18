<?php

namespace App\Http\Controllers;

use Modules\Gym\Services\GymService;

class HomeController extends Controller
{
    public function index()
    {
        /** @var GymService $gymService */
        $gymService = resolve('GymService');
        return $gymService->getInitializeRequestsSelectors(['withs'=>['gyms','tags','categories','sports','attributes','keywords','cities','provinces','gender_acceptances']]);
        return view('home.index');
    }

}
