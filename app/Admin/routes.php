<?php

Route::get('', ['as' => 'admin.dashboard', function () {
	return AdminSection::view('Главная', 'Главная');
}]);
