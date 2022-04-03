<?php

use App\Models\Department;
use App\Models\Hospital;
use App\Models\Polyclinic;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Hospital::class, function (ModelConfiguration $model) {
    $model->setTitle('Больницы');

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

    $model->onEdit(function ($id) {
        $form = AdminForm::panel();

        $column = AdminFormElement::columns()
            ->addColumn(function () {
                return [
                    AdminFormElement::text('name', 'Название')->required(),
                ];
            });

        $departments = AdminDisplay::table()
            ->setModelClass(Department::class)
            ->setColumns([
                AdminColumn::text('name')->setLabel('Корпус'),
            ]);
        $departments->setApply(function ($query) use ($id) {
            $query->where('hospital_id', $id);
        });

        $buildings = AdminDisplay::table()
            ->setModelClass(Polyclinic::class)
            ->setColumns([
                AdminColumn::text('name')->setLabel('Поликлиника'),
            ]);
        $buildings->setApply(function ($query) use ($id) {
           $query->where('polyclinics.hospital_id', $id);
        });

        $form->addBody([$column, $departments, $buildings]);
        return $form;
    });
})
    ->addMenuPage(Hospital::class, 3)
    ->setIcon('fas fa-medkit');
