<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

class AuthorsController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::paginate());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }
}