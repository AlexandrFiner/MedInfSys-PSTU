<?php

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\PolyclinicAppointment;
use App\Models\ProfileDoctors;
use SleepingOwl\Admin\Model\ModelConfiguration;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;

AdminSection::registerModel(PolyclinicAppointment::class, function (ModelConfiguration $model) {
    $model->setTitle('Поликлиника');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();
        $display->setNewEntryButtonText('Запись пациента');


        $display->setNewEntryButtonText('Добавить пациента');

        // Todo:: сделать кнопки записи в этой модели
        $display->setColumnFilters([
            null,

            AdminColumnFilter::text()
                ->setPlaceholder('ФИО')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select([
                'waiting' => 'Запись',
                'error' => 'Не явился',
                'success' => 'Успешно',
            ], 'Пол')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::text()
                ->setPlaceholder('ФИО')
                ->setOperator(FilterInterface::CONTAINS)
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::range()->setFrom(
                AdminColumnFilter::date()->setPlaceholder('С даты')->setFormat('Y.m.d H:m')
            )->setTo(
                AdminColumnFilter::date()->setPlaceholder('По дату')->setFormat('Y.m.d H:m')
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
            AdminColumn::text('patient.name')->setLabel('Пациент'),
            AdminColumn::custom('status', function($appointment) {
                return match ($appointment['status']) {
                    'waiting' => '<small class="badge badge-warning">Запись</small>',
                    'error' => '<small class="badge badge-danger">Не явился</small>',
                    'success' => '<small class="badge badge-success">Успешно</small>',
                };
            })->setLabel('Статус'),
            AdminColumn::text('doctor.name')->setLabel('Доктор'),
            AdminColumn::text('visit_time')->setLabel('Время записи'),
            AdminColumn::text('polyclinic.name')->setLabel('Поликлиника'),
        ]);

        $display->paginate(15);
        return $display;
    });

    $model->onCreate(function () {
        $form = AdminForm::panel();

        $form->addBody([
            AdminFormElement::select('patient_id', 'Пациент', Patient::class)
                ->setDisplay('name')
                ->required(),

            AdminFormElement::select('profile_doctors_id', 'Профиль', ProfileDoctors::class)
                ->setDisplay('name')
                ->required(),

            AdminFormElement::dependentselect('polyclinic_id', 'Поликлиника')
                ->setModelForOptions( Polyclinic::class, 'name' )
                ->setDisplay('name')
                ->setHtmlAttribute('placeholder', 'Укажите пациента')
                ->setDataDepends(['patient_id'])
                ->setLoadOptionsQueryPreparer(function($element, $query) {
                    $patient = Patient::find($element->getDependValue('patient_id'));
                    $polyclinic_id = $patient->polyclinic_id ?? 0;
                    return $query->where('id', $polyclinic_id);
                })
                ->required(),

            AdminFormElement::dependentselect('doctor_id', 'Доктор')
                ->setModelForOptions( Doctor::class, 'name' )
                ->setDisplay('name')
                ->setHtmlAttribute('placeholder', 'Укажите пациента')
                ->setDataDepends(['profile_doctors_id', 'polyclinic_id'])
                ->setLoadOptionsQueryPreparer(function($element, $query) {
                    $polyclinic_id = $element->getDependValue('polyclinic_id') ?? 0;
                    $doctor_profile_id = $element->getDependValue('profile_doctors_id') ?? 0;
                    $doctorHospitals = DB::table('doctor_polyclinics')
                        ->join('doctors', 'id', '=', 'doctor_polyclinics.doctor_id')
                        ->where('doctor_polyclinics.polyclinic_id', $polyclinic_id)
                        ->where('doctors.profile_doctors_id', $doctor_profile_id)
                        ->get();
                    $doctorsList = [];
                    foreach ($doctorHospitals as $doctor) {
                        $doctorsList[] = $doctor->doctor_id;
                    }
                    return $query->whereIn('id', $doctorsList);
                })
                ->required(),

            AdminFormElement::datetime('visit_time', 'Дата и время записи')->required(),
        ]);
        return $form;
    });
});
