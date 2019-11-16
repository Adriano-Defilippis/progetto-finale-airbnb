<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsersForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


      //relazione many-to-many apartments-features
      //Tabella APARTMENTS
      Schema::table('apartments', function (Blueprint $table) {
        $table -> bigInteger('user_id') -> unsigned() -> index();
        $table -> foreign('user_id', 'user_apartments')
               -> references('id')
               -> on('users')
               -> onDelete('cascade');

      Schema::table('apartment_feature', function (Blueprint $table) {
        $table -> bigInteger('apartment_id') -> unsigned() -> index();
        $table -> foreign('apartment_id', 'apartment_feature')
               -> references('id')
               -> on('apartments')
               -> onDelete('cascade');


        $table -> bigInteger('feature_id') -> unsigned() -> index();
        $table -> foreign('feature_id', 'features_apartments')
               -> references('id')
               -> on('features')
               -> onDelete('cascade');
             });


        $table -> bigInteger('tier_id') -> unsigned() ->default('1') -> index()->nullable();
        $table -> foreign('tier_id', 'apartment_tiers')
               -> references('id')
               -> on('tiers');
       });

       // Tabella MESSAGES
       Schema::table('messages', function (Blueprint $table) {
         $table -> bigInteger('apartment_id') -> unsigned() -> index();
         $table -> foreign('apartment_id', 'apartment_messages')
                -> references('id')
                -> on('apartments')
                -> onDelete('cascade');

       });

       // TABELLA PAGAMENTI
       Schema::table('payments', function(Blueprint $table){

         // $table->bigInteger('tier_id')->unsigned();
         // $table -> foreign('tier_id', 'tier_payment')
         //        -> references('id')
         //        -> on('tiers');

         $table -> bigInteger('apartment_id')->unsigned();
         $table -> foreign('apartment_id', 'apartment_payments')
                -> references('id')
                -> on('apartments');
       });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      // Drop Chiavi esterne apartment_feature
        Schema::table('apartment_feature', function (Blueprint $table) {

          $table -> dropForeign('apartment_feature');
          $table -> dropColumn('apartment_id');
          $table -> dropForeign('features_apartments');
          $table -> dropColumn('feature_id');
      });

        // Drop Chiavi esterne APARTMENTS
        Schema::table('apartments', function (Blueprint $table) {

          $table -> dropForeign('user_apartments');
          $table -> dropColumn('user_id');

          $table -> dropForeign('apartment_tiers');
          $table -> dropColumn('tier_id');

      });

        // Drop Chiavi esterne MESSAGES
        Schema::table('messages', function (Blueprint $table) {
          $table -> dropForeign('apartment_messages');
          $table -> dropColumn('apartment_id');

        });


        // Drop Chiavi esterne PAYMENTS
        Schema::table('payments', function (Blueprint $table) {
          // $table -> dropForeign('tier_payment');
          // $table -> dropColumn('tier_id');

          $table -> dropForeign('apartment_payments');
          $table -> dropColumn('apartment_id');

        });

    }
}
