<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartCheckoutRequest;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function __construct(private readonly CartService $checkoutService)
    {
    }


    public function index(): JsonResponse
    {
        $cart = $this->checkoutService->getCart();

        return response()->json(['cart' => $cart]);
    }

    public function store(CartCheckoutRequest $request): JsonResponse
    {
        $data = $request->validated();

        $cart = $this->checkoutService->addToCart($data);

        return response()->json(['message' => 'Item added to cart', 'cart' => $cart], 200);
    }

    public function update(CartCheckoutRequest $request): JsonResponse
    {
        $data = $request->validated();

        $cart = $this->checkoutService->removeFromCart($data);

        return response()->json(['message' => 'Item removed from cart', 'cart' => $cart], 200);
    }
}
