<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

namespace App\Modules;

/**
 * Class PythonInterpreterModule
 * @package App\Modules
 */
class PythonInterpreterModule
{
    /**
     * @param string $script
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public static function command(string $script, $data=[])
    {
        if (exec('echo ok') != 'ok') {
            throw new \Exception('Exes is not working');
        }

        $pythonFolder = app_path('Libraries/Python/');

        $dataStr = implode(' ', $data);

        if (exec('python "'.$pythonFolder.'TestPython.py"') != 'ok') {
            throw new \Exception('Python is not working');
        }

        exec('python "'.$pythonFolder.$script.'" '.$dataStr.' 2>&1', $out);
        return implode(', ', $out);
    }
}