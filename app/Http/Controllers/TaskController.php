<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TaskRepositoryInterface;

class TaskController extends Controller
{
    public function __construct(private TaskRepositoryInterface $repo)
    {
    }

    // GET /api/tasks
    public function index()
    {
        return response()->json($this->repo->all());
    }

    // GET /api/tasks/{id}
    public function show(int $id)
    {
        $task = $this->repo->find($id);
        return $task
            ? response()->json($task)
            : response()->json(['message' => 'Not found'], 404);
    }

    // POST /api/tasks
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => 'required|string|min:1',
            'completed' => 'boolean',
        ]);
        $task = $this->repo->create($data);
        return response()->json($task, 201);
    }

    // PUT /api/tasks/{id}
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'title'     => 'sometimes|string|min:1',
            'completed' => 'sometimes|boolean',
        ]);
        $task = $this->repo->update($id, $data);
        return $task
            ? response()->json($task)
            : response()->json(['message' => 'Not found'], 404);
    }

    // DELETE /api/tasks/{id}
    public function destroy(int $id)
    {
        return $this->repo->delete($id)
            ? response()->json(['deleted' => true])
            : response()->json(['message' => 'Not found'], 404);
    }
}
