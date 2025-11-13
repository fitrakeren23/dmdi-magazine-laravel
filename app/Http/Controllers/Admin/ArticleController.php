<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\AutoTranslator;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('category')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request, AutoTranslator $translator)
    {
        $validated = $request->validate([
            'title_id'     => 'required|string|max:255',
            'title_en'     => 'nullable|string|max:255',
            'excerpt_id'   => 'required|string|max:500',
            'excerpt_en'   => 'nullable|string|max:500',
            'content_id'   => 'required|string',
            'content_en'   => 'nullable|string',
            'category_id'  => 'required|exists:categories,id',
            'author'       => 'required|string|max:255',
            'is_published' => 'boolean',
            'is_featured'  => 'boolean',
        ]);

        // Generate slug dari judul ID (unik)
        $slug = Str::slug($validated['title_id']);
        $originalSlug = $slug;
        $counter = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        // Auto-translate EN jika kosong
        if (empty($validated['title_en'])) {
            $validated['title_en'] = $translator->translate($validated['title_id'] ?? '');
        }
        if (empty($validated['excerpt_en'])) {
            $validated['excerpt_en'] = $translator->translate($validated['excerpt_id'] ?? '');
        }
        if (empty($validated['content_en'])) {
            $validated['content_en'] = $translator->translateHtml($validated['content_id'] ?? '');
        }

        // Upload featured image (original, medium, thumb)
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $paths = $this->saveImageSizes($image);
            // simpan path medium sebagai featured_image
            $validated['featured_image'] = $paths['medium'];
        }

        Article::create($validated);

        return redirect()->route('articles.index')
                        ->with('success', 'Artikel berhasil dibuat (EN otomatis).');
    }

    public function show(Article $article)
    {
        return view('admin.articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article, AutoTranslator $translator)
    {
        $validated = $request->validate([
            'title_id'     => 'required|string|max:255',
            'title_en'     => 'nullable|string|max:255',
            'excerpt_id'   => 'required|string|max:500',
            'excerpt_en'   => 'nullable|string|max:500',
            'content_id'   => 'required|string',
            'content_en'   => 'nullable|string',
            'category_id'  => 'required|exists:categories,id',
            'author'       => 'required|string|max:255',
            'is_published' => 'boolean',
            'is_featured'  => 'boolean',
        ]);

        // Jika admin tidak mengisi EN (atau ingin selaras penuh), isi otomatis
        if (empty($validated['title_en']) && !empty($validated['title_id'])) {
            $validated['title_en'] = $translator->translate($validated['title_id']);
        }
        if (empty($validated['excerpt_en']) && !empty($validated['excerpt_id'])) {
            $validated['excerpt_en'] = $translator->translate($validated['excerpt_id']);
        }
        if (empty($validated['content_en']) && !empty($validated['content_id'])) {
            $validated['content_en'] = $translator->translateHtml($validated['content_id']);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) {
                $this->deleteImageSizesByMediumPath($article->featured_image);
            }

            $image = $request->file('featured_image');
            $paths = $this->saveImageSizes($image);
            $validated['featured_image'] = $paths['medium'];
        }

        $article->update($validated);

        return redirect()->route('articles.index')
                        ->with('success', 'Artikel berhasil diperbarui (EN otomatis).');
    }

    public function destroy(Article $article)
    {
        if ($article->featured_image) {
            $this->deleteImageSizesByMediumPath($article->featured_image);
        }

        if ($article->qr_code_path) {
            Storage::disk('public')->delete($article->qr_code_path);
        }

        $article->delete();

        return redirect()->route('articles.index')
                        ->with('success', 'Artikel berhasil dihapus!');
    }

    public function generateQR($id)
    {
        $article = Article::findOrFail($id);

        $url_id = url('/id/article/' . $article->slug);
        $url_en = url('/en/article/' . $article->slug);

        $qrCode = QrCode::format('png')
                        ->size(300)
                        ->errorCorrection('H')
                        ->generate($url_en);

        $filename = 'qr-codes/article-' . $article->id . '-en.png';

        Storage::disk('public')->put($filename, $qrCode);

        $article->update(['qr_code_path' => $filename]);

        return response()->json([
            'success' => true,
            'message' => 'QR Code berhasil digenerate!',
            'qr_url' => asset('storage/' . $filename)
        ]);
    }

    /**
     * Save original + medium + thumbnail versions of given UploadedFile.
     * Returns array: ['original'=>path, 'medium'=>path, 'thumb'=>path]
     */
    private function saveImageSizes($uploadedFile)
    {
        $ext = $uploadedFile->getClientOriginalExtension();
        $basename = 'article-' . time() . '-' . Str::random(6);

        $baseDir = 'uploads/articles';
        $originalPath = $baseDir . '/original/' . $basename . '.' . $ext;
        $mediumPath   = $baseDir . '/medium/' . $basename . '.' . $ext;
        $thumbPath    = $baseDir . '/thumb/' . $basename . '.' . $ext;

        Storage::disk('public')->putFileAs($baseDir . '/original', $uploadedFile, $basename . '.' . $ext);

        $img = Image::make($uploadedFile)->orientate();
        $img->resize(980, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        Storage::disk('public')->put($mediumPath, (string) $img->encode($ext, 85));

        $thumb = Image::make($uploadedFile)->orientate();
        $thumb->fit(300, 200, function ($constraint) {
            $constraint->upsize();
        });
        Storage::disk('public')->put($thumbPath, (string) $thumb->encode($ext, 85));

        return [
            'original' => $originalPath,
            'medium' => $mediumPath,
            'thumb' => $thumbPath,
        ];
    }

    /**
     * Given a medium path (stored in featured_image), delete medium/thumb/original siblings.
     */
    private function deleteImageSizesByMediumPath($mediumPath)
    {
        if (! $mediumPath) return;

        $basename = basename($mediumPath);

        $paths = [
            'uploads/articles/medium/' . $basename,
            'uploads/articles/thumb/' . $basename,
            'uploads/articles/original/' . $basename,
        ];

        foreach ($paths as $p) {
            if (Storage::disk('public')->exists($p)) {
                Storage::disk('public')->delete($p);
            }
        }
    }
}