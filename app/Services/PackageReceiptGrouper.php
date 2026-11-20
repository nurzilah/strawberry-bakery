<?php

namespace App\Services;

use Illuminate\Support\Collection;

class PackageReceiptGrouper
{
    /**
     * Group physical boxes with the same products, quantities, price, and promo state.
     * The order's stored totals are not changed; this only prepares receipt rows.
     */
    public function group(Collection $packages): array
    {
        $packages->loadMissing('items.product');
        $groups = [];

        foreach ($packages as $package) {
            $signatureItems = $package->items
                ->map(fn ($item) => [(string) $item->product_id, (int) $item->quantity])
                ->sortBy(fn ($item) => $item[0].':'.$item[1])
                ->values()
                ->all();

            $boxPrice = (int) $package->box_price;
            $isFree = (bool) $package->is_free;
            $beverageType = $package->beverage_type;
            $beveragePrice = (int) ($package->beverage_price ?? 0);
            $signature = json_encode([$signatureItems, $boxPrice, $isFree, $beverageType, $beveragePrice]);

            if (! isset($groups[$signature])) {
                $items = $package->items->map(fn ($item) => [
                    'name' => $item->product->name ?? 'Produk',
                    'quantity' => (int) $item->quantity,
                    'unit_price' => (int) $item->unit_price,
                ])->values()->all();

                $groups[$signature] = [
                    'is_free' => $isFree,
                    'quantity' => 0,
                    'unit_price' => $boxPrice,
                    'total_price' => 0,
                    'items' => $items,
                    'beverage_type' => $beverageType,
                    'beverage_price' => $beveragePrice,
                ];
            }

            $groups[$signature]['quantity']++;
            $groups[$signature]['total_price'] += $boxPrice;
        }

        return array_values($groups);
    }
}
