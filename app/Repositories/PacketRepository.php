<?php

namespace App\Repositories;

use App\Models\Packet;
use App\Repositories\BaseRepository;

/**
 * Class PacketRepository
 * @package App\Repositories
 * @version November 21, 2019, 9:41 pm UTC
 *
 * @method Packet findWithoutFail($id, $columns = ['*'])
 * @method Packet find($id, $columns = ['*'])
 * @method Packet first($columns = ['*'])
 */
class PacketRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category_id',
        'service',
        'service_id',
        'is_manual',
        'name_ru',
        'name_en',
        'min',
        'max',
        'price',
        'link',
        'step',
        'step_fixed',
        'icon_title1',
        'icon_title2',
        'icon_title3',
        'icon_title4',
        'icon_subtitle1',
        'icon_subtitle2',
        'icon_subtitle3',
        'icon_subtitle4',
        'icon_title1_ru',
        'icon_title2_ru',
        'icon_title3_ru',
        'icon_title4_ru',
        'icon_subtitle1_ru',
        'icon_subtitle2_ru',
        'icon_subtitle3_ru',
        'icon_subtitle4_ru',
        'info_en',
        'info_ru',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Packet::class;
    }

    /**
     * @param $service
     * @return mixed
     */
    public static function getPacketsByService($service)
    {
        return Packet::where('service', $service)->get();
    }


    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }
}
