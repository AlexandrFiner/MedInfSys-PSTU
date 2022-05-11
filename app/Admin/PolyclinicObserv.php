<?php

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Polyclinic;
use App\Models\PolyclinicObserv;
use App\Models\ProfileDoctors;
use SleepingOwl\Admin\Model\ModelConfiguration;
use SleepingOwl\Admin\Contracts\Display\Extension\FilterInterface;

AdminSection::registerModel(PolyclinicObserv::class, function (ModelConfiguration $model) {
    $model->setTitle('Наблюдение');

    $model->onDisplay(function () {
        $display = AdminDisplay::datatablesAsync();
        $display->setNewEntryButtonText('Добавить пациента');

        // Todo:: сделать кнопки записи в этой модели
        $display->setColumnFilters([
            null,

            AdminColumnFilter::select()
                ->setModelForOptions(Patient::class, 'name')
                ->setColumnName('patient_id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select()
                ->setModelForOptions(ProfileDoctors::class, 'name')
                ->setColumnName('profile_doctors_id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),

            AdminColumnFilter::select()
                ->setModelForOptions(Doctor::class, 'name')
                ->setColumnName('doctor_id')
                ->multiple()
                ->setHtmlAttribute('style', 'width: 100%'),

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
            AdminColumn::text('profile.name')->setLabel('Профиль'),
            AdminColumn::text('doctor.name')->setLabel('Доктор'),
            AdminColumn::text('polyclinic.name')->setLabel('Поликлиника'),
        ]);

        $display->paginate(15);
        return $display;
    });

    $model->onCreateAndEdit(function () {
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
        ]);
        return $form;
    });
});
