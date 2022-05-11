<?php

use App\Models\Department;
use App\Models\DepartmentRoom;
use App\Models\Hospital;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(DepartmentRoom::class, function (ModelConfiguration $model) {
    $model->setTitle('Палаты');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();
        $display->setNewEntryButtonText('Добавить палату');

        $display->setColumnFilters([
            null,

            AdminColumnFilter::text()
                ->setPlaceholder('Название')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select()
                ->setModelForOptions(Department::class, 'name')
                ->setColumnName('department_id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select()
                ->setModelForOptions(Hospital::class, 'name')
                ->setColumnName('department.hospital.id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select()
                ->setModelForOptions(Hospital::class, 'name')
                ->setColumnName('department.hospital.id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),
        ]);
        $display->getColumnFilters()->setPlacement('table.header');
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('name')->setLabel('Название'),
            AdminColumn::text('department.name')->setLabel('Корпус'),
            AdminColumn::text('department.hospital.name')->setLabel('Больница'),
            AdminColumn::text('beds')->setLabel('Коек в палате'),
            AdminColumn::text('occupied_beds')->setLabel('Коек занято'),
            AdminColumn::text('is_free_room')->setLabel('Комната свободна'),
        ]);

        $display->paginate(15);
        return $display;
    });
})
    ->addMenuPage(DepartmentRoom::class, 3)
    ->setIcon('fas fa-medkit');

