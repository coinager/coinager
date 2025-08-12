<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncomeStoreRequest;
use App\Http\Resources\IncomeResource;
use App\Models\Income;
use Illuminate\Support\Arr;

class IncomeController extends Controller
{
    public function store(IncomeStoreRequest $request): IncomeResource
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['transacted_at'] = Arr::get($data, 'transacted_at', now());

        $income = Income::create($data);

        return IncomeResource::make($income);
    }
}
