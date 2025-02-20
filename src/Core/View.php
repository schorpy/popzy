<?php

namespace Popzy\Core;

class View
{
    public static function Render($path, $data = array())
    {
        if (!empty($data)) {
            extract($data);
        }

        require sprintf(
            '%s/Views/%s.php',
            dirname(__DIR__),
            str_replace('.', '/', $path)
        );
    }
}
