<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Stage;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $stage;
    protected $task;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\StageSeeder::class); // Run the StageSeeder
        $this->user = User::factory()->create();
        $this->stage = Stage::first(); // Get the first seeded stage
        $this->task = Task::factory()->create(['user_id' => $this->user->id, 'stage_id' => $this->stage->id]);
    }

    /** @test */
    public function it_can_list_tasks()
    {
        $this->actingAs($this->user);
        Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200);
        $response->assertViewHas('tasks');
    }

    /** @test */
    public function it_can_show_a_task()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('tasks.show', $this->task->id));
        $response->assertStatus(200);
        $response->assertViewHas('task');
    }

    /** @test */
    public function it_can_store_a_task()
    {
        $this->actingAs($this->user);

        $data = [
            'title' => 'New Task',
            'description' => 'Task description',
            'stage_id' => $this->stage->id,
        ];

        $response = $this->post(route('tasks.store'), $data);
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $this->actingAs($this->user);

        $data = ['title' => 'Updated Task'];

        $response = $this->put(route('tasks.update', $this->task->id), $data);
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['id' => $this->task->id, 'title' => 'Updated Task']);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $this->actingAs($this->user);

        $response = $this->delete(route('tasks.destroy', $this->task->id));
        $response->assertRedirect(route('tasks.index'));
        $this->assertSoftDeleted('tasks', ['id' => $this->task->id]);
    }

    /** @test */
    public function it_can_list_deleted_tasks()
    {
        $this->actingAs($this->user);
        $this->task->delete();

        $response = $this->get(route('tasks.deleted'));
        $response->assertStatus(200);
        $response->assertViewHas('tasks');
    }

    /** @test */
    public function it_can_restore_a_deleted_task()
    {
        $this->actingAs($this->user);
        $this->task->delete();

        $response = $this->patch(route('tasks.restore', $this->task->id));
        $response->assertRedirect(route('tasks.deleted'));
        $this->assertDatabaseHas('tasks', ['id' => $this->task->id, 'deleted_at' => null]);
    }
}
