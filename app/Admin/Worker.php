<?php

use App\Models\ProfileWorkers;
use App\Models\Worker;

use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Worker::class, function (ModelConfiguration $model) {
    $model->setTitle('Персонал');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();

        $display->setColumnFilters([
            null,

            AdminColumnFilter::text()
                ->setPlaceholder('ФИО')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select()
                ->setModelForOptions(ProfileWorkers::class, 'name')
                ->setColumnName('profile.id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),

        ]);
        $display->getColumnFilters()->setPlacement('table.header');
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('name')->setLabel('ФИО'),
            AdminColumn::text('profile.name')->setLabel('Должность'),
        ]);

        $display->paginate(15);
        return $display;
    });

    $model->onCreateAndEdit(function () {
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::text('name', 'ФИО')->required(),
            AdminFormElement::select('profile_workers_id', 'Должность', ProfileWorkers::class)
                ->setDisplay('name')
                ->required(),
        ]);
        return $form;
    });
});
