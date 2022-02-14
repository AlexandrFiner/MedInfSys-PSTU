<?php

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

        ]);
        $display->getColumnFilters()->setPlacement('table.header');
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('name')->setLabel('ФИО'),
        ]);

        $display->paginate(15);
        return $display;
    });

    $model->onCreateAndEdit(function () {
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::text('name', 'ФИО')->required(),
        ]);
        return $form;
    });
});
