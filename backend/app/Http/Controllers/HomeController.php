<?php

namespace App\Http\Controllers;

use App\Models\FeedProduct;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProduct = FeedProduct::query()
            ->where('is_active', true)
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->orderBy('name')
            ->first();

        return view('home', [
            'featuredProduct' => $featuredProduct,
        ]);
    }
}