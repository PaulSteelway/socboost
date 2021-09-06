<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Wallet;
use App\Services\CropUserAvatarImage;

use App\Models\Deposit;
use App\Models\DepositQueue;

/**
 * Class UserObserver
 * @package App\Observers
 */
class UserObserver
{

  public function creating(User $user) {
    //нет логина
    if (!$user->login) {
      $user->login = $user->email;
    }

    //нет реферального кода пользователя
    if (null === $user->my_id || !$user->my_id) {
      $exist = true;
      while ($exist) {
        $myId = mt_rand();
        $exist = User::where('my_id', $myId)->select('my_id')->first();
      }
      $user->my_id = $myId;
    }
  }

  public function created(User $user) {
    $user->createUserApiKey();
    $user->sendVerificationEmail();

    Wallet::registerWallets($user);
  }







  // nit: Daan






    /**
     * @param User $user
     * @throws \Exception
     */
    public function deleting(User $user) {
        foreach ($user->transactions()->get() as $transaction) {
            $transaction->delete();
        }

        foreach ($user->taskPropositions()->get() as $taskProposition) {
            $taskProposition->delete();
        }

        foreach ($user->userTaskActions()->get() as $userTaskAction) {
            $userTaskAction->delete();
        }

        foreach ($user->userTasks()->get() as $userTask) {
            $userTask->delete();
        }

        /** @var Deposit $deposit */
        foreach ($user->deposits()->get() as $deposit) {
            DepositQueue::where('deposit_id', $deposit->id)->delete();
            $deposit->delete();
        }

        foreach ($user->wallets()->get() as $wallet) {
            $wallet->delete();
        }

        foreach ($user->telegramUser()->get() as $telegramUser) {
            $telegramUser->delete();
        }

        foreach ($user->user_ips()->get() as $ip) {
            $ip->delete();
        }

        foreach ($user->socialMeta()->get() as $meta) {
            $meta->delete();
        }

        foreach ($user->youtubeVideoWatches()->get() as $watch) {
            $watch->delete();
        }

        foreach ($user->mailSents()->get() as $mail) {
            $mail->delete();
        }

        \DB::table('blockio_notifications')->where('user_id', $user->id)->delete();

        User::where('partner_id', $user->my_id)->update([
            'partner_id' => null !== $user->partner_id
                ? $user->partner_id
                : null,
        ]);
    }

    /**
     * @param User $user
     * @return array
     */
    private function getCacheKeys(User $user): array
    {
        if (null == $user->id) {
            return [];
        }

        $keys = [
            'i.activeAccounts',
        ];

        if ($user->partner_id > 0) {
            $keys[] = 'i.' . $user->partner_id . '.partnerArray';
            $keys[] = 'a.' . $user->partner_id . '.d3v3ReferralsTree';
        }

        return $keys;
    }

    /**
     * @param User $user
     * @return array
     */
    private function getCacheTags(User $user): array
    {
        if (null == $user->id) {
            return [];
        }

        $keys = [
            'lastCreatedMembers',
            'totalAccounts',
            'activeAccounts',
        ];

        if ($user->partner_id > 0) {
            $keys[] = 'userReferrals.' . $user->partner_id;
        }

        return $keys;
    }




    /**
     * Listen to the User creating event.
     *
     * @param User $user
     * @return void
     * @throws
     */


    /**
     * Listen to the User updated event.
     *
     * @param User $user
     * @return void
     * @throws
     */
    public function updated(User $user)
    {
        if ($user->isDirty(['email'])) {
            $user->refreshEmailVerificationAndSendNew();
        }

        if ($user->isDirty(['partner_id'])) {
            if ($user->partner_id == $user->my_id) {
                $user->partner_id = null;
                $user->save();
            }
        }

        //CropAvatarImage and save
        //remove images && $user->isDirty('avatar')
        //nit: Daan
        if ($user->avatar && $user->isDirty('avatar')) {
          $crop_img = new CropUserAvatarImage;
          $crop_img->saveAvatarImage($user->id, $user->avatar);
        }
    }

    /**
     * Listen to the User saving event.
     *
     * @param User $user
     * @return void
     * @throws
     */
    public function saving(User $user)
    {
        //
    }

    /**
     * Listen to the User deleting event.
     *
     * @param User $user
     * @return void
     * @throws
     */
    public function deleted(User $user)
    {
        clearCacheByArray($this->getCacheKeys($user));
        clearCacheByTags($this->getCacheTags($user));
    }
}
