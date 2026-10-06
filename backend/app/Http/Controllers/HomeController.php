<?php

namespace App\Http\Controllers;

use App\Models\FeedProduct;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $products = FeedProduct::query()
            ->where('is_active', true)
            ->withSum('activeReceipts', 'quantity')
            ->withSum('activeIssues', 'quantity')
            ->withSum('activeSales', 'quantity')
            ->orderBy('name')
            ->get();

        return view('home', [
            'products' => $products,
        ]);
    }
}