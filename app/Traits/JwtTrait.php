<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request;
use stdClass;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

trait JwtTrait
{
    private stdClass $profile;

    /*
     * Retrieve JWT Profile
     */
    public function jwt($payload)
    {
        if (!empty(Request::header('Authorization'))) {
            try {
                $jwtPayload = JWTAuth::parseToken()->getPayload();

                if (!empty($jwtPayload->get($payload))) {
                    $this->profile = (object)Crypt::decrypt($jwtPayload->get($payload));
                }
            } catch (JWTException $e) {
                return $this;
            }
        }

        return $this;
    }

    public function get($key) {
        if(empty($this->profile)) {
            return null;
        }

        if(empty($this->profile->{$key})) {
            return null;
        }

        return gettype($this->profile->{$key}) == 'array' ? (object) $this->profile->{$key} : $this->profile->{$key};
    }
}
