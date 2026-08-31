<div class="editor-mode-header-bar">
    <h2 class="editor-mode-title">
        <a href="{{ route('admin.mylinkan', ['mode' => 'gallery']) }}" class="back-link" title="Kembali ke daftar microsite">
            <i class="fas fa-arrow-left"></i>
        </a>
        <i class="fas fa-sliders-h text-brand-orange"></i> {{ __('admin.edit_content_blocks') }}
    </h2>

    {{-- TAB SWITCHER: Elemen | Pengaturan --}}
    <nav class="editor-panel-tab-switcher" role="tablist" aria-label="Panel editor">
        <button
            type="button"
            id="tab-btn-elemen"
            role="tab"
            class="editor-panel-tab-btn is-active"
            aria-selected="true"
            aria-controls="editorPanelElemen"
            onclick="switchEditorPanel('elemen')"
        >
            <i class="fas fa-layer-group"></i>
            <span>Elemen</span>
        </button>
        <button
            type="button"
            id="tab-btn-pengaturan"
            role="tab"
            class="editor-panel-tab-btn"
            aria-selected="false"
            aria-controls="editorPanelPengaturan"
            onclick="switchEditorPanel('pengaturan')"
        >
            <i class="fa-solid fa-palette"></i>
            <span>Pengaturan</span>
        </button>
    </nav>
</div>
