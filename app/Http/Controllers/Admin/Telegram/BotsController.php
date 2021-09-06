<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin\Telegram;

use App\Http\Controllers\Controller;
use App\Http\Requests\Telegram\RequestCreateTelegramBot;
use App\Http\Requests\Telegram\RequestUpdateTelegramBot;
use App\Models\Telegram\TelegramBots;
use App\Models\Telegram\TelegramWebhooksInfo;
use App\Modules\Messangers\TelegramModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\DataTables;

/**
 * Class BotsController
 * @package App\Http\Controllers\Admin\Telegram
 */
class BotsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.telegram.bots.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        /** @var TelegramBots $events */
        $events = TelegramBots::select('*');

        return DataTables::of($events)
            ->make(true);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.telegram.bots.create');
    }

    /**
     * @param RequestCreateTelegramBot $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(RequestCreateTelegramBot $request)
    {
        $checkToken = TelegramBots::where('token', $request->token)->count();

        if ($checkToken > 0) {
            return back()->with('error', __('Such bot already registered with token').' '.$request->token)->withInput();
        }

        if (false == in_array($request->keyword, TelegramBots::getExistsKeywords())) {
            return back()->with('error', __('This keyword is not exists. Please, try again.'))->withInput();
        }

        try {
            /** @var TelegramBots $bot */
            $bot = TelegramBots::create([
                'token'   => $request->token,
                'keyword' => $request->keyword,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', __('Error while registering new Telegram BOT.').' '.$e->getMessage())->withInput();
        }

        try {
            $botInstance = new TelegramModule($bot->keyword);
        } catch (\Exception $e) {
            return back()->with('error', __('Error while creating instance of Telegram BOT.').' '.$e->getMessage())->withInput();
        }

        if (!empty($request->certificate)) {
            if (\File::exists($request->certificate)) {
                $certificate = \File::get($request->certificate);
            } else {
                foreach (TelegramBots::where('token', $bot->token)->get() as $bot) {
                    $bot->delete();
                }
                return back()->with('error', __('Certificate file can not be found.'))->withInput();
            }
        } else {
            $certificate = null;
        }

        try {
            $webhook = $botInstance->setWebhook($bot, $certificate, $request->max_connections);
        } catch (\Exception $e) {
            foreach (TelegramBots::where('token', $bot->token)->get() as $bot) {
                $bot->delete();
            }
            return back()->with('error', __('Webhook can not be installed:').' '.$e->getMessage())->withInput();
        }

        try {
            $newWebhookInfo = $botInstance->getWebhookInfo();
            $newWebhookInfo = $newWebhookInfo->result;
        } catch (\Exception $e) {
            foreach (TelegramBots::where('token', $bot->token)->get() as $bot) {
                $bot->delete();
            }
            return back()->with('error', __('Can not get new webhook fresh info.').' '.$e->getMessage())->withInput();
        }

        $webhookInfoData = [
            'telegram_webhook_id'    => $webhook->id,
            'url'                    => $newWebhookInfo->url ?? null,
            'has_custom_certificate' => $newWebhookInfo->has_custom_certificate ?? null,
            'pending_update_count'   => $newWebhookInfo->pending_update_count ?? null,
            'last_error_date'        => $newWebhookInfo->last_error_date ?? null,
            'last_error_message'     => $newWebhookInfo->last_error_message ?? null,
            'max_connections'        => $newWebhookInfo->max_connections ?? null,
            'allowed_updates'        => $newWebhookInfo->allowed_updates ?? null,
        ];

        try {
            TelegramWebhooksInfo::create($webhookInfoData);
        } catch (\Exception $e) {
            foreach (TelegramBots::where('token', $bot->token)->get() as $bot) {
                $bot->delete();
            }
            return back()->with('error', __('Can not register new webhook info in DB.').' '.$e->getMessage())->withInput();
        }

        try {
            $getMe = $botInstance->getMe();
            $getMe = $getMe->result;
        } catch (\Exception $e) {
            foreach (TelegramBots::where('token', $bot->token)->get() as $bot) {
                $bot->delete();
            }
            return back()->with('error', __('Can not get object getMe.').' '.$e->getMessage())->withInput();
        }

        $bot->update([
            'bot_id'        => $getMe->id,
            'is_bot'        => $getMe->is_bot,
            'first_name'    => $getMe->first_name ?? null,
            'last_name'     => $getMe->last_name ?? null,
            'username'      => $getMe->username,
            'language_code' => $getMe->language_code ?? null,
        ]);

        return back()->with('success', __('Telegram BOT was installed successfully.'))->withInput();
    }

    /**
     * @param TelegramBots $bot
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(TelegramBots $bot)
    {
        return view('admin.telegram.bots.edit', [
            'bot' => $bot,
        ]);
    }

    /**
     * @param RequestUpdateTelegramBot $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RequestUpdateTelegramBot $request)
    {
        $bot = TelegramBots::find($request->id);

        if (null === $bot) {
            return back()->with('error', __('This BOT id is not exists.').' '.$request->id)->withInput();
        }

        $checkToken = TelegramBots::where('token', $request->token)
            ->where('id', '!=', $request->id)
            ->count();

        if ($checkToken > 0) {
            return back()->with('error', __('Such bot already registered with token').' '.$request->token)->withInput();
        }

        if (false == in_array($request->keyword, TelegramBots::getExistsKeywords())) {
            return back()->with('error', __('This keyword is not exists. Please, try again.'))->withInput();
        }

        try {
            /** @var TelegramBots $bot */
            $bot->update([
                'token'   => $request->token,
                'keyword' => $request->keyword,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', __('Error while updating Telegram BOT.').' '.$e->getMessage())->withInput();
        }

        try {
            $botInstance = new TelegramModule($bot->keyword);
        } catch (\Exception $e) {
            return back()->with('error', __('Error while creating instance of Telegram BOT.').' '.$e->getMessage())->withInput();
        }

        try {
            $botInstance->deleteWebhook();
        } catch (\Exception $e) {
            return back()->with('error', __('Error while trying to destroy old webhook.').' '.$e->getMessage())->withInput();
        }

        if (!empty($request->certificate)) {
            if (\File::exists($request->certificate)) {
                $certificate = \File::get($request->certificate);
            } else {
                return back()->with('error', __('Certificate file can not be found.'))->withInput();
            }
        } else {
            $certificate = null;
        }

        try {
            $webhook = $botInstance->setWebhook($bot, $certificate, $request->max_connections);
        } catch (\Exception $e) {
            return back()->with('error', __('Webhook can not be installed:').' '.$e->getMessage())->withInput();
        }

        try {
            $newWebhookInfo = $botInstance->getWebhookInfo();
            $newWebhookInfo = $newWebhookInfo->result;
        } catch (\Exception $e) {
            return back()->with('error', __('Can not get new webhook fresh info.').' '.$e->getMessage())->withInput();
        }

        $webhookInfoData = [
            'telegram_webhook_id'    => $webhook->id,
            'url'                    => $newWebhookInfo->url ?? null,
            'has_custom_certificate' => $newWebhookInfo->has_custom_certificate ?? null,
            'pending_update_count'   => $newWebhookInfo->pending_update_count ?? null,
            'last_error_date'        => $newWebhookInfo->last_error_date ?? null,
            'last_error_message'     => $newWebhookInfo->last_error_message ?? null,
            'max_connections'        => $newWebhookInfo->max_connections ?? null,
            'allowed_updates'        => $newWebhookInfo->allowed_updates ?? null,
        ];

        try {
            TelegramWebhooksInfo::create($webhookInfoData);
        } catch (\Exception $e) {
            return back()->with('error', __('Can not register new webhook info in DB.').' '.$e->getMessage())->withInput();
        }

        try {
            $getMe = $botInstance->getMe();
            $getMe = $getMe->result;
        } catch (\Exception $e) {
            return back()->with('error', __('Can not get object getMe.').' '.$e->getMessage())->withInput();
        }

        $bot->update([
            'bot_id'        => $getMe->id,
            'is_bot'        => $getMe->is_bot,
            'first_name'    => $getMe->first_name ?? null,
            'last_name'     => $getMe->last_name ?? null,
            'username'      => $getMe->username,
            'language_code' => $getMe->language_code ?? null,
        ]);

        return back()->with('success', __('Telegram BOT was successfully updated.'))->withInput();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        /** @var TelegramBots $bot */
        $bot = TelegramBots::find($request->id);

        if (null === $bot) {
            return back()->with('error', __('This bot ID is not exists'))->withInput();
        }

        try {
            $botInstance = new TelegramModule($bot->keyword);
        } catch (\Exception $e) {
            return back()->with('error', __('Error while creating instance of Telegram BOT.').' '.$e->getMessage())->withInput();
        }

        try {
            $botInstance->deleteWebhook();
        } catch (\Exception $e) {
            return back()->with('error', __('Error while trying to destroy old webhook.').' '.$e->getMessage())->withInput();
        }

        Artisan::call('backup:run', ['--only-db' => true]);

        $bot->delete();
        return redirect(route('admin.telegram.bots.index'))->with('success', __('Telegram BOT and all information related to this bot was removed from DB. Note: we have automatically created back-up, if you want to restore this bot.'))->withInput();
    }
}
