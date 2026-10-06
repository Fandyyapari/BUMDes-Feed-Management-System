<?php

namespace App\Http\Controllers;

use App\Models\FeedProduct;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim($validated['q'] ?? '');

        $products = FeedProduct::query()
            ->where('is_active', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->withSum('activeReceipts', 'quantity')
            ->withSum('activeIssues', 'quantity')
            ->withSum('activeSales', 'quantity')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('catalog.index', [
            'products' => $products,
            'search' => $search,
        ]);
    }

    public function show(FeedProduct $feedProduct): View
    {
        abort_unless($feedProduct->is_active, 404);

        $feedProduct->loadSum('activeReceipts', 'quantity');
        $feedProduct->loadSum('activeIssues', 'quantity');
        $feedProduct->loadSum('activeSales', 'quantity');

        return view('catalog.show', [
            'product' => $feedProduct,
        ]);
    }
}