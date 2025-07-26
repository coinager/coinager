<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseStoreRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Support\Arr;

class ExpenseController extends Controller
{
    public function store(ExpenseStoreRequest $request): ExpenseResource
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['transacted_at'] = Arr::get($data, 'transacted_at', now());

        $expense = Expense::create($data);

        return ExpenseResource::make($expense);
    }
}
