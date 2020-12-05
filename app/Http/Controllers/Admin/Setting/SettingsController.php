<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\System\Group;
use App\Models\System\Setting;
use App\Models\System\Site;

/**
 * Settings Controller
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Admin\Setting
 */
class SettingsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $site = site();
        $settings = $site->settings()->with('groups')->select(['id'])->cursor();
        $groups = [];

        foreach ($settings as $setting) {
            $group = $setting->groups()->firstOrFail();

            if (!array_key_exists($group->name, $groups)) {
                $groups[$group->name] = $group;
            }
        }

        $breadcrumbs = [
            'Dashboard'         => ['path' => admin_url(),                                      'active' => false],
            'Settings'          => ['path' => route('admin.settings.index'),                          'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.settings.index', compact('breadcrumbs', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $input_types = Setting::INPUT_TYPES;
        $lock_types = Setting::LOCK_TYPES;
        $required_types = Setting::REQUIRED_TYPES;
        $groups = Group::select(['id', 'name'])->cursor();

        $breadcrumbs = [
            'Dashboard'         => ['path' => admin_url(),                                      'active' => false],
            'Settings'          => ['path' => route('admin.settings.index'),                          'active' => false],
            'Add Setting'       => ['path' => route('admin.settings.create'),                         'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        $list = [];
        foreach ($groups as $group) {
            if ($group->name != 'blog' && $group->name != 'user') {
                $list[] = $group->name;
            }
        }

        $groups = $list;

        return view('admin.settings.create', compact('breadcrumbs', 'groups', 'input_types', 'lock_types', 'required_types'));
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
                'name'      => ['required', 'string'],
                'type'      => ['required', 'string', Rule::in(Setting::INPUT_TYPES)],
                'group'     => ['required', 'string', 'exists:system.groups,name'],
                'options'   => ['nullable', 'string'],
                'required'  => ['required', 'string', Rule::in(Setting::REQUIRED_TYPES)],
                'status'    => ['required', 'string', Rule::in(Setting::LOCK_TYPES)],
            ];

            $validatedData = $request->validate($rules);

            $domain = Str::snake(config('app.base_domain'));
            $name = Str::snake($request->input('name'));
            $key =  $domain . '_site_' . $name;

            if (!Setting::where('key', $key)->exists()) {
                $site = site();
                $group = Group::where('name', $request->input('group'))->firstOrFail();
                $setting = new Setting();
                $setting->key = $key;
                $setting->type = $request->input('type');

                if (filled($request->input('options'))) {
                    $setting->options =  serialize(explode(PHP_EOL, $request->input('options')));
                }
                $setting->status = $request->input('status');
                $setting->required = $request->input('required');

                $setting->saveOrFail();
                $setting->assignGroup($group);
                $site->assignSetting($setting);

                flash('Successfully added new setting. You can assign value to setting.');
                return redirect()->route('admin.settings.group', ['group' => $group->name]);
            } else {
                $request->session()->reflash();
                flash('Setting key already exists. Must be unique');
                return redirect()->back();
            }
        }

        flash('Invaild action.');
        return redirect()->route('admin.settings.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return redirect()->route('admin.settings.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (Setting::where('id', $id)->exists()) {
            $site = site();
            $input_types = Setting::INPUT_TYPES;
            $lock_types = Setting::LOCK_TYPES;
            $required_types = Setting::REQUIRED_TYPES;
            $groups = Group::select(['id', 'name'])->cursor();
            $setting = $site->settings()->where('id', $id)->firstOrFail();

            if ($setting->status == Setting::LOCKED) {
                flash('Setting is locked and could not modify or delete.');
                return redirect()->route('admin.settings.manage');
            }

            $group = $setting->groups()->firstOrFail();

            $breadcrumbs = [
                'Dashboard'         => ['path' => admin_url(),                                      'active' => false],
                'Settings'          => ['path' => route('admin.settings.index'),                          'active' => false],
                'Edit Setting'      => ['path' => route('admin.settings.edit', $id),                      'active' => true],
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            $list = [];
            foreach ($groups as $_group) {
                if ($_group->name != 'blog' && $_group->name != 'user') {
                    $list[] = $_group->name;

                    if ($group->name == $_group->name) {
                        $group_name = $group->name;
                    }
                }
            }

            $groups = $list;
            $group = $group_name;

            return view('admin.settings.edit', compact('breadcrumbs', 'setting', 'input_types', 'lock_types', 'required_types', 'groups', 'group'));
        }

        flash('Setting does not exist.');
        return redirect()->route('admin.settings.manage');
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
        if ($request->isMethod('put') && Setting::where('id', $id)->exists()) {
            $rules = [
                'name'      => ['required', 'string'],
                'type'      => ['required', 'string', Rule::in(Setting::INPUT_TYPES)],
                'group'     => ['required', 'string', 'exists:system.groups,name'],
                'options'   => ['nullable', 'string'],
                'required'  => ['required', 'string', Rule::in(Setting::REQUIRED_TYPES)],
                'status'    => ['required', 'string', Rule::in(Setting::LOCK_TYPES)],
            ];

            $validatedData = $request->validate($rules);

            $site = site();
            $setting = $site->settings()->where('id', $id)->firstOrFail();

            $domain = Str::snake(config('app.base_domain'));
            $name = Str::snake($request->input('name'));
            $key =  $domain . '_site_' . $name;

            if ($setting->status == Setting::LOCKED) {
                flash('Setting is locked and could not modify or delete.');
                return redirect()->route('admin.settings.edit', $setting);
            } else {
                if ($setting->key != $key) {
                    if (!Setting::where('key', $key)->exists()) {
                        $setting->key = $key;
                    } else {
                        flash('Setting key name must unique.');
                        return redirect()->route('admin.settings.edit', $setting);
                    }
                }

                $setting->type = $request->input('type');

                if (filled($request->input('options'))) {
                    $setting->options =  serialize(explode(PHP_EOL, $request->input('options')));
                }

                $setting->status = $request->input('status');
                $setting->required = $request->input('required');

                $group = $setting->groups()->firstOrFail();

                if ($group->name != $request->input('group')) {
                    $setting->unassignGroup($group);
                    $setting->assignGroup(Group::where('name', $request->input('group'))->select('id')->firstOrFail());
                }

                $setting->saveOrFail();

                flash('Successfully updated setting.');
                return redirect()->route('admin.settings.edit', $setting);
            }
        }

        flash('Invaild action or setting does not exist.');
        return redirect()->route('admin.settings.manage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && Setting::where('id', $id)->exists()) {
            $site = site();
            $setting = $site->settings()->where('id', $id)->firstOrFail();

            if ($setting->status == Setting::LOCKED) {
                flash('Setting is locked and could not modify or delete.');
                return redirect()->route('admin.settings.edit', $setting);
            } else {
                $site->unassignSetting($setting);
                $setting->unassignGroup($setting->groups()->select('id')->firstOrFail());
                $setting->delete();

                flash('Successfully deleted setting.');
                return redirect()->route('admin.settings.manage');
            }
        }

        flash('Invaild action or setting does not exist.');
        return redirect()->route('admin.settings.manage');
    }

    /**
     * Manage Settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function manageSettings(Request $request)
    {
        $query = $request->query();
        $perPage = 10;

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
        }

        $site = site();
        $settings = $site->settings()->orderBy('key', 'asc')->paginate($perPage);

        $breadcrumbs = [
            'Dashboard'         => ['path' => admin_url(),                                      'active' => false],
            'Settings'          => ['path' => route('admin.settings.index'),                          'active' => false],
            'Manage'            => ['path' => route('admin.settings.manage'),                         'active' => true ]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.settings.manage', compact('breadcrumbs', 'settings', 'query'));
    }

    /**
     * Show specified grouped resource from storage.
     *
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function showGroupSetting(Request $request, $name)
    {
        if (Group::where('name', $name)->exists()) {
            $site = site();
            $group = Group::where('name', $name)->firstOrFail();
            $settings = $site->settings()->whereHas(
                'groups',
                function ($query) use (&$group) {
                    $query->where('name', $group->name);
                }
            )->cursor();

            if ($settings->count() !== 0) {
                $breadcrumbs = [
                    'Dashboard'         => ['path' => admin_url(),                                      'active' => false],
                    'Settings'          => ['path' => route('admin.settings.index'),                          'active' => false],
                    $group->label       => ['path' => route('admin.settings.group', ['group', $group->name]), 'active' => true]
                ];

                $breadcrumbs = breadcrumbs($breadcrumbs);

                return view('admin.settings.group_edit', compact('breadcrumbs', 'settings', 'group'));
            } else {
                flash('No ' . strtolower($group->label) . ' settings found.');
                return redirect()->route('admin.settings.index');
            }
        }

        flash('Invaild group settings');
        return redirect()->route('admin.settings.index');
    }

    /**
     * Update specified grouped setting resource from storage.
     *
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function updateGroupSetting(Request $request, $name)
    {
        if (Group::where('name', $name)->exists()) {
            $site = site();
            $group = Group::where('name', $name)->firstOrFail();
            $settings = $site->settings()->whereHas(
                'groups',
                function ($query) use (&$group) {
                    $query->where('name', $group->name);
                }
            )->cursor();

            if ($settings->count() !== 0) {
                $rules = [];

                foreach ($settings as $setting) {
                    if ($setting->required == Setting::REQUIRED) {
                        $required = 'required';
                    } else {
                        $required = 'nullable';
                    }

                    switch ($setting->type) {
                        case Setting::INPUT_FILE:
                            if (filled($setting->options)) {
                                $rules[str_replace(config('app.base_domain') . '_', '', $setting->key)] = [$required, 'file' ,'mimes:' . implode(',', unserialize($setting->options))];
                            } else {
                                $rules[str_replace(config('app.base_domain') . '_', '', $setting->key)] = [$required, 'file'];
                            }
                            break;
                        case Setting::INPUT_EMAIL:
                            $rules[str_replace(config('app.base_domain') . '_', '', $setting->key)] = [$required, 'string', 'email:rfc,dns'];
                            break;
                        case Setting::INPUT_TEXT:
                            $rules[str_replace(config('app.base_domain') . '_', '', $setting->key)] = [$required, 'string'];
                            break;
                        case Setting::INPUT_TEXTAREA:
                            $rules[str_replace(config('app.base_domain') . '_', '', $setting->key)] = [$required, 'string'];
                            break;
                        case Setting::INPUT_NUMBER:
                            $rules[str_replace(config('app.base_domain') . '_', '', $setting->key)] = [$required, 'numeric'];
                            break;
                        case Setting::INPUT_RANGE:
                            $rules[str_replace(config('app.base_domain') . '_', '', $setting->key)] = [$required, 'numeric'];
                            break;
                        case Setting::INPUT_SELECT:
                            if (filled($setting->options)) {
                                $rules[str_replace(config('app.base_domain') . '_', '', $setting->key)] = [$required, 'string', Rule::in(unserialize($setting->options))];
                            } else {
                                $rules[str_replace(config('app.base_domain') . '_', '', $setting->key)] = [$required, 'string'];
                            }
                            break;
                        case Setting::INPUT_MULTISELECT:
                            $rules[str_replace(config('app.base_domain') . '_', '', $setting->key)] = [$required, 'string'];
                            break;
                    }
                }

                $validatedData = $request->validate($rules);

                foreach ($settings as $setting) {
                    $key = str_replace(config('app.base_domain') . '_', '', $setting->key);

                    if ($request->has($key)) {
                        if ($setting->type == Setting::INPUT_FILE) {
                            if ($request->hasFile($key)) {
                                // Delete old file
                                if (filled($setting->value)) {
                                    $media = $setting->getMedia('files')->first();

                                    if ($media) {
                                        $media->delete();
                                    }
                                }

                                $file = $request->file($key);

                                $setting->addMedia($file)
                                    ->toMediaCollection('files');

                                $path = $setting->getMedia('files')->first()->getPath();
                                $setting->value = $path;
                                $setting->saveOrFail();
                            }
                        } else {
                            $setting->value = $request->input($key);
                            $setting->saveOrFail();
                        }
                    }
                }

                flash('Successfully updated site settings.');
                return redirect()->route('admin.settings.group', ['group' => $group->name]);
            } else {
                flash('No ' . strtolower($group->label) . ' settings found.');
                return redirect()->route('admin.settings.index');
            }
        }

        flash('Invaild group settings');
        return redirect()->route('admin.settings.index');
    }

    /**
     * Update specified grouped setting resource from storage.
     *
     * @param  string $group
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function clearGroupSetting(Request $request, $group, $setting)
    {

        if (Group::where('name', $group)->exists() && Setting::where('key', $setting)->exists()) {
            $site = site();
            $group = Group::where('name', $group)->firstOrFail();

            // Clear Setting.
            if ($site->settings()->where('key', $setting)->exists()) {
                $setting = $site->settings()->where('key', $setting)->firstOrFail();

                if ($setting->type == Setting::INPUT_FILE) {
                    $media = $setting->getMedia('files')->first();

                    if ($media) {
                        $media->delete();
                    }

                    $setting->value = null;
                    $setting->saveOrFail();

                    flash('Successfully removed image from setting');
                    return redirect()->route('admin.settings.group', ['group' => $group->name]);
                } else {
                    flash('Setting type can not be cleared.');
                    return redirect()->route('admin.settings.group', ['group' => $group->name]);
                }
            } else {
                flash('No settings in group ' . strtolower($group->label) . ' found.');
                return redirect()->route('admin.settings.index');
            }
        }

        flash('Invaild group settings');
        return redirect()->route('admin.settings.index');
    }
}
