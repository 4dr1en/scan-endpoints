<?php

use App\Models\User;
use App\Models\Workspace;
use App\Models\TargetsMonitored;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (
            !Schema::hasTable('user_workspace')
        ) {
            Schema::create('user_workspace', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete()->cascadeOnUpdate();
                $table->string('role')->default('watcher');
                $table->timestamps();
            });
        }

        if (
            !Schema::hasColumn('targets_monitoreds', 'workspace_id')
        ) {
            // Create a field workspace_id in the targets table
            Schema::table('targets_monitoreds', function (Blueprint $table) {
                $table->foreignId('workspace_id')->nullable()->constrained('workspaces')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }

        // Create a workspace for each user
        $users = User::all();
        foreach ($users as $user) {
            $workspace = new Workspace();
            $workspace->name = ($user->display_name ?? $user->first_name) . "'s Workspace";
            $workspace->description = 'This is ' . ($user->display_name ?? $user->first_name) . "'s default workspace.";
            $workspace->save();

            // Create the relationship between the user and the workspace in the joint table
            $user->workspaces()->attach($workspace->id, ['role' => 'owner']);

            // Move all existing target of the user to its default workspace
            $targets = TargetsMonitored::where('user_id', $user->id)->get();
            foreach ($targets as $target) {
                // Create the relationship between the target and the workspace
                $target->workspace_id = $workspace->id;
                $target->save();
            }
        }

        // Remove the field user_id from the targets table
        Schema::table('targets_monitoreds', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        // make the field 'workspace_id' in the targets table not nullable
        Schema::table('targets_monitoreds', function (Blueprint $table) {
            $table->foreignId('workspace_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_workspace', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['workspace_id']);
        });

        Schema::table('targets_monitoreds', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });

        Schema::dropIfExists('user_workspace');
    }
};