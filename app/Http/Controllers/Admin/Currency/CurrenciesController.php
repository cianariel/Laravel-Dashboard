<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Currency;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Currency;
use App\Rules\SanitizeHtml;

/**
 * Currencies Resource Controller
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Admin\Currency
 */
class CurrenciesController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->query();
        $perPage = 10;
        $withTrashed = false;

        if (filled($query)) {
            if ($request->has('per_page')) {
                $perPage = strip_tags(trim($query['per_page']));

                if (is_numeric($perPage)) {
                    $perPage = safe_integer($perPage);
                } else {
                    $perPage = 10;
                    $query['per_page'] = $per_page;
                }
            }

            if ($request->has('with_trashed')) {
                $with_trashed  = strip_tags(trim($query['with_trashed']));

                if ($with_trashed == 'true') {
                    $withTrashed  = true;
                }
            }
        }

        if ($withTrashed === true) {
            $currencies = Currency::withTrashed()->paginate($perPage);
        } else {
            $currencies = Currency::paginate($perPage);
        }

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                         'active' => false],
            'Currencies'    => ['path' => route('admin.currencies.index'),           'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.currencies.index', compact('breadcrumbs', 'currencies', 'withTrashed', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status_types = Currency::STATUS_TYPES;

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                         'active' => false],
            'Currencies'    => ['path' => route('admin.currencies.index'),           'active' => false],
            'Add Currency'  => ['path' => route('admin.currencies.create'),          'active' => true ]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.currencies.create', compact('breadcrumbs', 'status_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'code'              => ['string', 'required', 'alpha_dash' , 'max:5', 'unique:system.currencies,code'],
                'symbol'            => ['string', 'required', 'max:5'],
                'name'              => ['string', 'required', 'max:100', new SanitizeHtml],
                'name_plural'       => ['string', 'required', 'max:150', new SanitizeHtml],
                'symbol_native'     => ['string', 'required', 'max:25'],
                'decimal_digits'    => ['integer', 'required'],
                'status'            => ['string', 'required', Rule::in(Currency::STATUS_TYPES)],
            ];

            $validatedData = $request->validate($rules);

            $currency = new Currency;
            $currency->code = $request->input('code');
            $currency->symbol = $request->input('symbol');
            $currency->name = $request->input('name');
            $currency->name_plural = $request->input('name_plural');
            $currency->symbol_native = $request->input('symbol_native');
            $currency->decimal_digits = safe_integer($request->input('decimal_digits'));
            $currency->status = $request->input('status');

            $currency->saveOrFail();

            flash('Successfully created currency.');
            return redirect()->route('admin.currencies.edit', $currency);
        }

        flash('Invaild action.');
        return redirect()->route('admin.currencies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.currencies.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Currency::where('id', $id)->exists()) {
            $status_types = Currency::STATUS_TYPES;
            $currency = Currency::where('id', $id)->firstOrFail();

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                          'active' => false],
                'Currencies'    => ['path' => route('admin.currencies.index'),            'active' => false],
                'Edit Currency' => ['path' => route('admin.currencies.edit', $currency),  'active' => true],
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.currencies.edit', compact('currency', 'breadcrumbs', 'status_types'));
        }

        flash('Currency does not exist.');
        return redirect()->route('admin.currencies.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->isMethod('put') && Currency::where('id', $id)->exists()) {
            $currency = Currency::where('id', $id)->firstOrFail();

             $rules = [
                'symbol'            => ['string', 'required', 'max:5'],
                'name'              => ['string', 'required', 'max:100', new SanitizeHtml],
                'name_plural'       => ['string', 'required', 'max:150', new SanitizeHtml],
                'symbol_native'     => ['string', 'required', 'max:25'],
                'decimal_digits'    => ['integer', 'required'],
                'status'            => ['string', 'required', Rule::in(Currency::STATUS_TYPES)],
             ];

             $validatedData = $request->validate($rules);

             $currency->symbol = $request->input('symbol');
             $currency->name = $request->input('name');
             $currency->name_plural = $request->input('name_plural');
             $currency->symbol_native = $request->input('symbol_native');
             $currency->decimal_digits = safe_integer($request->input('decimal_digits'));
             $currency->status = $request->input('status');

             $currency->saveOrFail();

             flash('Successfully updated currency.');
             return redirect()->route('admin.currencies.edit', $currency);
        }

        flash('Invalid action. Currency does not exist.');
        return redirect()->route('admin.currencies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && Currency::where('id', $id)->exists()) {
            $currency = Currency::where('id', $id)->firstOrFail();

            if ($currency) {
                $currency->delete();

                flash('Successfully deleted currency.');
                return redirect()->route('admin.currencies.index');
            }
        }

        flash('Invaild action. Currency does not exist.');
        return redirect()->route('admin.currencies.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {

        if ($request->isMethod('put') && Currency::where('id', $id)->withTrashed()->exists()) {
            $currency = Currency::where('id', $id)->withTrashed()->firstOrFail();

            if ($currency) {
                $currency->restore();

                flash('Successfully restored currency.');
                return redirect()->route('admin.currencies.edit', $currency);
            }
        }

        flash('Invaild action. Currency does not exist.');
        return redirect()->route('admin.currencies.index');
    }

    /**
     * Remove the specified resource from storage trash.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {

        if ($request->isMethod('delete') && Currency::where('id', $id)->withTrashed()->exists()) {
            $currency = Currency::where('id', $id)->withTrashed()->firstOrFail();

            if ($currency) {
                $currency->forceDelete();

                flash('Successfully deleted currency forever.');
                return redirect()->route('admin.currencies.index');
            }
        }

        flash('Invaild action. Currency does not exist.');
        return redirect()->route('admin.currencies.index');
    }
}
