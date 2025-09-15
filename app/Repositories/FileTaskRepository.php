<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Storage;

class FileTaskRepository implements TaskRepositoryInterface
{
    private string $path = 'private/tasks.json';

    private function load(): array {
        if (!Storage::exists($this->path)) {
            Storage::put($this->path, json_encode([]));
        }
        $raw = Storage::get($this->path);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function save(array $tasks): void {
        Storage::put($this->path, json_encode($tasks, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }

    public function all(): array { return $this->load(); }

    public function find(int $id): ?array {
        foreach ($this->load() as $t) if (($t['id']??null)===$id) return $t;
        return null;
    }

    public function create(array $data): array {
        $tasks = $this->load();
        $ids   = array_map(fn($t)=>$t['id']??0, $tasks);
        $next  = empty($ids) ? 1 : max($ids)+1;

        $task = [
            'id'        => $next,
            'title'     => $data['title'] ?? 'Sin título',
            'completed' => (bool)($data['completed'] ?? false),
        ];
        $tasks[] = $task;
        $this->save($tasks);
        return $task;
    }

    public function update(int $id, array $data): ?array {
        $tasks = $this->load();
        foreach ($tasks as $i=>$t) {
            if (($t['id']??null)===$id) {
                $tasks[$i]['title'] = $data['title'] ?? $t['title'];
                if (array_key_exists('completed',$data)) {
                    $tasks[$i]['completed'] = (bool)$data['completed'];
                }
                $this->save($tasks);
                return $tasks[$i];
            }
        }
        return null;
    }

    public function delete(int $id): bool {
        $tasks = $this->load();
        $new   = array_values(array_filter($tasks, fn($t)=>($t['id']??null)!==$id));
        if (count($new)===count($tasks)) return false;
        $this->save($new);
        return true;
    }
}
