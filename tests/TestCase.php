<?php namespace Determine\Library\Utility\Tests;

class TestCase extends \Laravel\Lumen\Testing\TestCase
{
    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }
}
