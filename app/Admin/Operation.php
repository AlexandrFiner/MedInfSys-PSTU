<?php

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Operation;
use App\Models\Patient;
use App\Models\Polyclinic;
use Illuminate\Support\Facades\DB;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Operation::class, function (ModelConfiguration $model) {
    $model->setTitle('Операции');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();

        $display->setColumnFilters([
            null,

            AdminColumnFilter::text()
                ->setPlaceholder('Название операции')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::text()
                ->setPlaceholder('ФИО')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::text()
                ->setPlaceholder('ФИО')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),

        ]);

        $display->getColumnFilters()->setPlacement('table.header');
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('purpose')->setLabel('Операция'),
            AdminColumn::text('patient.name')->setLabel('Пациент'),
            AdminColumn::text('doctor.name')->setLabel('Доктор'),
        ]);

        $display->paginate(15);
        return $display;
    });

    $model->onCreate(function () {
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::text('purpose', 'Название операции')->required(),

            AdminFormElement::select('organization_type', 'Тип организации', [
                Polyclinic::class => 'Поликлиника',
                Hospital::class => 'Больница',
            ])->required(),

            AdminFormElement::dependentselect('organization_id', 'Организация')
                ->setModelForOptions(Hospital::class, 'name')
                ->setDataDepends(['organization_type'])
                ->setLoadOptionsQueryPreparer(function($item, $query) {
                    if($item->getDependValue('organization_type')) {
                        $table = app($item->getDependValue('organization_type'))->getTable();
                        return DB::table($table)->select('*');
                    }
                    return $query;
                })
                ->required(),

            AdminFormElement::select('patient_id', 'Пациент', Patient::class)->setDisplay('name')->required(),
            AdminFormElement::select('doctor_id', 'Доктор', Doctor::class)->setDisplay('name')->required(),

        ]);
        return $form;
    });
})
    ->addMenuPage(Operation::class, 1)
    ->setIcon('fas fa-syringe');
