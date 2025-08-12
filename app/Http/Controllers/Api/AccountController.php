<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AccountController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return AccountResource::collection(
            Account::query()
                ->where('user_id', $request->user()->id)
                ->get()
        );
    }
}
