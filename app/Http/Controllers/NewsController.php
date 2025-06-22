<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    /**
     * Отображение списка новостей
     */
    public function index(Request $request)
    {
        $sortOrder = $request->input('sort', 'new');
        
        $query = News::published();
        
        if ($sortOrder === 'old') {
            $news = $query->orderBy('published_at', 'asc')->paginate(9);
        } else {
            $news = $query->newest()->paginate(9);
        }
        
        return view('news.index', compact('news', 'sortOrder'));
    }

    /**
     * Отображение детальной страницы новости
     */
    public function show($id)
    {
        $news = News::findOrFail($id);
        
        // Увеличиваем счетчик просмотров
        $news->incrementViews();
        
        return view('news.show', compact('news'));
    }
    
    /**
     * Отображение списка новостей в админ-панели
     */
    public function adminIndex()
    {
        $news = News::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.news.index', compact('news'));
    }
    
    /**
     * Отображение формы создания новости
     */
    public function create()
    {
        return view('admin.news.create');
    }
    
    /**
     * Отображение формы редактирования новости
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }
    
    /**
     * Создание новой новости (для админ-панели)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'short_description' => 'required|string',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                // 'is_published' => 'nullable|boolean', // убрано для корректной работы чекбокса
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd('Ошибка валидации', $e->errors(), $request->all());
        }
        
        $news = new News();
        $news->title = $validated['title'];
        $news->short_description = $validated['short_description'];
        $news->content = $validated['content'];
        $news->is_published = $request->has('is_published');
        $news->published_at = $request->has('is_published') ? now() : null;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $targetDirectory = resource_path('media/images/');
            if (!File::exists($targetDirectory)) {
                File::makeDirectory($targetDirectory, 0755, true);
            }
            $image->move($targetDirectory, $imageName);
            $news->image = $imageName;
        }
        
        $saved = $news->save();
        if (!$saved) {
            \Log::error('Ошибка при сохранении новости', ['news' => $news]);
            dd('Ошибка при сохранении новости', $news);
        }
        
        return redirect()->route('admin.news')
            ->with('success', 'Новость успешно создана!');
    }
    
    /**
     * Обновление новости (для админ-панели)
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'is_published' => 'nullable|boolean', // убрано для корректной работы чекбокса
        ]);
        
        $news->title = $validated['title'];
        $news->short_description = $validated['short_description'];
        $news->content = $validated['content'];
        $news->is_published = $request->has('is_published');
        $news->published_at = $request->has('is_published') ? now() : null;
        
        if ($request->hasFile('image')) {
            if ($news->image && File::exists(resource_path('media/images/' . $news->image))) {
                File::delete(resource_path('media/images/' . $news->image));
            }
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $targetDirectory = resource_path('media/images/');
            if (!File::exists($targetDirectory)) {
                File::makeDirectory($targetDirectory, 0755, true);
            }
            $image->move($targetDirectory, $imageName);
            $news->image = $imageName;
        }
        
        $saved = $news->save();
        if (!$saved) {
            \Log::error('Ошибка при сохранении новости', ['news' => $news]);
            dd('Ошибка при сохранении новости', $news);
        }
        
        return redirect()->route('admin.news')
            ->with('success', 'Новость успешно обновлена!');
    }
    
    /**
     * Удаление новости
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        
        if ($news->image && File::exists(resource_path('media/images/' . $news->image))) {
            File::delete(resource_path('media/images/' . $news->image));
        }
        
        $news->delete();
        
        return redirect()->route('admin.news')
            ->with('success', 'Новость успешно удалена!');
    }
}
