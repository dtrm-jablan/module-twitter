<?php namespace Determine\Module\Twitter\Events;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    //******************************************************************************
    //* Traits
    //******************************************************************************

    use SerializesModels;
}
