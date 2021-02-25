<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office\Message;

use App\Http\Controllers\Office\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\System\User;
use App\Models\System\Role;
use App\Models\System\Message;
use App\Models\System\Office;
use App\Rules\SanitizeHtml;
use App\Notifications\Office\Messages\RepNewMessageNotification;
use \Exception;

/**
 * MessagesController
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\Office\Message
 */
class MessagesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();
        $perPage = 25;


        $messages =  Message::where('recipient', $user->id)
                            ->whereNotIn('status', [Message::DELETED, Message::QUEUE])
                            ->latest()
                            ->paginate($perPage);

        $breadcrumbs = breadcrumbs([
            __('Dashboard') => [
                'path'      => route('office.dashboard'),
                'active'    => false
            ],
            __('Messages') => [
                'path'      => route('office.messages.index'),
                'active'    => true
            ],
        ]);


        return view('office.messages.index', compact('site', 'user', 'breadcrumbs' , 'messages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'recipient'         => ['required', 'string', 'exists:system.users,username'],
            'subject'           => ['required', 'string', 'max:100', new SanitizeHtml],
            'message'           => ['required', 'string', 'max:1500', new SanitizeHtml],
            'attachment'        => ['nullable', 'file', 'mimes:jpg,jpeg,gif,png,doc,docx,xls,xlsx,csv,ppt,pptx', 'max:' . bit_convert(10, 'mb')]
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return redirect()->route('office.messages.index', ['error'=>'true'])->withErrors($validator)->withInput();
        }

        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($recipient = $site->users()->role(Role::USER)->first()) {

            $message = new Message;
            $message->uuid = Str::uuid();
            $message->type = Message::USER;
            $message->recipient = $recipient->id;
            $message->subject = $request->input('subject');
            $message->body = $request->input('message');
            $message->status = Message::UNREAD;
            $message->sent_at = now();
            $message->save();

            $user->assignMessage($message);
            $recipient->assignMessage($message);

            if($request->hasFile('attachment')) {

                try {

                    $file = $request->file('attachment');

                    $message->addMedia($file)
                         ->toMediaCollection('attachment');

                } catch(Exception $e) {
                    logger($e->getMessage());
                }
            }

            try {

                $recipient->notify(new RepNewMessageNotification($recipient, $user, $message));

            } catch(Exception $e) {
                logger($e->getMessage());
                flash(__('Message save but failed to notify recipient.'))->error();
            }

            flash(__('Successfully sent message to' . " {$recipient->username}"))->success();
            return redirect()->route('office.messages.index');
        }

        flash(__('User does not exist or inactive.'));
        return redirect()->route('office.messages.index', ['error'=>'true'])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
    }
}
