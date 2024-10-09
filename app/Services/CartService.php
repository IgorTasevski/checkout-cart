<?php

namespace App\Services;

use App\Enums\PromotionType;
use App\Repositories\Contracts\ConfigurationRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\SKURepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function __construct(
        private readonly ProductRepositoryInterface       $productRepository,
        private readonly SKURepositoryInterface           $skuRepository,
        private readonly ConfigurationRepositoryInterface $configurationRepository
    )
    {
    }

    public function addToCart(array $data): array
    {
        $productId = $data['product_id'];
        $quantity = $data['quantity'];
        $skuId = $data['sku_id'];

        $product = $this->productRepository->findById($productId);

        $sku = $this->skuRepository->findById($skuId);

        $cart = Session::get('cart', []);
        $internalId = uniqid('cart_', true);


        if (!isset($cart[$productId])) {
            $cart[$productId] = [
                'internal_id' => $internalId,
                'product_id'  => $product->id,
                'sku_code'    => $sku->code,
                'quantity'    => $quantity,
                'price'       => $product->price,
            ];
        } else {
            $cart[$productId]['quantity'] += $data['quantity'];
        }

        $cart = $this->calculateCartTotal($cart);

        Session::put('cart', $cart);

        return $cart;
    }

    public function removeFromCart(array $data): JsonResponse
    {
        $internalId = $data['internal_id'];

        $cart = Session::get('cart', []);

        if (isset($cart[$internalId])) {
            $newQuantity = $cart[$internalId]['quantity'] - $data['quantity'];

            if ($newQuantity > 0) {
                $cart[$internalId]['quantity'] = $newQuantity;
            } else {
                unset($cart[$internalId]);
            }

            $cart = $this->calculateCartTotal($cart);

            Session::put('cart', $cart);

            return response()->json(['message' => 'Item quantity updated in cart', 'cart' => $cart], 200);
        }

        return response()->json(['message' => 'Item not found in cart'], 404);
    }

    /**
     * Recalculate the price based on the quantity
     */
    private function recalculatePrice($productId, $quantity): float
    {
        $product = $this->productRepository->findById($productId);

        return $quantity * $product->price;
    }


    /**
     * Get the current cart.
     */
    public function getCart()
    {
        return Session::get('cart', []);
    }

    public function calculateCartTotal(array $cart): array
    {
        unset($cart['applied_meal_deals']);

        $cart = $this->applyPromotions($cart);

        $cart = $this->applyMealDealPromotions($cart);

        return $cart;
    }


    /**
     * Apply promotions to the cart.
     */
    private function applyPromotions(array $cart): array
    {
        foreach ($cart as $productId => &$item) {
            $product = $this->productRepository->findById($item['product_id']);

            $promotions = $product->configurations;

            $appliedPromotions = [];
            $isEligibleForPromotion = false;

            foreach ($promotions as $promotion) {
                $ruleDetails = json_decode($promotion->rule_details, true);

                switch ($promotion->rule_type) {
                    case PromotionType::MULTIPRICED->value:
                        if ($item['quantity'] >= $ruleDetails['quantity']) {
                            $item['price'] = $this->applyMultipriced($ruleDetails, $product, $item['quantity']);
                            $appliedPromotions[] = "Multipriced: Buy {$ruleDetails['quantity']} for {$ruleDetails['special_price']}";
                            $isEligibleForPromotion = true;
                        } else {
                            $appliedPromotions[] = "Add " . ($ruleDetails['quantity'] - $item['quantity']) . " more to get the multipriced promotion";
                        }
                        break;

                    case PromotionType::BUY_N_GET_ONE_FREE->value:
                        if ($item['quantity'] >= $ruleDetails['quantity']) {
                            $item['price'] = $this->applyBuyNGetOneFree($ruleDetails, $product, $item['quantity']);
                            $appliedPromotions[] = "Buy {$ruleDetails['quantity']} Get 1 Free";
                            $isEligibleForPromotion = true;
                        } else {
                            $item['price'] = $item['quantity'] * $product->price;
                            $appliedPromotions[] = "Add " . (($ruleDetails['quantity']) - $item['quantity']) . " more to get 1 free";
                            $isEligibleForPromotion = false;
                        }
                        break;
                }
            }

            $item['applied_promotions'] = $appliedPromotions;
            $item['eligible_for_promotion'] = $isEligibleForPromotion;
        }

        return $cart;
    }


    private function applyMultipriced($ruleDetails, $product, $quantity): float
    {
        $requiredQuantity = $ruleDetails['quantity'];
        $specialPrice = $ruleDetails['special_price'];

        $bundles = intdiv($quantity, $requiredQuantity);
        $remaining = $quantity % $requiredQuantity;

        return ($bundles * $specialPrice) + ($remaining * $product->price);
    }


    private function applyBuyNGetOneFree($ruleDetails, $product, $quantity): array
    {
        $requiredQuantity = $ruleDetails['quantity'];
        $totalFreeItems = intdiv($quantity, $requiredQuantity);

        return [
            'price'      => $quantity * $product->price,
            'free_items' => $totalFreeItems
        ];
    }

    /**
     * Apply meal deal promotion to the cart.
     * This checks if all items required for the meal deal are in the cart and applies a special price.
     */
    private function applyMealDealPromotions(array $cart): array
    {
        $mealDeals = $this->configurationRepository->getMealDealConfigurations();

        foreach ($mealDeals as $mealDeal) {
            $ruleDetails = json_decode($mealDeal->rule_details, true);
            $requiredSkus = $ruleDetails['items']; // List of SKUs for the deal
            $specialPrice = $ruleDetails['special_price'];

            $pairCount = $this->getMealDealPairCount($cart, $requiredSkus);

            if ($pairCount > 0) {
                $cart = $this->applyMealDealForPairs($cart, $requiredSkus, $pairCount, $specialPrice);
            }
        }

        return $cart;
    }

    private function getMealDealPairCount(array $cart, array $requiredSkus): int
    {
        $skuQuantities = [];

        foreach ($requiredSkus as $skuCode) {
            $skuQuantities[$skuCode] = 0;
        }

        foreach ($cart as $productId => $item) {
            if (in_array($item['sku_code'], $requiredSkus) && $item['quantity'] > 0) {
                $skuQuantities[$item['sku_code']] += $item['quantity'];
            }
        }

        return min($skuQuantities);
    }


    private function applyMealDealForPairs(array $cart, array $requiredSkus, int $pairCount, float $specialPrice): array
    {
        foreach ($requiredSkus as $skuCode) {
            foreach ($cart as $productId => &$item) {
                if (isset($item['sku_code']) && $item['sku_code'] === $skuCode && $pairCount > 0) {
                    $item['price'] = 0;
                    $item['applied_promotions'][] = "Meal Deal applied: {$specialPrice} for " . implode(' + ', $requiredSkus);
                    $item['eligible_for_promotion'] = true;

                    // Track the applied meal deal
                    $cart['applied_meal_deals'][] = [
                        'description'      => 'Meal Deal: ' . implode(' + ', $requiredSkus),
                        'price'            => $specialPrice,
                        'products_in_deal' => array_map(function ($skuCode) use ($cart) {
                            return [
                                'product_id' => $this->getProductIdBySkuCode($cart, $skuCode),
                                'sku_code'   => $skuCode,
                                'quantity'   => 1,
                            ];
                        }, $requiredSkus)
                    ];

                    $pairCount--;
                }
            }
        }

        return $cart;
    }

    private function getProductIdBySkuCode(array $cart, string $skuCode): ?int
    {
        foreach ($cart as $cartItem) {
            if ($cartItem['sku_code'] === $skuCode) {
                return $cartItem['product_id'];
            }
        }

        return null;
    }
}
