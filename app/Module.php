<?php namespace Determine\Module\Twitter;

use Determine\Library\Utility\Containers\BaseModule;

class Module extends BaseModule
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /** @inheritdoc */
    const NAME = 'twitter';

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Override to add our alias
     *
     * @return $this
     */
    public function withFacades()
    {
        if (!static::$aliasesRegistered) {
            parent::withFacades();
            class_alias('Thujohn\Twitter\Facades\Twitter', 'Twitter');
        }

        return $this;
    }
}
