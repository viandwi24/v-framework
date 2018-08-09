<?php

namespace vframework\kernel;

use vframework\kernel\config;

class console {
	public function add_controller ($name){
		config::apply();
		$path_controller = config::$app_dir . '/controllers';

		$controller_file = <<<EOT
<?php

use vframework\kernel\\request as Request;
use vframework\base\\view;

class {?} {
	public function index(){
	}

}
EOT;
		$controller_file = str_replace('{?}', $name, $controller_file);

		if (file_exists($path_controller . '/' . $name . '.php')) {
			return error("Controller Dengan Nama $name Sudah Ada!", "[".$path_controller . '/' . $name . '.php'."]");
		} else {
           file_put_contents($path_controller . '/' . $name . '.php', $controller_file);
           return true;
		}


	}

	public function del_controller ($name) {
		config::apply();
		$path_controller = config::$app_dir . '/controllers';

		if (!file_exists($path_controller . '/' . $name . '.php')) {
			return error("Controller $name Tidak Ada!", "[".$path_controller . '/' . $name . '.php'."]");
		} else {
			$confirm = readline('Yakin Ingin Menghapus Controller ' . $name . '? [yes/YES] : ');

			if ($confirm == 'yes' or $confirm == 'YES'){
				unlink($path_controller . '/' . $name . '.php');
				return true;
			} else {
				return die("Penghapusan Di Gagalkan.");
			}
		}
	}

	public function reset_routes($name){
		config::apply();
		$path_routes = config::$route_dir;

		if ($name == 'app' or $name == 'error' or $name == 'console' or $name == 'api') {

			$confirm = readline('Yakin Mereset Routes ' . $name . '? [yes/YES] : ');
			if ($confirm == 'yes' or $confirm == 'YES'){

				if (file_exists($path_routes . '/' . $name . '.php')) {
					unlink($path_routes . '/' . $name . '.php');
				}

				switch ($name) {
					case 'app':
						$filenya = <<<PHP
<?php
use vframework\kernel\\route;

route::get('/', function(){
	return "Hello World!";
}); #HOME ditandai dengan url request satu slash saja /
PHP;
						break;
					case 'error':
						$filenya = <<<EOT
<?php
use vframework\kernel\\route;
use vframework\base\\view;

#### DONT REMOVE THIS
route::error('system_error', function({1}){ 
	{?} = "<b> Error </b>: " . {2} .
			  '<div style="border: 1px solid red;text-align: center;padding:5px;">'
			  . {3} .
			  '</div>';

	return {?};
});
route::error('404', function($arg){
	return view::make('error.404', ['description' => 'Alamat URL Yang Diminta Tidak Ada.']);
});

#### Tulis Route Error Lainya Dibawah Ini
EOT;
						$filenya = str_replace('{?}', '$string', $filenya);
						$filenya = str_replace('{1}', '$arg', $filenya);
						$filenya = str_replace('{2}', '$arg[0]', $filenya);
						$filenya = str_replace('{3}', '$arg[1]', $filenya);
						break;
					default:
						return error("Tidak ada yang dihapus!", "Aksi tidak terlaksana.");
						break;
				}

				file_put_contents($path_routes . '/' . $name . '.php', $filenya);
				return true;
			} else {
				return die("Penghapusan Di Gagalkan.");
			}

		} else {
			return error("File Routes $name Tidak Ada!", "Routes File Hanya : [app|error|console|api]");
		}

	}
}