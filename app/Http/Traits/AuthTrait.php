<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

trait AuthTrait
{
    /* @var Request */
    protected Request $request;

    public function loadRequest(): void
    {
        $this->request = resolve(Request::class);
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    protected function getAuthUserId()
    {
        $user = $this->getAuthUser();
        return $user->getAuthIdentifier();
    }

    protected function getAuthUser(): ?Authenticatable
    {
        if (!$this->guard()->user()) {
            throw new UnauthorizedException('Token Expired!');
        }
        return $this->guard()->user();
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
