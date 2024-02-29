<?php

namespace App\Http\Controllers;

use App\Http\Resources\TasksResources;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'status' => 'required'
            ],
            [
                'name' => 'Поля Имя задачи не можеть быть пустим!',
                'status' => 'Поля Статус не можеть быть пустим!',
            ]
        );
        if ($validator->fails()) {
            return [
                'code' => 500,
                'message' => $validator->errors()
            ];
        } else {
            $task->create([
                'name' => $request->name,
                'status' => $request->status
            ]);
            return [
                'code' => 200,
                'message' => 'Запись успешно добавлено'
            ];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::where('id', '=', $id);
        if (count($task->get()) < 1) {
            return [
                'code' => 404,
                'message' => 'Задача не найдено!'
            ];
        } else {
            return TasksResources::collection($task->get());
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

    /**@return array{code: int, message: string}
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

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public function sortByDate($startDate, $endDate)
    {
        $task = Task::whereBetween('created_at', ["$startDate", "$endDate"]);
        if (count($task->get()) > 0) {
            return [
                'code' => 200,
                'data' => TasksResources::collection($task->get())
            ];
        } else {
            return [
                'code' => 404,
                'message' => 'Задача не найдено!'
            ];
        }
    }

    /**
     * @param $status
     * @return array
     */
    public function sortByStatus($status)
    {
        $task = Task::where('status', '=', $status);
        if (count($task->get()) > 0) {
            return [
                'code' => 200,
                'data' => TasksResources::collection($task->get())
            ];
        } else {
            return [
                'code' => 404,
                'message' => 'Задача не найдено!'
            ];
        }
    }

    public function lastId()
    {
        return $task = Task::latest()->first()->id;
    }
}
