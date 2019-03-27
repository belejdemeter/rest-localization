<?php

namespace Services\Localization;

use Illuminate\Container\Container;
use Illuminate\Http\Response;

/**
 * Exporter
 */
class Exporter
{

	protected $loader;

	protected $locale;

	/**
	 * Constructor.
	 * @param Container $app 
	 */
    public function __construct(Container $app)
    {
    	$translator = $app->make('translator');
    	
    	$this->loader = $translator->getLoader();
        $this->locale = $translator->getLocale();
    }

    /**
     * Convert translations to array.
     * @return array
     */
    public function toArray()
    {
	    $files = glob(resource_path('lang/'.$this->locale.'/*.php'));
	    $strings = [];

	    foreach ($files as $file) {
	        $namespace = basename($file, '.php');
	        $rows = $this->loader->load($this->locale, $namespace);
	        
	        $output = [];
	        foreach ($rows as $key => $trans) {
	            $output[str_replace('.', '_', $key)] = $trans;
	        }
	        $strings[$namespace] = $output;
	    }

	    return $strings;
    }

    public function toJson()
    {
    	return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toJsonResponse()
    {
    	return new Response($this->toJson(), 200, [
    		"Content-Type" => "application/json; charset=utf-8"
    	]);
    }

    public function toJsResponse()
    {
    	$script = 'window.i18n = '.$this->toJson().';';
    	return new Response($script, 200, [
    		"Content-Type" => "text/javascript; charset=utf-8"
    	]);
    }
}