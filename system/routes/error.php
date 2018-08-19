<?php

use vframework\router\error;

##### System Error
error::set('404', function(){
	return "404";
});