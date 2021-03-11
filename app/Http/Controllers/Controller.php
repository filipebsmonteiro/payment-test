<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuthTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, AuthTrait;

    protected function setUserIdOnRequest()
    {
        $this->loadRequest();
        $this->request->merge([
            'user_id' => $request->user_id ?? $this->getAuthUserId()
        ]);
    }
}
