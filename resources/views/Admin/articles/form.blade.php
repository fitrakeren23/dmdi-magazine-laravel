@csrf

@if(isset($article) && $article->id)
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-8">
        <!-- Indonesian Content -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-flag me-1"></i>
                    Konten Bahasa Indonesia
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="title_id" class="form-label">Judul Artikel (Indonesia) *</label>
                    <input type="text" 
                           class="form-control" 
                           id="title_id" 
                           name="title_id" 
                           value="{{ old('title_id', $article->title_id ?? '') }}"
                           required>
                </div>
                
                <div class="mb-3">
                    <label for="excerpt_id" class="form-label">Ringkasan (Indonesia) *</label>
                    <textarea class="form-control" 
                              id="excerpt_id" 
                              name="excerpt_id" 
                              rows="3" 
                              maxlength="500"
                              required>{{ old('excerpt_id', $article->excerpt_id ?? '') }}</textarea>
                    <div class="form-text">Maksimal 500 karakter</div>
                </div>
                
                <div class="mb-3">
                    <label for="content_id" class="form-label">Konten (Indonesia) *</label>
                    <textarea class="form-control" 
                              id="content_id" 
                              name="content_id" 
                              rows="10"
                              required>{{ old('content_id', $article->content_id ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- English Content -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-flag-fill me-1"></i>
                    English Content
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="title_en" class="form-label">Article Title (English) *</label>
                    <input type="text" 
                           class="form-control" 
                           id="title_en" 
                           name="title_en" value="{{ old('title_en', $article->title_en ?? '') }}">
                           required>
                </div>
                
                <div class="mb-3">
                    <label for="excerpt_en" class="form-label">Excerpt (English) *</label>
                    <textarea class="form-control" 
                              id="excerpt_en" 
                              name="excerpt_en" 
                              rows="3" 
                              maxlength="500"
                              required>{{ old('excerpt_en', $article->excerpt_en ?? '') }}</textarea>
                    <div class="form-text">Maximum 500 characters</div>
                </div>
                
                <div class="mb-3">
                    <label for="content_en" class="form-label">Content (English) *</label>
                    <textarea class="form-control" 
                              id="content_en" 
                              name="content_en" 
                              rows="10"
                              required>{{ old('content_en', $article->content_en ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Sidebar Settings -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-bold">Pengaturan</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori *</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ old('category_id', $article->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name_id }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="author" class="form-label">Penulis *</label>
                    <input type="text" 
                           class="form-control" 
                           id="author" 
                           name="author" 
                           value="{{ old('author', $article->author ?? '') }}"
                           required>
                </div>
                
                <div class="mb-3">
                    <label for="featured_image" class="form-label">Gambar Utama</label>
                    <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                    
                    @if(isset($article) && $article->featured_image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                 alt="Featured Image" 
                                 class="img-thumbnail"
                                 style="max-height: 150px;">
                        </div>
                    @endif
                </div>
                
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is_published" 
                               name="is_published" 
                               value="1"
                               {{ old('is_published', $article->is_published ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="is_published">
                            Publikasikan Artikel
                        </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1"
                               {{ old('is_featured', $article->is_featured ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="is_featured">
                            Jadikan Artikel Unggulan
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-2">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ isset($article) ? 'Perbarui Artikel' : 'Buat Artikel' }}
                </button>
                
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali
                </a>
                
                @if(isset($article) && $article->qr_code_path)
                    <div class="mt-3 text-center">
                        <p class="small text-muted mb-2">QR Code (English Version):</p>
                        <img src="{{ asset('storage/' . $article->qr_code_path) }}" 
                             alt="QR Code" 
                             class="img-fluid border rounded"
                             style="max-width: 150px;">
                        <div class="mt-1">
                            <a href="{{ asset('storage/' . $article->qr_code_path) }}" 
                               download="qr-{{ $article->slug }}.png"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download me-1"></i>
                                Download
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
<!-- QUILL.JS EDITOR -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>

<style>
.ql-editor {
  min-height: 350px;
  font-size: 14px;
  line-height: 1.6;
}
.ql-container {
  font-family: inherit;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Untuk Content Indonesia
  const contentIdElement = document.getElementById('content_id');
  const quill1 = new Quill('#content_id', {
    theme: 'snow',
    modules: {
      toolbar: [
        ['bold', 'italic', 'underline'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['link'],
        ['clean']
      ]
    },
    placeholder: 'Tulis konten artikel dalam Bahasa Indonesia...'
  });
  
  // Untuk Content English
  const contentEnElement = document.getElementById('content_en'); 
  const quill2 = new Quill('#content_en', {
    theme: 'snow',
    modules: {
      toolbar: [
        ['bold', 'italic', 'underline'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['link'],
        ['clean']
      ]
    },
    placeholder: 'Write article content in English...'
  });

  // Set initial content
  quill1.root.innerHTML = contentIdElement.value;
  quill2.root.innerHTML = contentEnElement.value;

  // Update hidden fields before form submit
  const form = document.querySelector('form');
  form.addEventListener('submit', function(e) {
    contentIdElement.value = quill1.root.innerHTML;
    contentEnElement.value = quill2.root.innerHTML;
  });
});
</script>
</div>