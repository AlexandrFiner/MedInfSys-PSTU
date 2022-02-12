<?php

use App\Models\Laboratory;
use App\Models\Polyclinic;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Polyclinic::class, function (ModelConfiguration $model) {
    $model->setTitle('Поликлиники');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();
        $display->setDisplaySearch(true);
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('name')->setLabel('Название'),
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
