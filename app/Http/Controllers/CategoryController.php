<?php

namespace App\Http\Controllers;

use App\Actions\Category\GetCategoriesAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        return (new GetCategoriesAction())->execute($request->all());
    }

    public function autoCategorize(Request $request)
    {
        return (new GetCategoriesAction())->autoCategorize($request->service);
    }
}
