<?php 
	namespace HskyZhou\NineOrange\Facades;

	use Illuminate\Support\Facades\Facade;

	class NineOrangeFacade extends Facade
	{

	    /**
	     * Get the registered name of the component.
	     *
	     * @return string
	     */
	    protected static function getFacadeAccessor()
	    {
	        return 'nineorange';
	    }
	}
