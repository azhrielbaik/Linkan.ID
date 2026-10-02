@props([
    'title',
    'description' => null,
    'searchId',
    'searchPlaceholder' => 'Cari data...',
    'searchHandler' => '',
    'statusId',
    'statusHandler' => '',
    'statusOptions' => [],
    'sortId',
    'sortHandler' => '',
    'sortOptions' => [],
    'exportFilename' => 'export.csv',
    'tableId' => '',
])

<div class="sa-table-header-tools">
    <div>
        <h3 class="analytics-card-title">{{ $title }}</h3>
        @if($description)
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $description }}</p>
        @endif
    </div>
    <div class="sa-table-actions">
        <div class="sa-table-search">
            <i class="fas fa-search"></i>
            <input type="text" id="{{ $searchId }}" placeholder="{{ $searchPlaceholder }}" oninput="{{ $searchHandler }}">
        </div>
        @if(!empty($statusOptions))
            <select id="{{ $statusId }}" class="sa-table-select" onchange="{{ $statusHandler }}">
                @foreach($statusOptions as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>
        @endif
        @if(!empty($sortOptions))
            <select id="{{ $sortId }}" class="sa-table-select" onchange="{{ $sortHandler }}">
                @foreach($sortOptions as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>
        @endif
        <button type="button" class="sa-btn-export" onclick="AnalyticsPage.tables.exportToCSV('{{ $exportFilename }}', '{{ $tableId }}')">
            <i class="fas fa-file-csv"></i> Export CSV
        </button>
    </div>
</div>
