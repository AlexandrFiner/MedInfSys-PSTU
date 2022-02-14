<?php

use App\Models\PolyclinicAppointment;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(PolyclinicAppointment::class, function (ModelConfiguration $model) {
    $model->setTitle('Поликлиника');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
        ]);

        $display->paginate(15);
        return $display;
    });
});
