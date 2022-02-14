<?php

use App\Models\AppointmentHospital;
use App\Models\Patient;
use App\Models\Polyclinic;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Patient::class, function (ModelConfiguration $model) {
    $model->setTitle('Пациенты');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();
        $display->setNewEntryButtonText('Добавить пациента');

        // Todo:: сделать кнопки записи в этой модели
        $display->setColumnFilters([
            null,

            AdminColumnFilter::text()
                ->setPlaceholder('ФИО')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),


            AdminColumnFilter::range()->setFrom(
                AdminColumnFilter::text()->setPlaceholder('от')
            )->setTo(
                AdminColumnFilter::text()->setPlaceholder('до')
            ),
            AdminColumnFilter::range()->setFrom(
                AdminColumnFilter::text()->setPlaceholder('от')
            )->setTo(
                AdminColumnFilter::text()->setPlaceholder('до')
            ),

            AdminColumnFilter::select([
                'male' => 'Мужской',
                'female' => 'Женский',
            ], 'Пол')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::range()->setFrom(
                AdminColumnFilter::date()->setPlaceholder('С даты')->setFormat('Y.m.d')
            )->setTo(
                AdminColumnFilter::date()->setPlaceholder('По дату')->setFormat('Y.m.d')
            ),

            AdminColumnFilter::select()
                ->setModelForOptions(Polyclinic::class, 'name')
                ->setColumnName('polyclinic_id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),
        ]);
        $display->getColumnFilters()->setPlacement('table.header');
        $display->setColumns([
            AdminColumn::text('id')->setLabel('#'),
            AdminColumn::text('name')->setLabel('ФИО'),
            /*
            AdminColumn::custom('weight', function($patient) {
                return $patient['weight'].' кг';
            })->setLabel('Вес'),
            AdminColumn::custom('height', function($patient) {
                return $patient['height'].' см';
            })->setLabel('Рост'),
            */
            AdminColumn::text('weight')->setLabel('Вес'),
            AdminColumn::text('height')->setLabel('Рост'),
            AdminColumn::custom('gender', function($patient) {
                return match ($patient['gender']) {
                    'male' => '<i class="fas fa-mars" style="color: #009ff1;font-size: 30px"></i>',
                    'female' => '<i class="fas fa-venus" style="color: #f284af;font-size: 30px"></i>',
                };
            })->setLabel('Пол'),
            AdminColumn::text('birthday')->setLabel('Дата рождения'),
            AdminColumn::text('polyclinic.name')->setLabel('Поликлиника прикрипления')
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
            AdminFormElement::date('birthday', 'Дата рождения')->required(),
            AdminFormElement::float('weight', 'Вес')->setMin(0)->setStep(0.01)->required(),
            AdminFormElement::float('height', 'Рост')->setMin(0)->setStep(0.01)->required(),
            AdminFormElement::select('polyclinic_id', 'Поликлиника', Polyclinic::class)->setDisplay('name')->required(),
        ]);
        return $form;
    });

    $model->onEdit(function ($id) {

        $tabs = AdminDisplay::tabbed();
        $tabs->setTabs(function ($tabId) use ($id) {
            $tabs = [];

            $tabs[] = AdminDisplay::tab(AdminForm::elements([
                AdminFormElement::text('name', 'ФИО')->required(),
                AdminFormElement::select('gender', 'Пол', [
                    'male' => 'Мужской',
                    'female' => 'Женский',
                ])->required(),
                AdminFormElement::date('birthday', 'Дата рождения')->required(),
                AdminFormElement::number('weight', 'Вес')->setMin(0)->setStep(0.01)->required(),
                AdminFormElement::number('height', 'Рост')->setMin(0)->setStep(0.01)->required(),
                AdminFormElement::select('polyclinic_id', 'Поликлиника', Polyclinic::class)->setDisplay('name')->required(),
            ]))->setLabel('Данные о пациенте');

            $tabs[] = AdminDisplay::tab(AdminForm::elements([

                AdminDisplay::table()
                    ->setModelClass(AppointmentHospital::class)
                    ->setColumns([
                        AdminColumn::text('name')->setLabel('Запись'),
                    ])
                    ->setApply(function ($query) use ($id) {
                        $query->where('patient_id', $id);
                    })
            ]))->setLabel('История');

            return $tabs;
        });
        return AdminForm::card()
            ->addBody([$tabs]);
        /*
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::text('name', 'ФИО')->required(),
        ]);

        return $form;
        */
    });
})
    ->addMenuPage(Patient::class, 1)
    ->setIcon('fa fa-user');
