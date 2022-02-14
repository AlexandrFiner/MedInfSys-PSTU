<?php

use App\Models\ProfileWorkers;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(ProfileWorkers::class, function (ModelConfiguration $model) {
    $model->setTitle('Персонал');

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
