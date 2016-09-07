<?php namespace Determine\Module\Twitter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class Job implements ShouldQueue
{
    //******************************************************************************
    //* Traits
    //******************************************************************************

    use InteractsWithQueue, Queueable, SerializesModels;
}
