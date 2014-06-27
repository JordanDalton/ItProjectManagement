<?php

abstract class My_Auth
{
	public static function user()
	{
        return IoC::resolve('user_cache', array(Auth::user()->username));
	}
}