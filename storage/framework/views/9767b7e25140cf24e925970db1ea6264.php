<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Shortlink</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/favicon.png')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
      * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f5f6fa;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            width: calc(100vw - 250px);
            margin-left: 250px;
            background: none;
            box-shadow: none;
            padding: 32px 32px 0 32px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 26px;
            font-weight: bold;
            color: #000;
        }

        .notification-icon {
            background-color: #fff;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .notification-icon i {
            color: #333;
            font-size: 16px;
        }


        form input, form button {
            font-size: 14px;
        }
        input[type="text"], input[type="url"] {
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            margin-top: 5px;
        }
        label {
            font-weight: bold;
            font-size: 14px;
        }
        button {
            padding: 12px;
            background-color: hsl(25, 100%, 63%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        .success-message {
            display: flex;
            align-items: center;
            gap: 16px;
            color: #fff;
            background: linear-gradient(90deg, #FF9040 0%, #2ecc40 100%);
            border-radius: 12px;
            padding: 18px 24px;
            margin-bottom: 26px;
            font-weight: 700;
            font-size: 17px;
            box-shadow: 0 4px 18px rgba(255,144,64,0.10);
            border: 2px solid #FF9040;
            position: relative;
            animation: fadeInNotif 0.7s;
        }
        @keyframes fadeInNotif {
            from { opacity: 0; transform: translateY(-16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .success-message .notif-icon {
            background: #fff;
            color: #2ecc40;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 2px 8px rgba(46,204,64,0.10);
            flex-shrink: 0;
            animation: popIn 0.5s;
        }
        @keyframes popIn {
            0% { transform: scale(0.5); opacity: 0; }
            80% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(1); }
        }
        .error-message {
            color: red;
            font-size: 12px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        @media (max-width: 900px) {
            .main-content { width: 100vw; margin-left: 0; padding: 16px 8px; }
            .modern-card { padding: 18px 6vw; }
        }
        .shortlink-result {
            display: flex;
            align-items: center;
            max-width: 340px;
            margin-top: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            background: #fff;
            border-radius: 8px;
        }
        .shortlink-result input[type="text"] {
            flex: 1;
            padding: 12px 14px;
            border: none;
            border-radius: 8px 0 0 8px;
            font-size: 14px;
            background: transparent;
            outline: none;
            height: 44px;
        }
        .shortlink-result button {
            padding: 0 18px;
            background: hsl(25, 100%, 63%);
            color: white;
            border: none;
            border-radius: 0 8px 8px 0;
            font-size: 14px;
            font-weight: 600;
            height: 44px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: background 0.2s;
        }
        .shortlink-result button:hover {
            background: hsl(25, 100%, 53%);
        }
        /* INTERAKTIF & MODERN SHORTLINK HISTORY */
        .shortlink-history-container {
            margin-top: 40px;
        }
        .shortlink-history-title {
            font-size: 20px;
            margin-bottom: 14px;
            color: #FF9040;
            font-weight: bold;
        }
        .shortlink-history-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(24,24,24,0.08);
        }
        .shortlink-history-table th {
            background: #181818;
            color: #FF9040;
            padding: 12px 8px;
            border-bottom: 2px solid #FF9040;
            font-size: 15px;
            font-weight: 700;
        }
        .shortlink-history-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #FFF3E6;
            color: #181818;
            font-size: 14px;
            vertical-align: middle;
            background: #fff;
            transition: background 0.2s;
        }
        .shortlink-history-table tr:hover td {
            background: #FFF3E6;
        }
        .shortlink-history-table td:first-child,
        .shortlink-history-table th:first-child {
            text-align: center;
        }
        .shortlink-badge {
            background: #FF9040;
            color: #fff;
            border-radius: 6px;
            padding: 4px 12px;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.5px;
            display: inline-block;
            transition: background 0.2s;
        }
        .shortlink-badge:hover {
            background: #181818;
            color: #FF9040;
        }
        .copy-btn-row {
            background: #FF9040;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            margin-left: 6px;
            position: relative;
        }
        .copy-btn-row:hover, .copy-btn-row.copied {
            background: #181818;
            color: #FF9040;
        }
        .copy-btn-row .tooltip {
            visibility: hidden;
            opacity: 0;
            background: #181818;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            padding: 3px 8px;
            position: absolute;
            z-index: 1;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            transition: opacity 0.2s;
            pointer-events: none;
        }
        .copy-btn-row:hover .tooltip, .copy-btn-row.copied .tooltip {
            visibility: visible;
            opacity: 1;
        }
        .shortlink-history-table tr.copied-row td {
            background: #FF9040 !important;
            color: #fff !important;
            transition: background 0.3s, color 0.3s;
        }
        @media (max-width: 700px) {
            .shortlink-history-table, .shortlink-history-table thead, .shortlink-history-table tbody, .shortlink-history-table tr, .shortlink-history-table th, .shortlink-history-table td {
                display: block;
                width: 100%;
            }
            .shortlink-history-table tr { margin-bottom: 18px; }
            .shortlink-history-table th { text-align: left; }
            .shortlink-history-table td { border: none; }
        }
        /* MODERN CARD LAYOUT */
        .modern-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(24,24,24,0.10);
            padding: 32px 28px 28px 28px;
            margin-bottom: 32px;
            margin-top: 0;
        }
        .modern-card h2 {
            font-size: 22px;
            color: #181818;
            margin-bottom: 18px;
            font-weight: 700;
        }
        .modern-form label {
            font-weight: 600;
            color: #181818;
            margin-bottom: 6px;
            display: block;
        }
        .modern-form input[type="text"], .modern-form input[type="url"] {
            width: 100%;
            padding: 13px 14px;
            border-radius: 8px;
            border: 1.5px solid #e0e0e0;
            background: #f9f9f9;
            margin-bottom: 18px;
            font-size: 15px;
            transition: border 0.2s;
        }
        .modern-form input[type="text"]:focus, .modern-form input[type="url"]:focus {
            border: 1.5px solid #FF9040;
            outline: none;
        }
        .modern-form button[type="submit"] {
            width: 100%;
            padding: 13px 0;
            background: #FF9040;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            margin-top: 8px;
            box-shadow: 0 2px 8px rgba(255,144,64,0.08);
            transition: background 0.2s;
        }
        .modern-form button[type="submit"]:hover {
            background: #181818;
            color: #FF9040;
        }
        /* Shortlink Result Modern */
        .shortlink-result {
            display: flex;
            align-items: center;
            max-width: 380px;
            margin-top: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            background: #fff;
            border-radius: 8px;
            border: 1.5px solid #FF9040;
        }
        .shortlink-result input[type="text"] {
            flex: 1;
            padding: 12px 14px;
            border: none;
            border-radius: 8px 0 0 8px;
            font-size: 15px;
            background: transparent;
            outline: none;
            height: 44px;
        }
        .shortlink-result button {
            padding: 0 18px;
            background: #FF9040;
            color: white;
            border: none;
            border-radius: 0 8px 8px 0;
            font-size: 15px;
            font-weight: 700;
            height: 44px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: background 0.2s;
        }
        .shortlink-result button:hover {
            background: #181818;
            color: #FF9040;
        }
        /* History Table Modern */
        .shortlink-history-container {
            margin-top: 0;
        }
        .shortlink-history-title {
            font-size: 20px;
            margin-bottom: 14px;
            color: #181818;
            font-weight: bold;
        }
        .shortlink-history-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(24,24,24,0.08);
        }
        .shortlink-history-table th {
            background: #181818;
            color: #FF9040;
            padding: 13px 8px;
            border-bottom: 2px solid #FF9040;
            font-size: 15px;
            font-weight: 700;
        }
        .shortlink-history-table td {
            padding: 13px 8px;
            border-bottom: 1px solid #FFF3E6;
            color: #181818;
            font-size: 15px;
            vertical-align: middle;
            background: #fff;
            transition: background 0.2s;
        }
        .shortlink-history-table tr:hover td {
            background: #FFF3E6;
        }
        .shortlink-history-table td:first-child,
        .shortlink-history-table th:first-child {
            text-align: center;
        }
        .shortlink-badge {
            background: #FF9040;
            color: #fff;
            border-radius: 6px;
            padding: 5px 14px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.5px;
            display: inline-block;
            transition: background 0.2s;
            text-decoration: none;
        }
        .shortlink-badge:hover {
            background: #181818;
            color: #FF9040;
        }
        .copy-btn-row {
            background: #FF9040;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 7px 18px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            margin-left: 6px;
            position: relative;
            box-shadow: 0 2px 8px rgba(255,144,64,0.08);
        }
        .copy-btn-row:hover, .copy-btn-row.copied {
            background: #181818;
            color: #FF9040;
        }
        .copy-btn-row .tooltip {
            visibility: hidden;
            opacity: 0;
            background: #181818;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            padding: 3px 8px;
            position: absolute;
            z-index: 1;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            transition: opacity 0.2s;
            pointer-events: none;
        }
        .copy-btn-row:hover .tooltip, .copy-btn-row.copied .tooltip {
            visibility: visible;
            opacity: 1;
        }
        .shortlink-history-table tr.copied-row td {
            background: #FF9040 !important;
            color: #fff !important;
            transition: background 0.3s, color 0.3s;
        }
        .shortlink-history-table td, .shortlink-history-table th {
            text-align: center;
            vertical-align: middle;
        }
        .shortlink-history-table td.destination-col {
            text-align: left;
            max-width: 320px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            word-break: break-all;
            position: relative;
        }
        /* Tambahan agar pagination responsif */
        .pagination {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: center;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .pagination li {
            display: inline-block;
        }
        .pagination li a, .pagination li span {
            display: block;
            padding: 8px 14px;
            border-radius: 6px;
            background: #fff;
            color: #FF9040;
            font-weight: 700;
            border: 1.5px solid #FF9040;
            margin: 0 2px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            font-size: 15px;
        }
        .pagination li.active span, .pagination li a:hover {
            background: #FF9040;
            color: #fff;
            border-color: #FF9040;
        }
        @media (max-width: 700px) {
            .pagination li a, .pagination li span {
                padding: 8px 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
     <div class="container">
        <?php echo $__env->make('homeadminS.sidebar.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="main-content">
            <div class="header" style="background:#fff; border-radius:14px; box-shadow:0 2px 8px rgba(24,24,24,0.06); padding:18px 24px 14px 24px; margin-bottom:32px; display:flex; align-items:center; justify-content:space-between;">
                <h1 style="font-size:26px; font-weight:800; color:#181818; letter-spacing:0.5px;">Shortlink Generator</h1>
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                </div>
            </div>

            <div class="modern-card">
                <h2>Buat Shortlink Baru</h2>
                <?php if(session('success')): ?>
                <?php
                    $shortUrl = explode(': ', session('success'))[1] ?? '';
                ?>
                <div class="success-message">
                    <span class="notif-icon"><i class="fas fa-check"></i></span>
                    <span>
                        Shortlink berhasil dibuat:<br>
                        <div class="shortlink-result" style="margin-top:10px;">
                            <input id="shortlinkInput" type="text" value="<?php echo e($shortUrl); ?>" readonly>
                            <button type="button" onclick="copyToClipboard()">Copy URL</button>
                        </div>
                        <div style="margin-top: 7px; font-size:14px; font-weight:400; color:#fff;">
                            Long URL: <strong><?php echo e(old('destination')); ?></strong>
                        </div>
                    </span>
                </div>
                <?php endif; ?>
                <form action="<?php echo e(url('/shorten')); ?>" method="POST" class="modern-form">
                    <?php echo csrf_field(); ?>
                    <label for="slug">Nama Shortlink</label>
                    <input type="text" name="slug" id="slug" required placeholder="Contoh: fajar">
                    <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <label for="destination">Tujuan URL</label>
                    <input type="url" name="destination" id="destination" required placeholder="Contoh: https://youtube.com/...">
                    <?php $__errorArgs = ['destination'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <button type="submit">🔗 Buat Shortlink</button>
                </form>
            </div>

            <?php if(isset($shortlinks) && $shortlinks->count()): ?>
            <div class="modern-card shortlink-history-container">
                <div class="shortlink-history-title">History Shortlink</div>
                <div class="table-responsive" style="overflow-x:auto;">
                <table class="shortlink-history-table" id="shortlinkHistoryTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Destination</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $shortlinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $shortlink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($shortlinks->firstItem() + $i); ?></td>
                            <td class="destination-col" data-full="<?php echo e($shortlink->destination); ?>"><?php echo e($shortlink->destination); ?></td>
                            <td><?php echo e($shortlink->created_at ? $shortlink->created_at->format('d-m-Y H:i') : '-'); ?></td>
                            <td>
                                <a href="/<?php echo e($shortlink->slug); ?>" target="_blank" class="shortlink-badge" title="Buka shortlink">
                                    <i class="fas fa-link" style="margin-right:4px;"></i><?php echo e($shortlink->slug); ?>

                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                </div>
                <div class="pagination-wrapper" style="margin-top: 18px; display: flex; justify-content: center;">
                    <?php if($shortlinks->lastPage() > 1): ?>
                        <div class="pagination-links" style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                            
                            <?php if($shortlinks->onFirstPage()): ?>
                                <span class="pagination-disabled" style="padding: 8px 14px; background: #f5f5f5; color: #999; border-radius: 6px; cursor: not-allowed; font-weight:700;">Previous</span>
                            <?php else: ?>
                                <a href="<?php echo e($shortlinks->previousPageUrl()); ?>" class="pagination-link" style="padding: 8px 14px; background: #FF9040; color: #fff; border-radius: 6px; text-decoration: none; font-weight:700;">Previous</a>
                            <?php endif; ?>

                            
                            <?php for($i = 1; $i <= $shortlinks->lastPage(); $i++): ?>
                                <?php if($i == $shortlinks->currentPage()): ?>
                                    <span class="pagination-current" style="padding: 8px 14px; background: #181818; color: #fff; border-radius: 6px; font-weight:700;"><?php echo e($i); ?></span>
                                <?php else: ?>
                                    <a href="<?php echo e($shortlinks->url($i)); ?>" class="pagination-link" style="padding: 8px 14px; background: #fff; color: #FF9040; border-radius: 6px; text-decoration: none; font-weight:700; border:1.5px solid #FF9040;"><?php echo e($i); ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            
                            <?php if($shortlinks->hasMorePages()): ?>
                                <a href="<?php echo e($shortlinks->nextPageUrl()); ?>" class="pagination-link" style="padding: 8px 14px; background: #FF9040; color: #fff; border-radius: 6px; text-decoration: none; font-weight:700;">Next</a>
                            <?php else: ?>
                                <span class="pagination-disabled" style="padding: 8px 14px; background: #f5f5f5; color: #999; border-radius: 6px; cursor: not-allowed; font-weight:700;">Next</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function copyToClipboard() {
            const input = document.getElementById("shortlinkInput");
            input.select();
            input.setSelectionRange(0, 99999); // For mobile
            document.execCommand("copy");
    
            alert("Shortlink berhasil disalin ke clipboard!");
        }
    </script>
    
</body>
</html>
<?php /**PATH C:\LINKAN_ID\resources\views/shortlink/create.blade.php ENDPATH**/ ?>