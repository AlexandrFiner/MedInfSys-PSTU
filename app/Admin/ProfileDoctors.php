<?php

use App\Models\ProfileDoctors;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(ProfileDoctors::class, function (ModelConfiguration $model) {
    $model->setTitle('Профили док.');

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
})
    ->addMenuPage(ProfileDoctors::class, 5)
    ->setIcon('fas fa-medkit');
