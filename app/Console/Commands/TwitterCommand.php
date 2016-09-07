<?php namespace Determine\Module\Twitter\Console\Commands;

use Determine\Library\Utility\Console\Commands\BaseModuleCommand;

abstract class TwitterCommand extends BaseModuleCommand
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /** @inheritdoc */
    protected $moduleId = 'twitter';
}
