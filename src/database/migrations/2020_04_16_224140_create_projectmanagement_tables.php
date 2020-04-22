<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectmanagementTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            if(!Schema::hasTable('comments')){
                Schema::create('comments', function ($table){
                    $table->integer('id');
                    $table->integer('gorev_id');
                    $table->string('name', 50);
                    $table->longText('comment');
                    $table->tinyInteger('approved');
                    $table->timestamp('created_at');
                    $table->tinyInteger('isDelete');
                });
            }

            if(!Schema::hasTable('work_feedback')){
                Schema::create('work_feedback', function ($table){
                    $table->integer('id');
                    $table->integer('work_id');
                    $table->longText('notes');
                    $table->tinyInteger('isAdmin');
                    $table->tinyInteger('isDelete');
                    $table->timestamp('created_at');
                    $table->timestamp('updated_at');
                });
            }

            if(!Schema::hasTable('project')){
                Schema::create('project', function ($table){
                    $table->integer('id');
                    $table->integer('gorevler_id');
                    $table->integer('project_leader');
                    $table->integer('task_id');
                    $table->integer('post_id');
                    $table->string('project_name', 255);
                    $table->mediumText('description');
                    $table->tinyInteger('isShow');
                    $table->timestamp('created_at');
                    $table->timestamp('updated_at');
                    $table->tinyInteger('isDelete');
                    $table->date('starting_date');
                    $table->date('end_date');

                });
            }

            if(!Schema::hasTable('tasks')){
                Schema::create('tasks', function ($table){
                    $table->integer('id');
                    $table->longText('task');
                    $table->tinyInteger('approved');
                    $table->tinyInteger('isShow');
                    $table->timestamp('created_at');
                    $table->timestamp('updated_at');
                    $table->tinyInteger('isDelete');
                    $table->integer('entry_id');
                    $table->integer('gorev_id');
                    $table->string('name', 255);
                    $table->date('end_date');
                    $table->date('start_date');
                });
            }


            if(!Schema::hasTable('user_task')){
                Schema::create('user_task', function ($table){
                    $table->integer('id');
                    $table->integer('user_id');
                    $table->integer('gorev_id');
                    $table->integer('gorevler_id');
                    $table->timestamp('created_at');
                    $table->timestamp('updated_at');
                });
            }

            if(!Schema::hasTable('working_times')){
                Schema::create('working_times', function ($table){
                    $table->integer('id');
                    $table->integer('work_id');
                    $table->date('start_time');
                    $table->date('end_time');
                    $table->tinyInteger('status');
                    $table->timestamp('created_at');
                    $table->timestamp('updated_at');

                });
            }

            if(!Schema::hasTable('work_calender')){
                Schema::create('work_calender', function ($table){
                    $table->integer('id');
                    $table->integer('project_id');
                    $table->string('field', 255);
                    $table->string('user_name', 255);
                    $table->integer('user_id');
                    $table->integer('add_id');
                    $table->longText('content');
                    $table->tinyInteger('status');
                    $table->date('timedata');
                    $table->date('start_time');
                    $table->date('finish_date');
                    $table->tinyInteger('isDelete');
                    $table->timestamp('created_at');
                    $table->timestamp('updated_at');

                });
            }
            if(!Schema::hasTable('work_gallery')){
                Schema::create('work_gallery', function ($table){
                    $table->integer('id');
                    $table->integer('work_id');
                    $table->tinyInteger('add_admin');
                    $table->string('gallery_image', 255);
                    $table->tinyInteger('isDelete');
                    $table->timestamp('created_at');
                    $table->timestamp('updated_at');



                });
            }

            if(!Schema::hasTable('administrator')){
                Schema::create('administrator', function ($table){
                    $table->integer('id');
                    $table->string('name', 60);
                    $table->string('email', 150);
                    $table->string('photo', 255);
                    $table->string('password', 60);
                    $table->tinyInteger('isAvailable');
                    $table->tinyInteger('isDelete');
                    $table->string('remember_token', 100);
                    $table->timestamp('created_at');
                    $table->timestamp('updated_at');

                });
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projectmanagement_tables');
    }
}
