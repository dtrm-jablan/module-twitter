<?php namespace Determine\Module\Twitter\Console;

use Determine\Module\Twitter\Console\Commands\Dequeue;
use Determine\Module\Twitter\Console\Commands\Lookup;
use Determine\Module\Twitter\Console\Commands\Populate;
use Determine\Module\Twitter\Console\Commands\Remap;
use Determine\Module\Twitter\Console\Commands\Scan;
use Determine\Module\Twitter\Console\Commands\Settings;
use Determine\Module\Twitter\Console\Commands\Setup;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /** @inheritdoc */
    protected $commands = [];
}
