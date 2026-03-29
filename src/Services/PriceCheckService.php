<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Services\AlertService;
use App\Models\History;
use App\Services\Parsers\ParserFactory;
use Exception;

class PriceCheckService
{
    // 1. get all products
    // 2. for every product check the current price of a product due to cURL
    // 3. updates price in price_history TABLE
    // 4. updates the price to prices TABLE
    // 5. updates the alerts in Alerts TABLE
    // 6. Log the result

    public function __construct(
        private Product $product,
        private AlertService $alertService,
        private History $history
    ) {}


    public function run(): array
    {
        $products = $this->product->getAllActive();

        $stats = [
            'total' => count($products),
            'updated' => 0,
            'errors' => 0,
            'no_change' => 0
        ];

        foreach ($products as $product) {
            $status = $this->checkProduct($product);

            if (isset($stats[$status])) {
                $stats[$status]++;
            }
        }

        return $stats;
    }

    private function checkProduct(array $product): string
    {
        try {
            $parsed = ParserFactory::make($product['url'])->parse($product['url']);

            $this->alertService->markChecked((int) $product['product_id']);

            // if abs diff less then one then we state that theres no changes were made
            if (abs($parsed['price'] - (float) $product['current_price']) < 1) {
                return 'no_change';
            }
            $this->history->create($product['product_id'], $parsed['price']);
            $this->product->updatePrice($parsed['price'], $product['product_id']);
            $this->alertService->check($product, (float) $product['current_price'], $parsed['price']);
            return 'updated';
        } catch (Exception $e) {
            error_log("Price check failed for product: {$product['product_id']}. " . $e->getMessage());
            return 'error';
        }
    }
}
