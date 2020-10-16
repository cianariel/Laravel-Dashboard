<?php

declare(strict_types=1);



use Illuminate\Database\Seeder;
use App\Models\System\Currency;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = resource_path('json'. DIRECTORY_SEPARATOR . 'misc' . DIRECTORY_SEPARATOR . 'currency.json');

        $currencies = load_json($path);

        if (filled($currencies)) {
            foreach ($currencies as $code => $currency) {
                if (!Currency::where('code', $code)->exists()) {
                    $_currency = new Currency;
                    $_currency->code = $code;
                    $_currency->symbol = $currency->symbol;
                    $_currency->name = $currency->name;
                    $_currency->name_plural = $currency->name_plural;
                    $_currency->symbol_native = $currency->symbol_native;
                    $_currency->decimal_digits = $currency->decimal_digits;

                    if ($code == 'USD') {
                        $_currency->status = Currency::ACTIVE;
                    } else {
                        $_currency->status = Currency::INACTIVE;
                    }

                    $_currency->saveOrFail();
                }
            }
        }
    }
}
