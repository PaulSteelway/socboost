<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SeeIntegrationExample extends Controller
{
    /**
     * @param string $functionName
     * @return mixed
     * @throws \Exception
     */
    public function index($functionName = '')
    {
        if (!preg_match('/^(get|is)/', $functionName)) {
            throw new \Exception('Wrong function');
        }

        return view('admin.integrationExample', [
            'functionName' => $functionName,
        ]);
    }
}