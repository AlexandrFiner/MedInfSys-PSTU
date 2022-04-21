<?php

use App\Models\ProfileDoctors;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(ProfileDoctors::class, function (ModelConfiguration $model) {
    $model->setTitle('Врачи');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();
        $display->setDisplaySearch(true);

        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('name')->setLabel('Название'),

            AdminColumn::custom('can_operate', function ($profile) {
                return match ($profile['can_operate']) {
                    1 => '<i class="fas fa-check" style="color: green;font-size: 20px"></i>',
                    0 => '',
                };
            })->setLabel('Может проводить операции'),

            /*
            AdminColumn::custom('can_operate', function($patient) {
                return match ($patient['gender']) {
                    'male' => '<i class="fas fa-mars" style="color: #009ff1;font-size: 30px"></i>',
                    'female' => '<i class="fas fa-venus" style="color: #f284af;font-size: 30px"></i>',
                };
            })->setLabel('Может проводить операции'),
            */
        ]);

        $display->paginate(15);
        return $display;
    });

    $model->onCreate(function () {
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::text('name', 'Название')->required(),
            AdminFormElement::checkbox('can_operate', 'Может проводить операции'),
        ]);
        return $form;
    });

    $model->onEdit(function () {
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::text('name', 'Название')->required(),
            AdminFormElement::checkbox('can_operate', 'Может проводить операции'),
        ]);
        return $form;
    });
});
