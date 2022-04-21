<?php

Route::get('', ['as' => 'dashboard', function () {
	return AdminSection::view('Главная', 'Главная');
}]);
