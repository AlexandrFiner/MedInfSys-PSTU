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

        $hospitalsList = Hospital::all();
        $hospitals = [];
        foreach ($hospitalsList as $hospital) {
            $hospitals[Hospital::class.'_'.$hospital->id] = $hospital->name;
        }

        $polyclinicsList = Polyclinic::all();
        $polyclinics = [];
        foreach ($polyclinicsList as $polyclinic) {
            $polyclinics[Polyclinic::class.'_'.$polyclinic->id] = $polyclinic->name;
        }

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

            AdminColumnFilter::select()
                ->setOptions([
                    'Больница' => $hospitals,
                    'Поликлиника' => $polyclinics
                ])
                ->setSortable(false)
                ->multiple(),
        ]);

        $display->getColumnFilters()->setPlacement('table.header');
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('purpose')->setLabel('Операция'),
            AdminColumn::text('patient.name')->setLabel('Пациент'),
            AdminColumn::text('doctor.name')->setLabel('Доктор'),
            AdminColumn::text('organization.name', null, 'organizationIs')
                ->setLabel('Организация')
                ->setFilterCallback(function($column, $query, $search) {
                    if($search) {
                        if(is_array($search)) {
                            foreach ($search as $searchItem) {
                                $searchItem = explode('_', $searchItem);
                                $query->orWhere(function ($query) use($searchItem) {
                                    $query->where('organization_type', $searchItem[0])->where('organization_id', $searchItem[1]);
                                });
                            }
                            return $query;
                        }

                        $search = explode('_', $search);
                        $query->where('organization_type', $search[0])->where('organization_id', $search[1]);
                        return $query;
                        // dd($column, $query->toSql(), $search);
                    }
                    return $query;
                }),
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
