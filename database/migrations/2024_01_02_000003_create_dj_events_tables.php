<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend schedule_shows with DJ relation
        Schema::table('schedule_shows', function (Blueprint $table) {
            $table->foreignId('dj_id')->nullable()->after('description')->constrained('team_members')->nullOnDelete();
            $table->boolean('is_live')->default(false)->after('dj_id');
        });
        
        // DJ extended profiles
        Schema::create('dj_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_member_id')->constrained()->cascadeOnDelete();
            $table->string('stage_name')->nullable();
            $table->string('genre')->nullable();
            $table->text('biography')->nullable();
            $table->string('equipment')->nullable();
            $table->string('mixcloud_url')->nullable();
            $table->string('soundcloud_url')->nullable();
            $table->string('spotify_url')->nullable();
            $table->string('apple_music_url')->nullable();
            $table->boolean('is_resident')->default(false);
            $table->integer('years_experience')->nullable();
            $table->json('top_tracks')->nullable(); // Array of favorite tracks
            $table->timestamps();
        });
        
        // Events/Gigs
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('venue')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->decimal('ticket_price', 8, 2)->nullable();
            $table->string('ticket_url')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->foreignId('featured_dj_id')->nullable()->constrained('team_members')->nullOnDelete();
            $table->timestamps();
        });
        
        // Event DJ lineup
        Schema::create('event_dj', function (Blueprint $table) {
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dj_id')->constrained('team_members')->cascadeOnDelete();
            $table->time('set_time')->nullable();
            $table->integer('order')->default(0);
            $table->primary(['event_id', 'dj_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_dj');
        Schema::dropIfExists('events');
        Schema::dropIfExists('dj_profiles');
        
        Schema::table('schedule_shows', function (Blueprint $table) {
            $table->dropForeign(['dj_id']);
            $table->dropColumn(['dj_id', 'is_live']);
        });
    }
};
