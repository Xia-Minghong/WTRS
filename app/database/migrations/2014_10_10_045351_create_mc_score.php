<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;

class CreateMcScore extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //If the table exists, drop it
        if (Schema::hasTable('mc_score')) {
            CreateMcScore::down();
        }

        Schema::create('mc_score', function(Blueprint $table)
        {
            $table->string('short_name');
            $table->integer('mc_score');
        });

        Schema::table('mc_score', function($table)
        {
            //setting primary key
            $table->primary('short_name');

            //@Depreciated
            //No enforce referential integrity on the foreign key
            //As according the business rule,
            //MC scores need to be kept as records even if teachers particulars are re-uploaded
            //Records with no references will be checked and deleted
            //during the particulars uploading processing
            //
            //$table->foreign('short_name')->references('short_name')->on('teacher')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mc_score');
    }

}
