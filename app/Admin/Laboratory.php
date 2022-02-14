<?php

use App\Models\Laboratory;
use App\Models\ProfileLaboratories;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Laboratory::class, function (ModelConfiguration $model) {
    $model->setTitle('Лаборатории');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();


        $display->setColumnFilters([
            null,

            AdminColumnFilter::text()
                ->setPlaceholder('Название')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),

        ]);
        $display->getColumnFilters()->setPlacement('table.header');
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('name')->setLabel('Название'),
            AdminColumn::lists('profiles.name', 'ProfileLaboratories')->setLabel('Профили'),
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
            AdminFormElement::multiselect('profiles', 'Профили', ProfileLaboratories::class)->setDisplay('name'),
        ]);
        return $form;
    });
})
    ->addMenuPage(Laboratory::class, 4)
    ->setIcon('fas fa-medkit');

