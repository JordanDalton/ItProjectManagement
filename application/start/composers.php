<?php

View::composer('layouts.default', function($view){
	$view->nest('header', 'layouts.partials.header');
	$view->nest('footer', 'layouts.partials.footer');
});