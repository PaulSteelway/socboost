<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin\Technical;

use App\Http\Controllers\Controller;

/**
 * Class ReftreeController
 * @package App\Http\Controllers\Admin\Technical
 */
class ReftreeController extends Controller
{
    /**
     * @param null $id
     * @return null|string
     * @throws \Exception
     */
    public function show($id = null)
    {
        if (null == $id) {
            throw new \Exception('reftree id is null');
        }

        return getAdminD3V3ReferralsTree($id);
    }
}
