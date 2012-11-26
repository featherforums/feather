<?php

use Mockery as m;

class TestCase extends Illuminate\Foundation\Testing\TestCase {


    /**
     * Close mockery.
     * 
     * @return void
     */
    public function tearDown()
    {
        m::close();
    }


    /**
     * Creates the application.
     *
     * @return Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
    	$unitTesting = true;

        $testEnvironment = 'testing';

    	return require __DIR__.'/../start.php';
    }

}