<?php

namespace App\Http\Managers\FreePromotion;

use App\Models\User;
use App\Models\UserTasks\Tasks;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class TaskManager
{
    /**
     * @param $reward
     * @return int
     */
    public static function getOneRewardWithCommission($reward)
    {
        $commission = round($reward * config('tasks.commission') / 100);
        $commission = empty($commission) ? 1 : $commission;
        return $reward + $commission;
    }

    /**
     * @param User $user
     * @param $reward
     * @param $qty
     * @return bool
     */
    public static function checkAbilityCreating(User $user, $reward, $qty)
    {
        $wallet = $user->getFreePromotionWallet();
        $totalSum = self::getOneRewardWithCommission($reward) * $qty;
        if ($wallet->balance < $totalSum) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param User $user
     * @param Tasks $task
     */
    public static function markUserTaskAsCompleted(User $user, Tasks $task)
    {
        DB::table('user_tasks')->updateOrInsert(
            [
                'user_id' => $user->id,
                'task_id' => $task->id
            ],
            [
                'id' => Uuid::generate()->string,
                'status' => config('tasks.task_statuses.Completed'),
                'completed_at' => now()
            ]
        );
        $walletCustomer = $task->user->getFreePromotionWallet();
        $sumCustomer = self::getOneRewardWithCommission($task->reward_amount);
        $walletCustomer->withdrawFreePromotions($sumCustomer);

        $walletExecutor = $user->getFreePromotionWallet();
        $walletExecutor->replenishFreePromotions($task->reward_amount);

        $task->executed_qty += 1;
        $task->save();
    }
}