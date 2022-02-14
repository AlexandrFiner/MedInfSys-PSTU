<?php

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Polyclinic;
use App\Models\ProfileDoctors;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Doctor::class, function (ModelConfiguration $model) {
    $model->setTitle('Врачи');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();

        $display->setColumnFilters([
            null,

            AdminColumnFilter::text()
                ->setPlaceholder('ФИО')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select([
                'male' => 'Мужской',
                'female' => 'Женский',
            ], 'Пол')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select()
                ->setModelForOptions(ProfileDoctors::class, 'name')
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
            AdminColumn::custom('gender', function($patient) {
                return match ($patient['gender']) {
                    'male' => '<i class="fas fa-mars" style="color: #009ff1;font-size: 30px"></i>',
                    'female' => '<i class="fas fa-venus" style="color: #f284af;font-size: 30px"></i>',
                };
            })->setLabel('Пол'),
            AdminColumn::text('profile.name')->setLabel('Профиль'),
            AdminColumn::lists('polyclinics.name', 'Polyclinic')->setLabel('Поликлиники'),
            AdminColumn::lists('hospitals.name', 'Hospital')->setLabel('Больницы'),
        ]);

        $display->paginate(15);
        return $display;
    });

    $model->onCreate(function () {
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::text('name', 'ФИО')->required(),
            AdminFormElement::select('gender', 'Пол', [
                'male' => 'Мужской',
                'female' => 'Женский',
            ])->required(),
            AdminFormElement::select('profile_doctors_id', 'Профиль', ProfileDoctors::class)
                ->setDisplay('name')
                ->required(),
        ]);
        return $form;
    });

    $model->onEdit(function (Doctor $doctor) {
        $form = AdminForm::panel();

        // Todo:: максимальное количество работ в зависимости от звания
        $form->addBody([
            AdminFormElement::text('name', 'ФИО')->required(),
            AdminFormElement::select('gender', 'Пол', [
                'male' => 'Мужской',
                'female' => 'Женский',
            ])->required(),
            AdminFormElement::select('profile_doctors_id', 'Профиль', ProfileDoctors::class)
                ->setDisplay('name')
                ->required(),
            AdminFormElement::select('degree', 'Звание', [
                ' ' => 'Нет звания',
                'candidate' => 'Кандидат',
                'doctor' => 'Доктор',
            ]),
            AdminFormElement::multiselect('polyclinics', 'Поликлиники', Polyclinic::class)->setDisplay('name'),
            AdminFormElement::multiselect('hospitals', 'Больницы', Hospital::class)->setDisplay('name'),
        ]);
        return $form;
    });
})
    ->addMenuPage(Doctor::class, 1)
    ->setIcon('fa fa-user');
