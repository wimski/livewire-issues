<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->primaryUuid();
            $table->string('name');
            $table->caseInsensitive('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->rememberToken();
            $table->timestamps(6);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->caseInsensitive('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }
};
