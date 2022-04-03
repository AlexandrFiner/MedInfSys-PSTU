<?php

use App\Models\ProfileLaboratories;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(ProfileLaboratories::class, function (ModelConfiguration $model) {
    $model->setTitle('Лаборатории');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();
        $display->setDisplaySearch(true);

        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('name')->setLabel('Название'),
        ]);

        $display->paginate(15);
        return $display;
    });

    $model->onCreate(function () {
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::text('name', 'Название')->required(),
        ]);
        return $form;
    });

    $model->onEdit(function () {
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::text('name', 'Название')->required(),
        ]);
        return $form;
    });
});
