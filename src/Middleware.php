<?php

namespace Services\Localization;

use Closure;
use Services\Localization\NegotiatorInterface;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Container\Container as Application;

class Middleware
{

	/**
	 * Negotiates language
	 * @var \Services\Localization\NegotiatorInterface
	 */
	private $negotiator;


	/**
	 * Translator interface.
	 * @var \Illuminate\Contracts\Translation\Translator
	 */
	private $translator;


	/**
	 * Middleware constructor.
	 * @param NegotiatorInterface $negotiator 
	 */
	public function __construct(NegotiatorInterface $negotiator, Application $app)
	{
		$this->negotiator = $negotiator;		
		$this->translator = $app['translator'];
	}


    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    { 

        $locale = $this->negotiator->negotiateLanguage($request);
        $this->translator->setLocale($locale);

    	$response = $next($request);
    	$response->headers->set('Content-Language', $locale);

        return $response;
    }

}