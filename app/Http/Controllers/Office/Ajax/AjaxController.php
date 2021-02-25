<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office\Ajax;

use App\Http\Controllers\Ajax\AjaxController as AjaxBaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Role;
use App\Models\System\User;
use App\Models\System\Message;
use App\Rules\SanitizeHtml;
use \Exception;

/**
 * AjaxController
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\Office\Ajax
 */
class AjaxController extends AjaxBaseController
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('role:'.Role::OWNER);
        $this->middleware('user:'.User::GUARD);
    }

    /**
     * Toggle approved rep user for office
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\ResponseJson
     */
    public function toggleApprovedRepUser(Request $request)
    {
        $rules = [
            'user'  => ['required', 'integer', 'exists:system.users,id'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()) {

            $site = site(config('app.base_domain'));
            $user = auth()->guard(User::GUARD)->user();
            $officeOwner = office_owner($user);
            $office = $officeOwner->offices()->first();
            $status = 'off';

            if($repUser = $site->users()->where('id', $request->input('user'))->where('status', User::ACTIVE)->first()) {

                if($repUser->hasRole(Role::USER)) {

                    $approvedUsers = $office->getMetaField('approved_users', []);

                    if(filled($approvedUsers)) {
                        if(in_array($repUser->username, $approvedUsers)) {
                            foreach($approvedUsers as $index => $approvedUser) {
                                if($repUser->username = $approvedUser) {
                                    unset($approvedUsers[$index]);
                                    $status = 'off';
                                    break;
                                }
                            }
                        } else {
                            $approvedUsers[] = $repUser->username;
                            $status = 'on';
                        }
                    } else {
                        $approvedUsers[] = $repUser->username;
                        $status = 'on';

                    }

                    $office->setMetaField('approved_users', $approvedUsers);
                    $office->save();

                    return response()->json([
                        'status'    => 200,
                        'message'   => __('success'),
                        'status'    => $status
                    ]);
                }
            }

            return response()->json([
                'status'    => 500,
                'message'   => __('Invaild user or inactive.')
            ]);
        }

        abort(500);
    }

    /**
     * Toggle favorite rep user for office
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\ResponseJson
     */
    public function toggleFavoriteUser(Request $request)
    {
        $rules = [
            'user'  => ['required', 'integer', 'exists:system.users,id'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()) {

            $site = site(config('app.base_domain'));
            $user = auth()->guard(User::GUARD)->user();
            $officeOwner = office_owner($user);
            $office = $officeOwner->offices()->first();
            $status = 'off';

            if($repUser = $site->users()->where('id', $request->input('user'))->where('status', User::ACTIVE)->first()) {

                if($repUser->hasRole(Role::USER)) {

                    $favoriteUsers = $office->getMetaField('favorite_users', []);

                    if(filled($favoriteUsers)) {
                        if(in_array($repUser->username, $favoriteUsers)) {
                            foreach($favoriteUsers as $index => $favoriteUser) {
                                if($repUser->username = $favoriteUser) {
                                    unset($favoriteUsers[$index]);
                                    $status = 'off';
                                    break;
                                }
                            }
                        } else {
                            $favoriteUsers[] = $repUser->username;
                            $status = 'on';
                        }
                    } else {
                        $favoriteUsers[] = $repUser->username;
                        $status = 'on';

                    }

                    $office->setMetaField('favorite_users', $favoriteUsers);
                    $office->save();

                    return response()->json([
                        'status'    => 200,
                        'message'   => __('success'),
                        'status'    => $status
                    ]);
                }
            }

            return response()->json([
                'status'    => 500,
                'message'   => __('Invaild user or inactive.')
            ]);
        }

        abort(500);
    }

    /**
     * Toggle blocked rep user for office
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\ResponseJson
     */
    public function toggleBlockedUser(Request $request)
    {
        $rules = [
            'user'  => ['required', 'integer', 'exists:system.users,id'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()) {

            $site = site(config('app.base_domain'));
            $user = auth()->guard(User::GUARD)->user();
            $officeOwner = office_owner($user);
            $office = $officeOwner->offices()->first();
            $status = 'off';

            if($repUser = $site->users()->where('id', $request->input('user'))->where('status', User::ACTIVE)->first()) {

                if($repUser->hasRole(Role::USER)) {

                    $blockedUsers = $office->getMetaField('blocked_users', []);

                    if(filled($blockedUsers)) {
                        if(in_array($repUser->username, $blockedUsers)) {
                            foreach($blockedUsers as $index => $blockedUser) {
                                if($repUser->username = $blockedUser) {
                                    unset($blockedUsers[$index]);
                                    $status = 'off';
                                }
                            }
                        } else {
                            $blockedUsers[] = $repUser->username;
                            $status = 'on';
                        }
                    } else {
                        $blockedUsers[] = $repUser->username;
                        $status = 'on';
                    }

                    $office->setMetaField('blocked_users', $blockedUsers);
                    $office->save();

                    return response()->json([
                        'status'    => 200,
                        'message'   => __('success'),
                        'status'    => $status
                    ]);
                }
            }

            return response()->json([
                'status'    => 500,
                'message'   => __('Invaild user or inactive.')
            ]);
        }

        abort(500);
    }

    /**
     * Retrieve Query Rep Users
     */
    public function queryRepsUsers(Request $request)
    {
        $rules = [
            'q'    => ['required', 'string', 'min:3' , new SanitizeHtml]
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()) {

            $q = $request->input('q');

            $site = site(config('app.base_domain'));
            $user = auth()->guard(User::GUARD)->user();

            $users = $site->users()->role(Role::USER)->where('status', User::ACTIVE)
                                           ->orWhere('username', 'like', "%{$q}%")
                                           ->orWhere('first_name', 'like', "%{$q}%")
                                           ->orWhere('last_name', 'like', "%{$q}%")
                                           ->orWhere('company', 'like', "%{$q}%")
                                           ->orWhere('email', 'like', "%{$q}%")
                                           ->select([
                                                'id',
                                                'username',
                                                'first_name',
                                                'last_name',
                                                'company',
                                           ])
                                           ->limit(5)
                                           ->cursor();
            if($users->count() !== 0) {

                $receipts = [];

                foreach($users as $receipt) {

                    if($receipt->hasRole(Role::USER)) {
                        $receipts[] = [
                            'username'      => $receipt->username,
                            'first_name'    => $receipt->first_name,
                            'last_name'     => $receipt->last_name,
                            'company'       => $receipt->company,
                            'avator'        => avator($receipt)
                        ];
                    }
                }

                return response()->json([
                    'status'        => 200,
                    'recipients'    => $receipts
                ]);
            }

            return response()->json([
                'status'    => 404,
                'message'   => __('No matches found.')
            ]);
        }

        abort(500);
    }

    /**
     * Retreive Single Message
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\ResponseJson
     */
    public function retreiveMessage(Request $request)
    {
        $rules = [
            'id'    => ['required', 'integer', 'exists:system.messages,id']
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()) {

            $site = site(config('app.base_domain'));
            $user = auth()->guard(User::GUARD)->user();

            if($message = $user->messages()->where('recipient', $user->id)->first()) {

                $fromUser = $message->users()->whereNot('id', $user->id)->first();

                $data = [
                    'id'            => $message->id,
                    'type'          => $message->type,
                    'from'          => $fromUser->username,
                    'subject'       => $message->subject,
                    'body'          => $message->body,
                    'status'        => $message->subject,
                    'meta_fields'   => $message->meta_fields,
                    'read_at'       => $message->read_at,
                    'sent_at'       => $message->sent_at,
                    'created_at'    => $message->created_at
                ];

                return response()->json([
                    'status'    => 200,
                    'message'   => $data
                ]);

            } else {

                return response()->json([
                    'status'    => 404,
                    'message'   => __('Message does not exist.')
                ]);
            }
        }

        abort(500);
    }
}
