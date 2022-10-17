<?php

use LemurEngine\LemurBot\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'deleted_at'))
        {
            Schema::table('users', function (Blueprint $table)
            {
                $table->timestamp('deleted_at', 0)->nullable()->after('remember_token');
            });
        }

        if (!Schema::hasColumn('users', 'slug'))
        {
            Schema::table('users', function (Blueprint $table)
            {
                $table->string('slug')->nullable()->after('id');
            });

            User::whereNull('slug')->update(['slug'=>Str::random(16)]);
        }
        if (!Schema::hasColumn('users', 'api_token'))
        {
            Schema::table('users', function (Blueprint $table)
            {
                $table->string('api_token', 80)->nullable()->unique()->after('password');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('api_token');
            $table->dropColumn('deleted_at');
        });
    }
}
