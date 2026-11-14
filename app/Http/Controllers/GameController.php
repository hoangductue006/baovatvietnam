<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    private function readJson(string $file): array
    {
        $path = storage_path('app/data/' . $file);
        if (!is_file($path)) return [];
        $data = json_decode(file_get_contents($path), true);
        return is_array($data) ? $data : [];
    }

    public function home()
    {
        $treasures = $this->readJson('treasures.json');
        return view('pages.home', ['count' => count($treasures), 'featured' => array_slice($treasures, 0, 3)]);
    }

    public function treasures(Request $request)
    {
        $all = $this->readJson('treasures.json');
        $keyword = trim((string) $request->query('q', ''));
        $period = trim((string) $request->query('period', ''));
        $material = trim((string) $request->query('material', ''));

        $items = array_values(array_filter($all, function ($item) use ($keyword, $period, $material) {
            $haystack = implode(' ', [
                $item['title'] ?? '', $item['date'] ?? '', $item['format'] ?? '',
                $item['description'] ?? '', $item['holding_current'] ?? ''
            ]);
            $matchesKeyword = $keyword === '' || mb_stripos($haystack, $keyword) !== false;
            $matchesPeriod = $period === '' || mb_stripos($item['date'] ?? '', $period) !== false;
            $matchesMaterial = $material === '' || mb_stripos($item['format'] ?? '', $material) !== false;
            return $matchesKeyword && $matchesPeriod && $matchesMaterial;
        }));

        $total = count($items);
        $perPage = 9;
        $page = max(1, (int) $request->query('page', 1));
        $pageItems = array_slice($items, ($page - 1) * $perPage, $perPage);
        $items = new \Illuminate\Pagination\LengthAwarePaginator(
            $pageItems, $total, $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        return view('pages.treasures', [
            'items' => $items, 'keyword' => $keyword, 'period' => $period,
            'material' => $material, 'total' => count($all)
        ]);
    }

    public function treasureDetail(string $id)
    {
        $items = $this->readJson('treasures.json');
        $item = collect($items)->first(fn ($row) => ($row['id'] ?? '') === $id || ($row['identifier'] ?? '') === $id);
        abort_if(!$item, 404);
        $related = collect($items)->filter(fn ($row) =>
            ($row['id'] ?? '') !== ($item['id'] ?? '') &&
            (mb_stripos($row['date'] ?? '', 'Đông Sơn') !== false) === (mb_stripos($item['date'] ?? '', 'Đông Sơn') !== false)
        )->take(3)->values();
        return view('pages.treasure-detail', ['item' => $item, 'related' => $related]);
    }

    public function learning() { return view('pages.learning', ['items' => $this->readJson('treasures.json')]); }
    public function miniGames() { return view('pages.minigames', ['items' => $this->readJson('treasures.json')]); }
    public function quiz() { return view('pages.quiz', ['items' => $this->readJson('treasures.json')]); }
    public function flashcards() { return view('pages.flashcards', ['items' => $this->readJson('treasures.json')]); }
    public function scores() { return view('pages.scores'); }
    public function sources() { return view('pages.sources', ['sources' => $this->readJson('sources.json')]); }
    public function about() { return view('pages.about'); }

    public function exportJson()
    {
        return response()->download(storage_path('app/data/treasures.json'), 'bao-vat-viet-nam.json', [
            'Content-Type' => 'application/json; charset=utf-8'
        ]);
    }

    public function exportCsv()
    {
        $items = $this->readJson('treasures.json');
        $callback = function () use ($items) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, ['Mã định danh','Nhan đề','Niên đại','Chất liệu','Nơi lưu giữ','Số đăng ký','Mô tả','Nguồn','Loại bản hiển thị','Tình trạng bản gốc','Người cho phép công bố']);
            foreach ($items as $i) fputcsv($out, [
                $i['identifier'], $i['title'], $i['date'], $i['format'], $i['holding_current'],
                $i['registration_number'], $i['description'], $i['source'], $i['display_copy_type'],
                $i['original_condition'], $i['permission_grantor']
            ]);
            fclose($out);
        };
        return response()->streamDownload($callback, 'bao-vat-viet-nam.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function explain(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required','string','max:30'],
            'question' => ['required','string','min:3','max:500']
        ]);
        $item = collect($this->readJson('treasures.json'))->firstWhere('id', $validated['id']);
        if (!$item) return response()->json(['message' => 'Không tìm thấy bản ghi.'], 404);

        $apiKey = config('services.gemini.key');
        if (!$apiKey) return response()->json(['message' => 'Chưa cấu hình GEMINI_API_KEY trong tệp .env.'], 422);

        $prompt = "Bạn là trợ lý giáo dục bảo tàng dành cho học sinh Việt Nam. Chỉ dùng dữ liệu bên dưới để trả lời. Nếu dữ liệu không có câu trả lời, hãy nói rõ chưa đủ thông tin và khuyên mở nguồn tham khảo. Không tự bịa niên đại, chất liệu, số đăng ký hoặc quyền sở hữu. Trả lời ngắn gọn, dễ hiểu.\n\nDỮ LIỆU: " .
            json_encode([
                'ma' => $item['id'], 'ten' => $item['title'], 'mo_ta' => $item['description'],
                'nien_dai' => $item['date'], 'chat_lieu' => $item['format'],
                'noi_luu_giu' => $item['holding_current'], 'nguon' => $item['source']
            ], JSON_UNESCAPED_UNICODE) .
            "\n\nCÂU HỎI HỌC SINH: " . $validated['question'];

        $model = config('services.gemini.model', 'gemini-2.5-flash');
        $response = Http::timeout(25)
            ->withHeaders(['x-goog-api-key' => $apiKey])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => ['temperature' => 0.2, 'maxOutputTokens' => 500]
            ]);

        if (!$response->successful()) {
            return response()->json(['message' => 'Gemini chưa phản hồi thành công. Kiểm tra API key, model và hạn mức.'], 502);
        }

        $answer = data_get($response->json(), 'candidates.0.content.parts.0.text');
        return response()->json(['answer' => $answer ?: 'Chưa nhận được câu trả lời.', 'source' => $item['source']]);
    }
}
