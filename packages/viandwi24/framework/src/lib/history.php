<?php

namespace vifaframework\lib;

use vifaframework\exception\handle as HandleException;
use vifaframework\lib\session;

class history {
	private static $temp_menu = null;
	private static $instance = null;

	public static function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function previous()
	{
		self::$temp_menu = 'previous';
		return new static;
	}

	public static function input($name = '')
	{
		$app = session::get('__app__');

		if (!isset($app['last_input']))
		{
			$app['last_input'] = [];
			session::set('__app__', $app);
		}
		
		$input = $app['last_input'];

		if ($name == '')
		{
			return $input;
		} else {
			if (isset($input[$name]))
			{
				return $input[$name];
			} else 
			{
				return null;
			}
		}
	}

	public static function page()
	{
		$app = session::get('__app__');

		if (self::$temp_menu == 'previous')
		{
			if (isset($app['previous_page']))
			{
				return $app['previous_page'];
			} else 
			{
				return null;
			}
		} else 
		{
			try {
				throw new HandleException('Fungsi Ini Membutuhkan Metode Lain [history::previous].', get_called_class(), 'Untuk Menggunakan FUngsi Ini Tulis <b>history::previous()->page()</b> ');
			} catch (HandleException $e) {
				$e->renderError();
			}
		}
	}

	public static function form()
	{
		$app = session::get('__app__');

		if (self::$temp_menu == 'previous')
		{
			if (isset($app['last_form']))
			{
				return $app['last_form'];
			} else 
			{
				return null;
			}
		} else 
		{
			try {
				throw new HandleException('Fungsi Ini Membutuhkan Metode Lain [history::previous].', get_called_class(), 'Untuk Menggunakan FUngsi Ini Tulis <b>history::previous()->page()</b> ');
			} catch (HandleException $e) {
				$e->renderError();
			}
		}
	}
}