<?php

namespace App\Http\Controllers;

use App\Http\Resources\TasksResources;
use App\Models\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $task = Task::all();
        return TasksResources::collection($task);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $task = new Task();
        $task->name = $request->input('name');
        $task->status = $request->input('status');
        $task->save();
        return TasksResources::collection(Task::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            return [
                'code' => 404,
                'message' => 'Задача не найдено!'
            ];
        } else {
            return $task;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::find($id);
        if (!empty($task)) {
            $task->name = $request->input('name');
            $task->status = $request->input('status');
            $task->update();
            return [
                'code' => 200,
                'message' => 'Запись обновлено успешно!',
                'data' => $task
            ];
        } else {
            return [
                'code' => 404,
                'message' => 'Задача не найдено!'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if (!empty($task)) {
            $task->delete();
            return [
                'code' => 200,
                'message' => 'Запись удалено успешно!',
            ];
        } else {
            return [
                'code' => 404,
                'message' => 'Задача не найдено!'
            ];
        }
    }

    public function sortByDate($startDate, $endDate)
    {
        $task = Task::whereBetween('created_at', ["$startDate", "$endDate"]);
        if (count($task->get()) > 0) {
            return [
                'code' => 200,
                'data' => $task->get()
            ];
        } else {
            return [
                'code' => 404,
                'message' => 'Задача не найдено!'
            ];
        }
    }

    public function sortByStatus($status)
    {
        $task = Task::where('status', '=', $status);
        if (count($task->get()) > 0) {
            return [
                'code' => 200,
                'data' => $task->get()
            ];
        } else {
            return [
                'code' => 404,
                'message' => 'Задача не найдено!'
            ];
        }
    }
}
