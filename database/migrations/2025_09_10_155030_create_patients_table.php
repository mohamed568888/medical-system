<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{

    public function up()

    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            // الربط بالعيادة (تأكد أن ملف عياداتك تاريخه أقدم من هذا الملف)
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');

            $table->string('name');
            $table->string('phone');
            $table->string('document')->nullable(); // حقل الرفع الرقمي (مسار الملف)
            $table->text('Diagnosis')->nullable(); // التشخيص
            $table->text('Chronic_diseases')->nullable(); //الامراض المزمنه
            $table->date('birth_date')->nullable();
            $table->string('gender');
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
