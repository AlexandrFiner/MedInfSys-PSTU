<?php

use App\Models\Hospital;
use App\Models\Polyclinic;
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

            AdminColumnFilter::select()
                ->setModelForOptions(Polyclinic::class, 'name')
                ->setColumnName('polyclinics.id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select()
                ->setModelForOptions(Hospital::class, 'name')
                ->setColumnName('hospitals.id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),

        ]);
        $display->getColumnFilters()->setPlacement('table.header');
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('name')->setLabel('ФИО'),
            AdminColumn::text('profile.name')->setLabel('Должность'),
            AdminColumn::lists('polyclinics.name', 'Polyclinic')->setLabel('Поликлиники'),
            AdminColumn::lists('hospitals.name', 'Hospital')->setLabel('Больницы'),
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
            AdminFormElement::multiselect('polyclinics', 'Поликлиники', Polyclinic::class)->setDisplay('name'),
            AdminFormElement::multiselect('hospitals', 'Больницы', Hospital::class)->setDisplay('name'),
        ]);
        return $form;
    });
});
