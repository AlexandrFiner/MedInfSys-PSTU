<?php

use App\Models\Department;
use App\Models\Hospital;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Department::class, function (ModelConfiguration $model) {
    $model->setTitle('Корпусы');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();

        $display->setColumnFilters([
            null,

            AdminColumnFilter::text()
                ->setPlaceholder('Название')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select()
                ->setModelForOptions(Hospital::class, 'name')
                ->setColumnName('hospital_id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),
        ]);
        $display->getColumnFilters()->setPlacement('table.header');
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('name')->setLabel('Название'),
            AdminColumn::text('hospital.name')->setLabel('Больница'),
        ]);

        $display->paginate(15);
        return $display;
    });

    $model->onCreate(function () {
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::text('name', 'Название корпуса')->required(),
            AdminFormElement::select('hospital_id', 'Больница', Hospital::class)->setDisplay('name')->required(),
        ]);
        return $form;
    });
})
    ->addMenuPage(Department::class, 3)
    ->setIcon('fas fa-medkit');

