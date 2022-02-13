<?php

use App\Models\Hospital;
use App\Models\Laboratory;
use App\Models\Polyclinic;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Polyclinic::class, function (ModelConfiguration $model) {
    $model->setTitle('Поликлиники');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();

        $display->setColumnFilters([
            null,

            AdminColumnFilter::text()
                ->setPlaceholder('ФИО')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select()
                ->setModelForOptions(Hospital::class, 'name')
                ->setColumnName('hospital.id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),
        ]);
        $display->getColumnFilters()->setPlacement('table.header');
        $display->setDisplaySearch(true);
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

        $column = AdminFormElement::columns()
            ->addColumn(function () {
                return [
                    AdminFormElement::text('name', 'Название')->required(),
                ];
            })
            ->addColumn(function () {
                return [
                    AdminFormElement::select('hospital_id', 'Больница', Hospital::class)->setDisplay('name'),
                    AdminFormElement::multiselect('laboratories', 'Лаборатории', Laboratory::class)->setDisplay('name'),
                ];
            });

        $form->addBody($column);
        return $form;
    });

    $model->onEdit(function ($id) {
        $form = AdminForm::panel();

        $column = AdminFormElement::columns()
            ->addColumn(function () {
                return [
                    AdminFormElement::text('name', 'Название')->required(),
                ];
            })
            ->addColumn(function () {
                return [
                    AdminFormElement::select('hospital_id', 'Больница', Hospital::class)->setDisplay('name'),
                    AdminFormElement::multiselect('laboratories', 'Лаборатории', Laboratory::class)->setDisplay('name'),
                ];
            });

        /*
        $buildings = AdminDisplay::table()
            ->setModelClass(Building::class)
            ->setColumns([
                AdminColumn::text('name')->setLabel('Больница'),
            ]);
        $buildings->setApply(function ($query) use ($id) {
            $query->where('polyclinic_id', $id);
        });
        */
        $form->addBody([$column]);
        return $form;
    });
})
    ->addMenuPage(Polyclinic::class, 2)
    ->setIcon('fas fa-medkit');
