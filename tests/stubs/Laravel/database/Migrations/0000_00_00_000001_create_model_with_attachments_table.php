<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_with_attachments', function (Blueprint $table): void {
            $table->primaryUuid();
            $table->attachment('image');
            $table->timestamps(6);
        });
    }
};
