<?php


namespace App\Types;


use App\Models\User;

class RefferalUserType
{

    /**
     * Percent depend on level
     */
    const REFERRAL_REWARD_PERCENTS = [
        1 => 10,
        2 => 7.5,
        3 => 5,
        4 => 3,
        5 => 1.5,

    ];


    /**
     * @var integer
     */
    private $referal_level;

    /**
     * @var float
     */
    private $referal_bonus;

    /**
     * @var User
     */
    private $user;

    /**
     * RefferalUserType constructor.
     * @param int $referal_level
     * @param User $user
     * @throws \Exception
     */
    public function __construct(int $referal_level, User $user)
    {
        if(!isset(self::REFERRAL_REWARD_PERCENTS[$referal_level])){
            throw new \Exception('Refferal level not exist');
        }
        $this->referal_level = $referal_level;
        $this->referal_bonus = self::REFERRAL_REWARD_PERCENTS[$referal_level];
        $this->user = $user;
    }


    /**
     * @return int
     */
    public function getReferalLevel(): int
    {
        return $this->referal_level;
    }

    /**
     * @param int $referal_level
     */
    public function setReferalLevel(int $referal_level): void
    {
        $this->referal_level = $referal_level;
    }

    /**
     * @return int
     */
    public function getReferalBonus(): int
    {
        return $this->referal_bonus;
    }

    /**
     * @param int $referal_bonus
     */
    public function setReferalBonus(int $referal_bonus): void
    {
        $this->referal_bonus = $referal_bonus;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }



}
