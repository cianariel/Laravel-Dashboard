<?php

declare(strict_types=1);



use Illuminate\Database\Seeder;
use App\Models\System\ProductType;

class ProductTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Site
        $site = site(config('app.base_domain'));

        // Product Types
        $product_types = [
            'single' => 'Single',
            'bundle' => 'Bundle',
        ];

        foreach ($product_types as $name => $label) {
            $productType = new ProductType;
            $productType->name = $name;
            $productType->label = $label;
            $productType->status = ProductType::ACTIVE;
            $productType->saveOrFail();
            $site->assignProductType($productType);
        }
    }
}
