<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned(); // the client

            // the client data are archived in this table to pevent any loss of data among the invoices
            $table->string('client_name')->nullable(); // client name.
            $table->string('client_idnumber')->nullable(); // client passport.
            $table->string('client_address')->nullable(); // client address.
            $table->string('client_email')->nullable(); // client email.
            $table->decimal('total_price', 8, 2)->nullable(); // final invoice price.


            $table->integer('company_id')->default(1); // link to copany details: name, legal number... - todo
            $table->string('invoice_number')->nullable(); // the final invoice number, generated by the accounting software
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pre_invoice_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pre_invoice_id')->unsigned(); // pre_invoice id
            $table->string('product_name');
            $table->decimal('price', 8, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('pre_invoices', function (Blueprint $table) {
            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');
        });

        Schema::table('pre_invoice_details', function (Blueprint $table) {
            $table->foreign('pre_invoice_id')
            ->references('id')->on('pre_invoices')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pre_invoices');
    }
}
