<?php

namespace App\Http\Controllers;

use App\Models\FeedOrder;
use App\Models\FeedProduct;
use App\Services\FeedOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{
    public function cancel(
        Request $request,
        int $feedOrder,
        FeedOrderService $service
    ): RedirectResponse {
        $this->ensureCustomer($request);

        // Pelanggan hanya boleh mengakses pesanannya sendiri.
        $order = FeedOrder::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($feedOrder);

        $validated = $request->validate(
            [
                'cancellation_reason' => [
                    'required',
                    'string',
                    'min:5',
                    'max:1000',
                ],
            ],
            [
                'cancellation_reason.required' =>
                'Alasan pembatalan wajib diisi.',
                'cancellation_reason.min' =>
                'Alasan minimal 5 karakter.',
                'cancellation_reason.max' =>
                'Alasan maksimal 1.000 karakter.',
            ]
        );

        $service->cancelByCustomer(
            $order,
            $validated['cancellation_reason'],
            $request->user()
        );

        return redirect()
            ->route('customer.orders.show', [
                'feedOrder' => $order->id,
            ])
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
    public function index(Request $request): View
    {
        $this->ensureCustomer($request);

        $orders = FeedOrder::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
        ]);
    }

    public function create(
        Request $request,
        FeedProduct $feedProduct
    ): View {
        $this->ensureCustomer($request);

        abort_unless($feedProduct->is_active, 404);

        return view('orders.create', [
            'product' => $feedProduct,
            'stock' => $feedProduct->availableStock(),
            'customer' => $request->user(),
            'requestToken' => (string) Str::uuid(),
        ]);
    }

    public function store(
        Request $request,
        FeedProduct $feedProduct,
        FeedOrderService $service
    ): RedirectResponse {
        $this->ensureCustomer($request);

        // Hanya ambil input yang dibutuhkan dari formulir.
        $data = $request->only([
            'quantity',
            'request_token',
            'customer_notes',
        ]);

        // Produk ditentukan dari alamat halaman pemesanan.
        $data['feed_product_id'] = $feedProduct->id;

        $order = $service->create(
            $request->user(),
            $data
        );

        return redirect()
            ->route('customer.orders.show', [
                'feedOrder' => $order->id,
            ])
            ->with(
                'success',
                'Pesanan berhasil dikirim. Tunggu konfirmasi pengurus.'
            );
    }

    public function show(
        Request $request,
        int $feedOrder
    ): View {
        $this->ensureCustomer($request);

        // Cari pesanan hanya di dalam data pelanggan yang masuk.
        $order = FeedOrder::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($feedOrder);

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    private function ensureCustomer(Request $request): void
    {
        abort_unless(
            $request->user()
                && $request->user()->role === 'pelanggan',
            403
        );
    }
}
