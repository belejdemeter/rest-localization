<?php

namespace Services\Localization;


interface NegotiatorInterface {

	/**
	 * Negotiates language with the user's browser through the Accept-Language HTTP header
	 * @return string The negotiated language result or app.locale.
	 */
	public function negotiateLanguage($request);
}