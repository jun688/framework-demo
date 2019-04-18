<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 21:21
 */

namespace Core\Handles;
use Core\Core;

interface Handle
{
    public function register(Core $app);
}