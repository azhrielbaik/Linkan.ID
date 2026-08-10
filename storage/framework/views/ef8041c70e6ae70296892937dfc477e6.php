<?php $__env->startSection("page_title", "Shortlink"); ?>

<?php $__env->startPush("styles"); ?>
<style>
    /* RESET & BASE */
    * {
        box-sizing: border-box;
    }
    
    /* Clean white flat canvas for shortlinks */
    body, .main-content, .content-wrapper, .dashboard-wrapper {
        background-color: #fff !important;
    }

    .dashboard-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #181818;
    }

    /* GRID LAYOUT */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1.8fr 1.2fr;
        gap: 30px;
        align-items: start;
        margin-top: 20px;
    }

    @media (max-width: 1024px) {
        .dashboard-grid {
            display: flex;
            flex-direction: column;
        }
        .left-col { order: 2; width: 100%; }
        .right-col { order: 1; width: 100%; }
        
        .mobile-form-collapse-body { display: none; }
        .mobile-form-collapse.is-open .mobile-form-collapse-body { display: block; }
        
        .mobile-form-collapse-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            background: #fff;
            color: #181818;
            border-radius: 8px;
            border: 1px solid #f5f5f5;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            font-weight: 800;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 15px;
        }
        .mobile-form-collapse.is-open .mobile-form-collapse-header {
            margin-bottom: 0;
            border-radius: 8px 8px 0 0;
            border-bottom: 1px solid #eee;
            background: #fbfbfc;
        }
        .mobile-form-collapse.is-open .mobile-form-collapse-header i.fa-chevron-down {
            transform: rotate(180deg);
        }
        .mobile-form-collapse-header i.fa-chevron-down {
            transition: transform 0.3s;
        }
    }

    @media (max-width: 600px) {
        .stats-row { grid-template-columns: 1fr !important; }
        .chart-container { 
            overflow-x: auto; 
            justify-content: flex-start !important; 
            gap: 16px; 
            padding-bottom: 12px; 
        }
        .chart-container::-webkit-scrollbar { height: 4px; }
        .chart-container::-webkit-scrollbar-thumb { background: #FF9040; border-radius: 4px; }
        .chart-bar-group { min-width: 32px; }
        
        .preview-meta-grid { grid-template-columns: 1fr !important; }
        
        .card { padding: 16px !important; }
        .stat-box { padding: 16px !important; }
        
        .card-header { 
            flex-direction: column; 
            align-items: flex-start !important; 
            gap: 8px; 
        }
        
        #panel-view-section > div:first-child { flex-direction: column; }
        
        .engagement-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        .eng-info {
            width: 100%;
            min-width: 0;
        }
        .eng-info p {
            max-width: 100% !important;
            white-space: normal !important;
            word-break: break-all;
        }
        .eng-actions {
            width: 100%;
            justify-content: flex-end;
        }
        
        .success-card {
            bottom: 20px;
            right: 20px;
            left: 20px;
            max-width: none;
            padding: 16px;
            border-radius: 12px;
            gap: 12px;
        }
        .success-card-header h3 {
            font-size: 14px !important;
        }
        .success-card-header .icon {
            width: 32px !important; 
            height: 32px !important; 
            font-size: 14px !important;
        }
        .shortlink-display {
            flex-direction: column;
            gap: 8px;
        }
        .shortlink-display input, .shortlink-display button {
            width: 100%;
            padding: 10px !important;
        }
        
        .input-row {
            flex-direction: column;
            border: none !important;
        }
        .input-row input {
            border: 1px solid #e0e0e0 !important;
            border-radius: 12px;
            margin-bottom: 12px;
        }
        .btn-submit {
            width: 100%;
            justify-content: center;
            border-radius: 12px;
            padding: 16px !important;
        }
        .slug-prefix {
            padding: 0 12px !important;
            font-size: 13px !important;
        }
    }
    @media (min-width: 1025px) {
        .mobile-form-collapse-header { display: none; }
        .mobile-form-collapse-body { display: block !important; }
    }

    /* CARDS */
    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: none !important;
        padding: 24px;
        margin-bottom: 30px;
        border: 1px solid #e2e8f0 !important;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #181818;
    }

    /* PERFORMANCE SECTION */
    .stats-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-box {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 50% !important;
        background: #FF9040;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-info span {
        display: block;
        font-size: 13px;
        color: #666;
        margin-bottom: 4px;
    }

    .stat-info strong {
        font-size: 24px;
        font-weight: 800;
        color: #181818;
    }

    /* MOCK CHART */
    .chart-container {
        height: 200px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        padding-top: 20px;
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
    }
    
    .chart-bar-group {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        width: 100%;
    }

    .chart-bar {
        width: 24px;
        background: #FF9040;
        border-radius: 4px 4px 0 0;
        transition: height 0.3s ease;
    }

    .chart-label {
        font-size: 12px;
        color: #999;
    }

    .hide-chart-btn {
        background: #FF9040;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: block;
        margin: 0 auto;
    }

    /* ENGAGEMENT LIST */
    .engagement-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .engagement-item {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: box-shadow 0.2s;
    }

    .engagement-item:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .eng-info h4 {
        margin: 0 0 6px 0;
        font-size: 16px;
    }

    .eng-info h4 a {
        color: #181818;
        text-decoration: none;
        font-weight: 700;
        transition: color 0.2s;
    }

    .eng-info h4 a:hover {
        color: #FF9040;
    }

    .eng-info p {
        margin: 0;
        font-size: 13px;
        color: #888;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 300px;
    }

    .eng-actions {
        display: flex;
        gap: 8px;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        background: #FFF3E6;
        color: #FF9040;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-icon:hover {
        background: #FF9040;
        color: #fff;
    }

    /* FORMS & INPUTS */
    .create-form-desc {
        font-size: 14px;
        color: #666;
        margin-bottom: 20px;
    }

    .input-row {
        display: flex;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
        transition: border-color 0.3s;
        margin-bottom: 16px;
    }

    .input-row:focus-within {
        border-color: #FF9040;
    }

    .input-row input {
        flex: 1;
        border: none;
        padding: 16px;
        font-size: 14px;
        outline: none;
    }

    .btn-submit {
        background: #FF9040;
        color: #fff;
        border: none;
        padding: 0 24px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s;
    }

    .btn-submit:hover {
        background: #e67d30;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #181818;
        margin-bottom: 8px;
    }

    .form-group input {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        border-color: #FF9040;
    }

    .slug-wrapper {
        display: flex;
        align-items: center;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .slug-wrapper:focus-within {
        border-color: #FF9040;
    }

    .slug-prefix {
        background: #f8f9fa;
        padding: 14px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #555;
        border-right: 1px solid #e0e0e0;
    }

    .slug-wrapper input {
        border: none;
        border-radius: 0;
        padding: 14px 16px;
        flex: 1;
    }

    /* LOTTIE SUCCESS MODAL */
    .success-card-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        animation: fadeInOverlay 0.3s ease forwards;
        padding: 20px;
    }
    
    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .success-card {
        background: #fff;
        border-radius: 28px;
        padding: 40px 30px 30px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        width: 100%;
        max-width: 440px;
        text-align: center;
        position: relative;
        animation: popInCard 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes popInCard {
        from { transform: scale(0.8) translateY(20px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }

    .success-card.hiding {
        animation: popOutCard 0.4s ease forwards;
    }
    .success-card-overlay.hiding {
        animation: fadeOutOverlay 0.4s ease forwards;
    }

    @keyframes popOutCard {
        from { transform: scale(1); opacity: 1; }
        to { transform: scale(0.9); opacity: 0; }
    }
    @keyframes fadeOutOverlay {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    .success-card-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #f5f5f5;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #666;
        cursor: pointer;
        transition: all 0.2s;
    }
    .success-card-close:hover {
        background: #e0e0e0;
        color: #111;
        transform: rotate(90deg);
    }

    .success-card h3 {
        margin: 0;
        color: #111;
        font-size: 24px;
        font-weight: 800;
    }
    
    .shortlink-display {
        display: flex;
        gap: 10px;
        width: 100%;
        margin-top: 10px;
    }
    .shortlink-display input {
        flex: 1; 
        padding: 16px; 
        border: 2px solid #eaeaea; 
        border-radius: 14px; 
        font-weight: 600; 
        background: #fafafa; 
        font-size: 15px; 
        color: #111; 
        outline: none; 
        text-align: center;
        transition: border-color 0.2s;
    }
    .shortlink-display input:focus {
        border-color: #0067D5;
    }
    .shortlink-display button {
        padding: 16px 24px; 
        background: #0067D5; 
        color: #fff; 
        border: none; 
        border-radius: 14px; 
        font-weight: 700; 
        font-size: 15px; 
        cursor: pointer; 
        transition: background 0.2s, transform 0.1s; 
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .shortlink-display button:hover {
        background: #0056b3;
    }
    .shortlink-display button:active {
        transform: scale(0.96);
    }
    
    /* PAGINATION FIX & ANIMATION */
    nav[role="navigation"] {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin-top: 30px;
        width: 100%;
        position: relative;
    }
    
    .hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:first-child {
        display: none !important;
    }
    
    .hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between {
        display: flex !important;
        justify-content: center !important;
        width: 100%;
    }

    span.relative.z-0.inline-flex {
        display: inline-flex;
        background: #fff;
        border-radius: 50px;
        padding: 6px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #f0f0f0;
        gap: 6px;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    span.relative.z-0.inline-flex > a,
    span.relative.z-0.inline-flex > span > span,
    span.relative.z-0.inline-flex > span {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        padding: 0 12px !important;
        border-radius: 21px !important;
        font-size: 15px;
        font-weight: 700;
        color: #666;
        background: transparent !important;
        border: none !important;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: none !important;
        text-decoration: none;
        margin: 0 !important;
        z-index: 2;
        position: relative;
    }

    span.relative.z-0.inline-flex > a:hover {
        background: #FFF3E6 !important;
        color: #FF9040 !important;
        transform: scale(1.1);
    }

    @keyframes rollInActive {
        0% {
            transform: translateX(-30px) rotate(-180deg) scale(0.5);
            opacity: 0;
            background: #fff !important;
        }
        50% {
            background: #FF9040 !important;
        }
        100% {
            transform: translateX(0) rotate(0deg) scale(1);
            opacity: 1;
            background: #FF9040 !important;
        }
    }

    span.relative.z-0.inline-flex > span[aria-current="page"] > span {
        background: #FF9040 !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(255,144,64,0.3) !important;
        animation: rollInActive 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    span.relative.z-0.inline-flex > span[aria-disabled="true"] > span {
        opacity: 0.4;
        cursor: not-allowed;
        background: transparent !important;
        color: #999 !important;
        transform: none !important;
        animation: none;
    }

    .w-5.h-5, nav[role="navigation"] svg {
        width: 20px !important;
        height: 20px !important;
        display: block;
    }

    nav[role="navigation"] .flex.justify-between:not(.hidden) {
        display: flex;
        width: 100%;
        justify-content: space-between;
        background: #fff;
        padding: 10px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    nav[role="navigation"] .flex.justify-between:not(.hidden) a, 
    nav[role="navigation"] .flex.justify-between:not(.hidden) span {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        color: #FF9040;
        background: #FFF3E6;
        border: none;
    }
    nav[role="navigation"] .flex.justify-between:not(.hidden) span {
        opacity: 0.5;
        background: #f8f9fa;
        color: #999;
    }
    @media (min-width: 640px) {
        nav[role="navigation"] .flex.justify-between:not(.hidden) {
            display: none !important;
        }
    }
    
    /* DETAIL PANEL CSS */
    :root {
        --panel-w: 480px;
        --transition-speed: 0.4s;
    }

    #sl-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.75); /* Made darker as requested */
        backdrop-filter: blur(5px);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: all var(--transition-speed) cubic-bezier(0.16, 1, 0.3, 1);
    }
    #sl-overlay.is-visible { opacity: 1; visibility: visible; }
    
    #sl-panel {
        position: fixed; top: 0; right: calc(-1 * var(--panel-w));
        width: var(--panel-w); height: 100vh;
        background: #fff; z-index: 999999;
        box-shadow: none;
        transition: right 0.5s cubic-bezier(0.19, 1, 0.22, 1), box-shadow 0.5s ease;
        display: flex; flex-direction: column;
        overflow-y: auto;
    }
    #sl-panel.is-open { 
        right: 0; 
        box-shadow: -10px 0 50px rgba(0,0,0,0.5); 
    }
    
    @media (max-width: 768px) {
        :root { --panel-w: 100vw; }
    }

    /* TOP HEADER */
    .preview-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 30px; border-bottom: 1px solid var(--border-color);
        background: #fff; position: sticky; top: 0; z-index: 10;
    }
    
    .preview-header-left {
        display: flex; align-items: center; gap: 16px;
    }
    
    .preview-close {
        background: none; border: none; font-size: 20px; color: #666; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 50%; transition: 0.2s;
    }
    .preview-close:hover { background: #f0f0f0; color: #181818; }
    
    .preview-header h2 {
        font-size: 20px; font-weight: 700; color: #181818; margin: 0;
    }

    .preview-btn {
        background: #fff; border: 1px solid var(--border-color); color: #181818;
        padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 700;
        cursor: pointer; transition: 0.2s; text-decoration: none; display: inline-block;
    }
    .preview-btn:hover { background: #f9f9f9; border-color: #d1d5db; }

    /* IDENTITY SECTION */
    .preview-identity {
        padding: 30px; border-bottom: 1px solid var(--border-color);
        display: flex; justify-content: space-between; align-items: flex-start;
    }
    
    .identity-left {
        display: flex; gap: 20px; align-items: flex-start;
    }
    
    .identity-icon {
        width: 60px; height: 60px; border-radius: 50%;
        background: #FFF3E6; color: #FF9040;
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; flex-shrink: 0;
    }
    
    .identity-info h3 {
        font-size: 22px; font-weight: 800; color: #181818; margin: 0 0 8px 0;
    }
    
    .identity-links {
        display: flex; gap: 16px; font-size: 13px; color: #666; flex-wrap: wrap;
    }
    
    .identity-links span {
        display: flex; align-items: center; gap: 6px;
    }
    .identity-links i { color: #999; }
    
    .identity-actions {
        display: flex; gap: 10px;
    }
    
    .circle-action {
        width: 40px; height: 40px; border-radius: 50%;
        border: 1px solid var(--border-color); background: #fff;
        color: #181818; display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: 0.2s; font-size: 15px;
    }
    .circle-action:hover { border-color: #FF9040; color: #FF9040; background: #FFF3E6; }

    .action-btn-row {
        background: #fff; border: 1px solid var(--border-color); color: #181818;
        padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
        cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 8px;
    }
    .action-btn-row:hover { background: #f9f9f9; border-color: #d1d5db; }

    /* METADATA GRID */
    .preview-meta-grid {
        display: grid; grid-template-columns: repeat(2, 1fr);
        gap: 20px; padding: 24px 30px; border-bottom: 1px solid var(--border-color);
        background: #fafafa;
    }
    
    .meta-box {
        display: flex; flex-direction: column; gap: 6px;
    }
    
    .meta-box-label {
        font-size: 11px; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .meta-box-value {
        font-size: 14px; color: #181818; font-weight: 700; word-break: break-word;
        display: flex; align-items: center; gap: 6px;
    }

    /* PERFORMANCE TABS */
    .preview-section { padding: 30px; border-bottom: 1px solid var(--border-color); }
    
    .section-title {
        font-size: 16px; font-weight: 800; color: #181818; margin: 0 0 16px 0;
        display: flex; align-items: center; gap: 8px;
    }
    
    .badge-count {
        background: #D4ED31; color: #181818; font-size: 12px; font-weight: 800;
        padding: 2px 8px; border-radius: 20px;
    }
    .badge-count.peach { background: #FF9040; color: #fff; }

    .tabs-container {
        display: grid; grid-template-columns: repeat(4, 1fr);
        background: #f9f9f9; border: 1px solid var(--border-color);
        border-radius: 6px; overflow: hidden; margin-bottom: 24px;
    }
    
    .tab-item {
        padding: 12px; text-align: center; font-size: 13px; font-weight: 700; color: #666;
        border-right: 1px solid var(--border-color); cursor: pointer;
    }
    .tab-item:last-child { border-right: none; }
    .tab-item.active { background: #FFF3E6; color: #FF9040; }
    
    .stepper-container {
        display: flex; align-items: center; justify-content: space-between;
        position: relative; padding: 0 40px; margin-top: 10px;
    }
    .stepper-line {
        position: absolute; top: 12px; left: 60px; right: 60px; height: 2px;
        background: #e5e7eb; z-index: 1;
    }
    .stepper-line-progress {
        position: absolute; top: 12px; left: 60px; width: 40%; height: 2px;
        background: #FF9040; z-index: 2;
    }
    .step-item {
        position: relative; z-index: 3; display: flex; flex-direction: column; align-items: center; gap: 10px;
    }
    .step-circle {
        width: 24px; height: 24px; border-radius: 50%; background: #fff; border: 2px solid #e5e7eb;
    }
    .step-circle.active { border-color: #FF9040; background: #FF9040; }
    .step-circle.completed { background: #D4ED31; border-color: #D4ED31; }
    .step-circle.completed::after {
        content: '✓'; color: #181818; font-size: 14px; font-weight: bold;
        display: flex; align-items: center; justify-content: center; height: 100%;
    }
    
    .step-label { font-size: 12px; font-weight: 600; color: #666; }
    .step-item.active .step-label { color: #181818; font-weight: 800; }

    /* INSIGHTS & NOTES */
    .insight-card, .note-card {
        border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; margin-bottom: 16px;
    }
    .insight-header, .note-header {
        padding: 16px 20px; display: flex; align-items: flex-start; gap: 12px;
    }
    .note-header { border-bottom: 1px solid var(--border-color); justify-content: space-between; align-items: center; }
    .insight-radio {
        width: 18px; height: 18px; border-radius: 50%; border: 2px solid #d1d5db; margin-top: 2px; flex-shrink: 0;
    }
    .insight-content h4 { margin: 0 0 6px 0; font-size: 15px; font-weight: 700; color: #181818; }
    .insight-content p { margin: 0; font-size: 13px; color: #666; line-height: 1.5; }
    
    .insight-footer {
        display: grid; grid-template-columns: 1fr 1fr 1fr;
        background: #f9f9fb; border-top: 1px solid var(--border-color);
    }
    .insight-footer-box {
        padding: 12px 20px; border-right: 1px solid var(--border-color);
    }
    .insight-footer-box:last-child { border-right: none; }
    .if-label { font-size: 12px; color: #666; margin-bottom: 4px; }
    .if-value { font-size: 13px; font-weight: 700; color: #181818; display: flex; align-items: center; gap: 6px;}

    .note-header-left { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 14px; color: #181818; }
    .note-header-right { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #666; }
    .note-body { padding: 20px; font-size: 14px; color: #444; line-height: 1.6; }

    /* Detail Button CSS */
    .btn-detail-text {
        background: transparent;
        border: 2px solid #FF9040;
        color: #FF9040;
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex; align-items: center; gap: 6px;
        text-decoration: none;
    }
    .btn-detail-text:hover {
        background: #FF9040;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255,144,64,0.2);
    }
    
    @media (max-width: 767px) {
        :root { --panel-w: 100vw; }
    }

    /* Mobile App-like Styles */
    .mobile-only-header-bar {
        display: none;
    }
    .mobile-only-new-link-btn {
        display: none;
    }
    .mobile-segment-control {
        display: none;
    }
    .mobile-list-subheader {
        display: none;
    }
    .mobile-only-actions {
        display: none;
    }
    .mobile-list-icon-container {
        display: none;
    }
    .mobile-slug-text {
        display: none;
    }

    @media (max-width: 768px) {
        /* Force white background on wrapper and components */
        body, .dashboard-wrapper, .dashboard-grid, .left-col, .right-col, .content-wrapper, .main-content {
            background-color: #fff !important;
        }

        .dashboard-grid {
            margin-top: 0px !important;
            gap: 0px !important;
        }

        /* Show Performance Card (graphic) on mobile, style edge-to-edge, flat merge with divider */
        .left-col > .card.performance-card {
            display: block !important;
            margin: 0 -16px !important; /* Tarik ujung layar */
            border-radius: 0 !important;
            border: none !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: none !important;
            padding: 16px 10px !important; /* Tipis di pinggir */
            background: #fff !important;
        }

        /* Collapsible Creation Form on Mobile as a distinct card container */
        .mobile-form-collapse-header {
            margin: 0 -16px !important; /* Tarik ujung layar */
            border-radius: 0 !important;
            border: none !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: none !important;
            background: #fff !important;
            padding: 16px 10px !important; /* Tipis di pinggir */
        }
        .mobile-form-collapse.is-open .mobile-form-collapse-header {
            margin: 0 -16px !important; /* Pastikan state open juga mepet */
            border-radius: 0 !important;
            border: none !important;
            border-bottom: 1px solid #e2e8f0 !important;
            background: #fbfbfc !important;
            padding: 16px 10px !important; /* Tipis di pinggir */
        }
        .mobile-form-collapse-body > form > .card {
            margin: 0 -16px !important; /* Tarik card ujung layar */
            border-radius: 0 !important;
            border: none !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: none !important;
        }

        /* Unified Container layout for search card and engagement card, flat merge with divider */
        .left-col > .card.search-filter-card {
            margin: 0 -16px !important; /* Tarik ujung layar */
            border-radius: 0 !important;
            border: none !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: none !important;
            padding: 16px 10px !important; /* Tipis di pinggir */
            background: #fff !important;
        }
        .dashboard-wrapper > .card.engagement-card {
            margin: 0 -16px !important; /* Tarik list ke ujung layar menutupi padding content-wrapper */
            border-radius: 0 !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            background: #fff !important;
        }

        /* Search input styling matching screenshot */
        .left-col input[name="search"] {
            border-radius: 100px !important;
            background: #f4f4f6 !important;
            border: none !important;
            padding: 12px 40px 12px 40px !important;
        }
        
        .desktop-sort-wrapper {
            display: none !important;
        }

        /* Segmented control tabs */
        .mobile-segment-control {
            display: flex;
            background: #f4f4f6;
            border-radius: 100px;
            padding: 4px;
            margin-top: 12px;
            gap: 4px;
        }
        .segment-tab {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .segment-tab.active {
            background: #FFF3E6;
            color: #FF9040;
            box-shadow: 0 2px 6px rgba(255,144,64,0.12);
            font-weight: 700;
        }

        /* Subheader */
        .desktop-only-card-header {
            display: none !important;
        }
        .mobile-list-subheader {
            display: flex;
            justify-content: space-between;
            padding: 12px 10px; /* Tipis di pinggir */
            background: #f9f9fb;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            font-weight: 700;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .mobile-list-subheader span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* List Items */
        .engagement-list {
            padding: 0 !important;
            gap: 0 !important;
        }
        .engagement-item {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 16px 10px !important; /* Tipis di pinggir */
            border: none !important;
            border-bottom: 1px dashed #e2e8f0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            margin: 0 !important;
            gap: 12px;
        }
        .engagement-item:last-child {
            border-bottom: none !important;
        }

        .mobile-list-icon-container {
            display: flex;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #FFF3E6;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #FF9040;
            flex-shrink: 0;
        }

        .eng-info {
            flex: 1;
            min-width: 0;
        }
        .eng-info h4 {
            font-size: 14px !important;
            font-weight: 700;
            margin: 0 0 4px 0 !important;
            color: #181818;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .eng-info p, .eng-info a {
            display: none !important;
        }
        .mobile-slug-text {
            display: block;
            font-size: 12px;
            color: #777;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Actions */
        .desktop-only-actions {
            display: none !important;
        }
        .mobile-only-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .mobile-action-circle-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid #e2e8f0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
        }
        .mobile-action-circle-btn:hover, .mobile-action-circle-btn:active {
            background: #FFF3E6;
            color: #FF9040;
            border-color: #FF9040;
        }
        
        /* Pagination Container on mobile */
        .dashboard-wrapper > .card.engagement-card > div:last-child {
            padding: 16px 10px !important; /* Tipis di pinggir */
            border-top: 1px solid #e2e8f0;
            margin-top: 0 !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
<div class="dashboard-wrapper">




    <?php if(session('success')): ?>
        <div class="success-card-overlay" id="successOverlay">
            <div class="success-card" id="successToast">
                <button class="success-card-close" onclick="closeToast()"><i class="fas fa-times"></i></button>
                
                <dotlottie-wc src="https://lottie.host/5d51f8d8-3e8d-4466-9fa3-03a220ba6911/K5POZcWmAX.lottie" style="width: 200px; height: 200px;" autoplay loop></dotlottie-wc>
                
                <h3><?php echo e(__('shortlink.saved_successfully')); ?></h3>
                <p style="color: #666; font-size: 15px; margin: 0;">Link telah berhasil dibuat dan siap untuk dibagikan.</p>
                
                <div class="shortlink-display">
                    <input type="text" id="shortlinkInput" value="<?php echo e(session('success')); ?>" readonly>
                    <button class="btn-copy" onclick="copyToClipboard()">
                        <i class="fas fa-copy"></i> <?php echo e(__('shortlink.copy')); ?>

                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="dashboard-grid">
        <!-- LEFT COLUMN -->
        <div class="left-col">
            <!-- PERFORMANCE CARD -->
            <div class="card performance-card">
                <div class="card-header">
                    <div class="card-title"><?php echo e(__('shortlink.performance')); ?></div>
                    <div style="font-size: 13px; color: #888;"><?php echo e(__('shortlink.data_preview')); ?></div>
                </div>

                <div class="stats-row">
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #FFF3E6; color: #FF9040;"><i class="fas fa-chart-line"></i></div>
                        <div class="stat-info">
                            <span><?php echo e(__('shortlink.total_clicks')); ?></span>
                            <strong>2,280</strong>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon" style="background: #E6F6FF; color: #0088FF;"><i class="fas fa-link"></i></div>
                        <div class="stat-info">
                            <span><?php echo e(__('shortlink.total_links')); ?></span>
                            <strong><?php echo e($shortlinks->total()); ?></strong>
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <?php $__currentLoopData = [300, 280, 260, 310, 250, 400, 220, 350, 450, 240, 410, 380]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height: <?php echo e(($val / 450) * 160); ?>px;"></div>
                        <div class="chart-label"><?php echo e(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][$index]); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <button class="hide-chart-btn"><i class="fas fa-chevron-up"></i> <?php echo e(__('shortlink.hide_chart')); ?></button>
            </div>

            <!-- SEARCH & FILTER -->
            <div class="card search-filter-card" style="margin-bottom: 0;">
                <form method="GET" action="<?php echo e(route('admin.shortlinks.index')); ?>" id="search-filter-form">
                    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                        <div style="flex: 1; position: relative; min-width: 200px;">
                            <i class="fas fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #999;"></i>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('shortlink.search_placeholder') ?? 'Search your links...'); ?>" style="width: 100%; padding: 12px 40px 12px 40px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
                            <?php if(request('search')): ?>
                                <a href="<?php echo e(route('admin.shortlinks.index', request()->except(['search', 'page']))); ?>" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #999; text-decoration: none;"><i class="fas fa-times-circle"></i></a>
                            <?php endif; ?>
                        </div>
                        <div style="width: 180px; position: relative; flex-shrink: 0;" class="desktop-sort-wrapper">
                            <select name="sort" onchange="this.form.submit()" style="width: 100%; padding: 12px 32px 12px 16px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; background: #fff; cursor: pointer; appearance: none; color: #181818; box-sizing: border-box;">
                                <option value="newest" <?php echo e(request('sort') == 'newest' ? 'selected' : ''); ?>><?php echo e(__('shortlink.filter_newest') ?? 'Newest'); ?></option>
                                <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?>>Oldest</option>
                                <option value="popular" <?php echo e(request('sort') == 'popular' ? 'selected' : ''); ?>><?php echo e(__('shortlink.filter_popular') ?? 'Most Popular'); ?></option>
                            </select>
                            <i class="fas fa-chevron-down" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #999; pointer-events: none; font-size: 12px;"></i>
                        </div>
                    </div>

                    <!-- Mobile Segmented Sorting Tabs -->
                    <div class="mobile-segment-control">
                        <button type="button" class="segment-tab <?php echo e(request('sort') != 'popular' ? 'active' : ''); ?>" data-sort="newest">
                            <i class="fas fa-bolt"></i> <?php echo e(__('shortlink.filter_newest') ?? 'Newest'); ?>

                        </button>
                        <button type="button" class="segment-tab <?php echo e(request('sort') == 'popular' ? 'active' : ''); ?>" data-sort="popular">
                            <i class="fas fa-fire"></i> <?php echo e(__('shortlink.filter_popular') ?? 'Most Popular'); ?>

                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="right-col mobile-form-collapse">
            <div class="mobile-form-collapse-header" onclick="this.parentElement.classList.toggle('is-open')">
                <span><i class="fas fa-plus-circle" style="color:#FF9040; margin-right:8px;"></i> Create New Link</span>
                <i class="fas fa-chevron-down" style="color:#999;"></i>
            </div>
            <div class="mobile-form-collapse-body">
                <form action="<?php echo e(route('admin.shortlinks.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <!-- CREATE NEW LINK -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><?php echo e(__('shortlink.create_new_link')); ?> <i class="fas fa-link" style="color: #999; margin-left: 8px;"></i></div>
                    </div>
                    <p class="create-form-desc"><?php echo e(__('shortlink.create_desc')); ?></p>
                    
                    <div class="input-row">
                        <input type="url" name="destination" placeholder="https://example.com/your-long-url" required value="<?php echo e(old('destination')); ?>">
                        <button type="submit" class="btn-submit"><?php echo e(__('shortlink.btn_create')); ?> <i class="fas fa-arrow-right"></i></button>
                    </div>
                    <?php $__errorArgs = ['destination'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color: red; font-size: 12px; margin-top: -10px; margin-bottom: 10px;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- CUSTOM YOUR LINK -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><?php echo e(__('shortlink.custom_link')); ?></div>
                    </div>
                    
                    <div class="form-group">
                        <label><?php echo e(__('shortlink.slug_label')); ?></label>
                        <div class="slug-wrapper">
                            <div class="slug-prefix"><i class="fas fa-link"></i> Linkan.id/</div>
                            <input type="text" name="slug" id="slug" placeholder="custom-slug" required value="<?php echo e(old('slug')); ?>">
                            <button type="button" onclick="generateRandomSlug()" style="background: transparent; border: none; padding: 0 16px; color: #FF9040; font-weight: bold; cursor: pointer; border-left: 1px solid #e0e0e0;"><i class="fas fa-random"></i></button>
                        </div>
                        <div style="font-size: 12px; color: #888; margin-top: 6px;"><?php echo e(__('shortlink.slug_hint')); ?></div>
                        <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color: red; font-size: 12px; margin-top: 4px;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label><?php echo e(__('shortlink.title_label')); ?></label>
                        <input type="text" name="title" placeholder="<?php echo e(__('shortlink.title_placeholder')); ?>" value="<?php echo e(old('title')); ?>">
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color: red; font-size: 12px; margin-top: 4px;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label><?php echo e(__('shortlink.desc_label')); ?></label>
                        <input type="text" name="description" placeholder="<?php echo e(__('shortlink.desc_placeholder')); ?>" value="<?php echo e(old('description')); ?>">
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color: red; font-size: 12px; margin-top: 4px;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>

            <!-- ENGAGEMENT ALL TIME -->
            <div class="card engagement-card">
                <div class="card-header desktop-only-card-header">
                    <div class="card-title"><?php echo e(__('shortlink.engagement')); ?></div>
                    <div style="font-size: 13px; color: #888;"><?php echo e($shortlinks->total()); ?> <?php echo e(__('shortlink.results')); ?></div>
                </div>

                <!-- Mobile List Subheader -->
                <div class="mobile-list-subheader">
                    <span class="col-name"><?php echo e(__('shortlink.name_label')); ?> <i class="fas fa-sort"></i></span>
                    <span class="col-actions"><?php echo e(__('shortlink.actions_label')); ?> <i class="fas fa-sort"></i></span>
                </div>

                <div class="engagement-list">
                    <?php $__empty_1 = true; $__currentLoopData = $shortlinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="engagement-item sl-card"
                         data-id="<?php echo e($link->id); ?>"
                         data-title="<?php echo e($link->title ?: $link->slug); ?>"
                         data-description="<?php echo e($link->description ?: ''); ?>"
                         data-destination="<?php echo e($link->destination); ?>"
                         data-url="<?php echo e(url('/' . $link->slug)); ?>"
                         data-created="<?php echo e($link->created_at->format('d M Y, H:i')); ?>"
                         data-updated="<?php echo e($link->updated_at->format('d M Y, H:i')); ?>"
                         data-slug="<?php echo e($link->slug); ?>"
                         data-password="<?php echo e($link->password); ?>"
                         data-expires="<?php echo e($link->expires_at ? $link->expires_at->format('Y-m-d\TH:i') : ''); ?>"
                    >
                        <!-- Mobile Link Icon -->
                        <div class="mobile-list-icon-container">
                            <i class="fas fa-link"></i>
                        </div>

                        <div class="eng-info">
                            <h4><?php echo e($link->title ?: __('shortlink.untitled') . ' (' . $link->slug . ')'); ?></h4>
                            <a href="<?php echo e(url('/' . $link->slug)); ?>" target="_blank" style="font-size: 13px; color: #FF9040; font-weight: 700; text-decoration: none; display: block; margin-bottom: 4px;">Linkan.id/<?php echo e($link->slug); ?></a>
                            <p><?php echo e(Str::limit($link->destination, 60)); ?></p>
                            <?php if($link->description): ?>
                            <p style="font-size: 12px; color: #999; margin-top: 4px;"><?php echo e(Str::limit($link->description, 60)); ?></p>
                            <?php endif; ?>
                            <!-- Mobile Subtitle -->
                            <span class="mobile-slug-text">Linkan.id/<?php echo e($link->slug); ?></span>
                        </div>
                        
                        <!-- Desktop Actions -->
                        <div class="eng-actions desktop-only-actions">
                            <button type="button" class="btn-detail-text sl-btn--detail"><?php echo e(__('shortlink.detail')); ?></button>
                            <a href="<?php echo e(route('admin.shortlinks.analytics', $link)); ?>" class="btn-icon" title="Analytics"><i class="fas fa-chart-bar"></i></a>
                            <button type="button" class="btn-icon sl-btn--copy" onclick="copySlugToClipboard('<?php echo e(url('/' . $link->slug)); ?>', this)" title="Copy Link"><i class="fas fa-copy"></i></button>
                        </div>

                        <!-- Mobile Actions -->
                        <div class="mobile-only-actions">
                            <button type="button" class="mobile-action-circle-btn sl-btn--edit-direct" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                            <button type="button" class="mobile-action-circle-btn sl-btn--detail" title="Detail"><i class="fas fa-ellipsis-h"></i></button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div style="text-align: center; padding: 30px; color: #999;">
                        <i class="fas fa-link" style="font-size: 32px; margin-bottom: 10px; color: #ddd;"></i>
                        <p><?php echo e(__('shortlink.no_shortlink')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div style="margin-top: 20px;">
                    <?php echo e($shortlinks->appends(request()->except('page'))->links()); ?>

                </div>
            </div>
</div>

<!-- PANEL OVERLAY -->
<div id="sl-overlay"></div>

<!-- SLIDE OUT PANEL -->
<aside id="sl-panel">
    <form id="panel-form" method="POST" style="display:flex; flex-direction:column; height:100%; margin:0;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="preview-header">
            <div style="flex: 1;"></div>
            <h2 style="margin:0; font-size:18px; font-weight:800; text-align:center;"><?php echo e(__('shortlink.link_detail')); ?></h2>
            <div style="flex: 1; display:flex; justify-content:flex-end;">
                <button type="button" class="preview-close" id="sl-panel-close"><i class="fas fa-times"></i></button>
            </div>
        </div>

        <div id="panel-view-section">
            <div class="preview-identity" style="border-bottom: none; padding-bottom: 20px;">
                <div class="identity-left" style="width: 100%;">
                    <div class="identity-icon"><i class="fas fa-link"></i></div>
                    <div class="identity-info">
                        <h3 id="panel-title">Judul Shortlink</h3>
                        <div class="identity-links">
                            <span><i class="far fa-envelope"></i> <span id="panel-desc">Deskripsi link</span></span>
                            <span><i class="fas fa-globe"></i> <span id="panel-slug-badge" style="color: #FF9040; font-weight:700;">/slug</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BUTTONS IN THE MIDDLE -->
            <div style="padding: 0 30px 30px 30px; display: flex; justify-content: center; gap: 12px; border-bottom: 1px solid var(--border-color);">
                <button type="button" class="action-btn-row" style="flex: 1; justify-content: center; flex-direction: column; gap: 8px; padding: 16px 12px;" onclick="copySlugToClipboard(document.getElementById('panel-url').href, this)">
                    <i class="far fa-copy" style="font-size: 20px; color: #666;"></i> <?php echo e(__('shortlink.copy')); ?>

                </button>
                <button type="button" class="action-btn-row" style="flex: 1; justify-content: center; flex-direction: column; gap: 8px; padding: 16px 12px; border-color: #FF9040; color: #FF9040; background: #FFF3E6;" onclick="toggleSection('edit')">
                    <i class="fas fa-edit" style="font-size: 20px;"></i> <?php echo e(__('shortlink.btn_edit')); ?>

                </button>
                <a href="#" id="panel-btn-analytics" class="action-btn-row" style="flex: 1; justify-content: center; flex-direction: column; gap: 8px; padding: 16px 12px; text-decoration: none;">
                    <i class="fas fa-chart-bar" style="font-size: 20px; color: #666;"></i> <?php echo e(__('shortlink.btn_stats')); ?>

                </a>
                <button type="button" class="action-btn-row" style="flex: 1; justify-content: center; flex-direction: column; gap: 8px; padding: 16px 12px;" onclick="window.open(document.getElementById('panel-url').href, '_blank')">
                    <i class="fas fa-external-link-alt" style="font-size: 20px; color: #666;"></i> <?php echo e(__('shortlink.btn_open')); ?>

                </button>
            </div>

            <div class="preview-meta-grid">
                <div class="meta-box">
                    <div class="meta-box-label"><?php echo e(__('shortlink.created_by')); ?></div>
                    <div class="meta-box-value"><i class="fas fa-user-circle" style="color: #FF9040;"></i> <?php echo e(__('shortlink.sys_admin')); ?></div>
                </div>
                <div class="meta-box">
                    <div class="meta-box-label"><?php echo e(__('shortlink.status')); ?></div>
                    <div class="meta-box-value" style="color: #2ecc40;"><i class="fas fa-check-circle"></i> <?php echo e(__('shortlink.active')); ?></div>
                </div>
                <div class="meta-box">
                    <div class="meta-box-label"><?php echo e(__('shortlink.created_at')); ?></div>
                    <div class="meta-box-value"><i class="far fa-calendar-plus" style="color: #999;"></i> <span id="panel-created">...</span></div>
                </div>
                <div class="meta-box">
                    <div class="meta-box-label"><?php echo e(__('shortlink.last_edited')); ?></div>
                    <div class="meta-box-value"><i class="far fa-calendar-check" style="color: #999;"></i> <span id="panel-updated">...</span></div>
                </div>
            </div>

            <div class="preview-section" style="flex:1;">
                <h4 class="section-title" style="margin-top: 0px;"><?php echo e(__('shortlink.config_status')); ?></h4>
                
                <!-- Tautan Terproteksi -->
                <div style="margin-bottom: 20px; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #fff;">
                    <div style="display: flex; gap: 16px; align-items: center;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: #FFF3E6; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #FF9040;">
                            <i class="fas fa-unlock" id="status-password-icon"></i>
                        </div>
                        <div>
                            <div style="font-weight: 800; color: #181818; margin-bottom: 4px; font-size: 15px;" id="status-password"><?php echo e(__('shortlink.pub_link')); ?></div>
                            <div style="color: #666; font-size: 13px; line-height: 1.5; max-width: 400px;"><?php echo e(__('shortlink.pub_link_desc')); ?></div>
                        </div>
                    </div>
                    <button type="button" onclick="toggleSection('password')" class="action-btn-row" style="flex-shrink:0;">
                        <i class="fas fa-key"></i> <?php echo e(__('shortlink.set_password')); ?>

                    </button>
                </div>

                <!-- Tautan Berjangka -->
                <div style="margin-bottom: 24px; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #fff;">
                    <div style="display: flex; gap: 16px; align-items: center;">
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: #FFF3E6; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #FF9040;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div style="font-weight: 800; color: #181818; margin-bottom: 4px; font-size: 15px;" id="status-expires"><?php echo e(__('shortlink.no_time_limit')); ?></div>
                            <div style="color: #666; font-size: 13px; line-height: 1.5; max-width: 400px;"><?php echo e(__('shortlink.no_time_limit_desc')); ?></div>
                        </div>
                    </div>
                    <button type="button" onclick="toggleSection('expires')" class="action-btn-row" style="flex-shrink:0;">
                        <i class="fas fa-stopwatch"></i> <?php echo e(__('shortlink.set_time')); ?>

                    </button>
                </div>

                <h4 class="section-title" style="margin-top: 30px;"><?php echo e(__('shortlink.link_info')); ?></h4>
                <div class="note-card">
                    <div class="note-header">
                        <div class="note-header-left"><i class="far fa-sticky-note" style="color:#999;"></i> <?php echo e(__('shortlink.destination_url')); ?></div>
                    </div>
                    <div class="note-body">
                        <a href="#" id="panel-destination" target="_blank" style="color: #FF9040; text-decoration: none; word-break: break-all;"></a>
                    </div>
                </div>
                
                <div class="note-card">
                    <div class="note-header">
                        <div class="note-header-left"><i class="far fa-sticky-note" style="color:#999;"></i> Shortlink URL</div>
                    </div>
                    <div class="note-body">
                        <a href="#" id="panel-url" target="_blank" style="color: #FF9040; text-decoration: none; word-break: break-all;"></a>
                    </div>
                </div>
            </div>
            
            <a href="#" id="panel-url" style="display:none;"></a>
        </div>

        <div id="panel-edit-section" style="display: none; flex: 1; padding: 30px;">
            <h4 class="section-title" style="margin-top: 0px; margin-bottom: 24px;"><?php echo e(__('shortlink.link_config')); ?></h4>
            
            <div class="form-group">
                <label style="font-size: 13px; font-weight: 700; color: #181818; display: block; margin-bottom: 8px;"><?php echo e(__('shortlink.title_label')); ?></label>
                <input type="text" name="title" id="panel-input-title" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
            </div>

            <div class="form-group" style="margin-top: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: #181818; display: block; margin-bottom: 8px;"><?php echo e(__('shortlink.edit_slug_label')); ?></label>
                <input type="text" name="slug" id="panel-input-slug" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
            </div>

            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <button type="submit" class="preview-btn" style="background: #FF9040; color: #fff; border: none; padding: 12px 24px; font-size: 14px; flex: 1; cursor: pointer;"><i class="fas fa-save" style="margin-right: 8px;"></i> <?php echo e(__('shortlink.save_changes')); ?></button>
                <button type="button" class="preview-btn" onclick="toggleSection('view')" style="background: #f1f1f1; color: #333; border: 1px solid #ddd; padding: 12px 24px; font-size: 14px; cursor: pointer;"><?php echo e(__('shortlink.cancel')); ?></button>
            </div>
        </div>

        <div id="panel-password-section" style="display: none; flex: 1; padding: 30px;">
            <h4 class="section-title" style="margin-top: 0px; margin-bottom: 24px;"><?php echo e(__('shortlink.set_password_title')); ?></h4>
            <div class="form-group">
                <label style="font-size: 13px; font-weight: 700; color: #181818; display: block; margin-bottom: 8px;"><?php echo e(__('shortlink.password_label')); ?></label>
                <input type="text" name="password" id="panel-input-password" placeholder="<?php echo e(__('shortlink.password_placeholder')); ?>" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
            </div>
            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <button type="submit" class="preview-btn" style="background: #FF9040; color: #fff; border: none; padding: 12px 24px; font-size: 14px; flex: 1; cursor: pointer;"><i class="fas fa-save" style="margin-right: 8px;"></i> <?php echo e(__('shortlink.save_changes')); ?></button>
                <button type="button" class="preview-btn" onclick="toggleSection('view')" style="background: #f1f1f1; color: #333; border: 1px solid #ddd; padding: 12px 24px; font-size: 14px; cursor: pointer;"><?php echo e(__('shortlink.cancel')); ?></button>
            </div>
        </div>

        <div id="panel-expires-section" style="display: none; flex: 1; padding: 30px;">
            <h4 class="section-title" style="margin-top: 0px; margin-bottom: 24px;"><?php echo e(__('shortlink.expiration')); ?></h4>
            <div class="form-group">
                <label style="font-size: 13px; font-weight: 700; color: #181818; display: block; margin-bottom: 8px;"><?php echo e(__('shortlink.expiration_label')); ?></label>
                <input type="datetime-local" name="expires_at" id="panel-input-expires" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-family: inherit; font-size: 14px; box-sizing: border-box;">
            </div>
            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <button type="submit" class="preview-btn" style="background: #FF9040; color: #fff; border: none; padding: 12px 24px; font-size: 14px; flex: 1; cursor: pointer;"><i class="fas fa-save" style="margin-right: 8px;"></i> <?php echo e(__('shortlink.save_changes')); ?></button>
                <button type="button" class="preview-btn" onclick="toggleSection('view')" style="background: #f1f1f1; color: #333; border: 1px solid #ddd; padding: 12px 24px; font-size: 14px; cursor: pointer;"><?php echo e(__('shortlink.cancel')); ?></button>
            </div>
        </div>
    </form>
</aside>

<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts"); ?>
<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.9.4/dist/dotlottie-wc.js" type="module"></script>
<script>
    function toggleSection(mode) {
        const viewSec = document.getElementById('panel-view-section');
        const editSec = document.getElementById('panel-edit-section');
        const pwdSec = document.getElementById('panel-password-section');
        const expSec = document.getElementById('panel-expires-section');

        if(viewSec) viewSec.style.display = (mode === 'view') ? 'block' : 'none';
        if(editSec) editSec.style.display = (mode === 'edit') ? 'block' : 'none';
        if(pwdSec) pwdSec.style.display = (mode === 'password') ? 'block' : 'none';
        if(expSec) expSec.style.display = (mode === 'expires') ? 'block' : 'none';
    }

    function copyToClipboard() {
        const input = document.getElementById("shortlinkInput");
        if(input) {
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand("copy");
            
            const copyBtn = document.querySelector('.btn-copy');
            const originalHTML = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fas fa-check"></i> <?php echo e(__('shortlink.copied')); ?>';
            setTimeout(() => {
                copyBtn.innerHTML = originalHTML;
            }, 2000);
        }
    }

    function closeToast() {
        const toast = document.getElementById('successToast');
        const overlay = document.getElementById('successOverlay');
        if (toast) toast.classList.add('hiding');
        if (overlay) {
            overlay.classList.add('hiding');
            setTimeout(() => {
                overlay.remove();
            }, 400); // Wait for animation to finish
        } else if (toast) {
            setTimeout(() => {
                toast.remove();
            }, 400);
        }
    }

    function generateRandomSlug() {
        const length = 6;
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        
        const slugInput = document.getElementById('slug');
        if(slugInput) {
            slugInput.value = result;
            slugInput.style.backgroundColor = '#FFF3E6';
            slugInput.style.color = '#FF9040';
            setTimeout(() => {
                slugInput.style.backgroundColor = 'transparent';
                slugInput.style.color = 'inherit';
            }, 300);
        }
    }

    function copySlugToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.style.background = '#2ecc40';
            btn.style.color = '#fff';
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.style.background = '#FFF3E6';
                btn.style.color = '#FF9040';
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    // Detail Panel Logic
    document.addEventListener("turbo:load", function() {
        const overlay = document.getElementById('sl-overlay');
        const panel = document.getElementById('sl-panel');
        const closeBtn = document.getElementById('sl-panel-close');

        function openPanel() {
            const currentOverlay = document.getElementById('sl-overlay');
            const currentPanel = document.getElementById('sl-panel');
            if(currentOverlay) currentOverlay.classList.add('is-visible');
            if(currentPanel) currentPanel.classList.add('is-open');
        }

        function closePanel() {
            const viewSec = document.getElementById('panel-view-section');
            if (viewSec && viewSec.style.display === 'none') {
                toggleSection('view');
                return;
            }
            
            const currentOverlay = document.getElementById('sl-overlay');
            const currentPanel = document.getElementById('sl-panel');
            if(currentOverlay) currentOverlay.classList.remove('is-visible');
            if(currentPanel) currentPanel.classList.remove('is-open');
        }

        if (closeBtn) {
            // Remove previous event listeners to prevent duplicate triggers with turbo
            const newCloseBtn = closeBtn.cloneNode(true);
            closeBtn.parentNode.replaceChild(newCloseBtn, closeBtn);
            newCloseBtn.addEventListener('click', closePanel);
        }
        
        if (overlay) {
            const newOverlay = overlay.cloneNode(true);
            overlay.parentNode.replaceChild(newOverlay, overlay);
            newOverlay.addEventListener('click', closePanel);
        }

        // Event delegation for detail panel buttons to support dynamic AJAX elements
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.sl-btn--detail');
            if (!btn) return;

            e.preventDefault();
            const card = btn.closest('.sl-card');
            if (!card) return;

            const form = document.getElementById('panel-form');
            if(form) {
                form.action = `/admin/shortlinks/${card.dataset.id}`;
            }

            const panelInputSlug = document.getElementById('panel-input-slug');
            if(panelInputSlug) panelInputSlug.value = card.dataset.slug;

            const panelInputTitle = document.getElementById('panel-input-title');
            if(panelInputTitle) panelInputTitle.value = card.dataset.title;

            const panelInputPassword = document.getElementById('panel-input-password');
            if(panelInputPassword) panelInputPassword.value = card.dataset.password;

            const panelInputExpires = document.getElementById('panel-input-expires');
            if(panelInputExpires) panelInputExpires.value = card.dataset.expires;

            toggleSection('view');

            const panelTitle = document.getElementById('panel-title');
            if(panelTitle) panelTitle.innerText = card.dataset.title;
            
            const urlEl = document.getElementById('panel-url');
            if(urlEl) urlEl.href = card.dataset.url;

            const panelCreatedDate = document.getElementById('panel-created-date');
            if(panelCreatedDate) panelCreatedDate.innerText = card.dataset.created;

            const statusPasswordIcon = document.getElementById('status-password-icon');
            const statusPassword = document.getElementById('status-password');
            if(statusPassword) {
                if (card.dataset.password) {
                    statusPassword.innerText = '<?php echo e(__('shortlink.password_protected')); ?>';
                    if (statusPasswordIcon) statusPasswordIcon.className = 'fas fa-lock';
                } else {
                    statusPassword.innerText = '<?php echo e(__('shortlink.pub_link')); ?>';
                    if (statusPasswordIcon) statusPasswordIcon.className = 'fas fa-unlock';
                }
            }

            const statusExpires = document.getElementById('status-expires');
            if(statusExpires) {
                if (card.dataset.expires) {
                    statusExpires.innerText = '<?php echo e(__('shortlink.expired_label')); ?>: ' + card.dataset.expires.replace('T', ' ');
                } else {
                    statusExpires.innerText = '<?php echo e(__('shortlink.no_time_limit')); ?>';
                }
            }

            const panelSlug = document.getElementById('panel-slug-badge');
            if(panelSlug) {
                const urlPath = new URL(card.dataset.url).pathname;
                panelSlug.innerText = urlPath;
            }
            
            const panelDesc = document.getElementById('panel-desc');
            if(panelDesc) panelDesc.innerText = card.dataset.description || 'No description provided';
            
            const destEl = document.getElementById('panel-destination');
            if(destEl) {
                destEl.href = card.dataset.destination;
                destEl.innerText = card.dataset.destination;
            }

            const urlEl2 = document.getElementById('panel-url');
            if(urlEl2) {
                urlEl2.href = card.dataset.url;
                urlEl2.innerText = card.dataset.url;
            }

            const panelCreated = document.getElementById('panel-created');
            if(panelCreated) panelCreated.innerText = card.dataset.created;
            
            const panelUpdated = document.getElementById('panel-updated');
            if(panelUpdated) panelUpdated.innerText = card.dataset.updated;

            openPanel();
            const btnAnalytics = document.getElementById('panel-btn-analytics');
            if(btnAnalytics) {
                // Get the analytics link from the card's actions block
                const analyticsLink = card.querySelector('a[title="Analytics"]');
                if(analyticsLink) btnAnalytics.href = analyticsLink.href;
            }
        });

        // Handle segmented control tabs click on mobile
        document.addEventListener('click', function(e) {
            const tab = e.target.closest('.segment-tab');
            if (tab) {
                e.preventDefault();
                const sortVal = tab.dataset.sort;
                const sortSelect = document.querySelector('select[name="sort"]');
                if (sortSelect) {
                    sortSelect.value = sortVal;
                    // Toggle active classes
                    tab.parentElement.querySelectorAll('.segment-tab').forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    performAjaxSearch();
                }
            }
        });

        // Handle direct edit button click on mobile
        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.sl-btn--edit-direct');
            if (editBtn) {
                e.preventDefault();
                const card = editBtn.closest('.sl-card');
                if (card) {
                    const detailBtn = card.querySelector('.sl-btn--detail');
                    if (detailBtn) {
                        detailBtn.click();
                        setTimeout(() => {
                            toggleSection('edit');
                        }, 50);
                    }
                }
            }
        });

        // Toggle mobile form collapse
        window.toggleMobileForm = function() {
            const formCol = document.querySelector('.mobile-form-collapse');
            if (formCol) {
                formCol.classList.toggle('is-open');
                if (formCol.classList.contains('is-open')) {
                    formCol.scrollIntoView({ behavior: 'smooth' });
                }
            }
        };

        // AJAX Search & Sort with Debounce
        const searchInput = document.querySelector('input[name="search"]');
        const sortSelect = document.querySelector('select[name="sort"]');
        const searchForm = document.getElementById('search-filter-form');
        let debounceTimeout;

        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performAjaxSearch();
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => {
                    performAjaxSearch();
                }, 300);
            });
        }

        // Handle clear search button click
        document.addEventListener('click', function(e) {
            const clearLink = e.target.closest('.fa-times-circle')?.parentElement || e.target.closest('input[name="search"] ~ a');
            if (clearLink && clearLink.closest('div[style*="position: relative"]')) {
                e.preventDefault();
                if (searchInput) searchInput.value = '';
                performAjaxSearch();
            }
        });

        // Intercept pagination links
        document.addEventListener('click', function(e) {
            const pageLink = e.target.closest('.pagination a, .pagination-container a, [style*="margin-top: 20px"] a');
            if (pageLink && pageLink.href) {
                // Check if the link is inside the shortlinks paginator
                const isShortlinkPagination = pageLink.closest('[style*="margin-top: 20px"]');
                if (isShortlinkPagination) {
                    e.preventDefault();
                    fetchAndUpdate(pageLink.href);
                }
            }
        });

        function performAjaxSearch() {
            const searchVal = searchInput ? searchInput.value : '';
            const sortVal = sortSelect ? sortSelect.value : 'newest';
            
            const url = new URL(window.location.origin + window.location.pathname);
            if (searchVal.trim() !== '') {
                url.searchParams.set('search', searchVal);
            }
            if (sortVal !== 'newest') {
                url.searchParams.set('sort', sortVal);
            }
            
            fetchAndUpdate(url.toString());
        }

        function fetchAndUpdate(url) {
            const listContainer = document.querySelector('.engagement-list');
            if (listContainer) listContainer.style.opacity = '0.5';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // 1. Update list content
                const newList = doc.querySelector('.engagement-list');
                const currentList = document.querySelector('.engagement-list');
                if (newList && currentList) {
                    currentList.innerHTML = newList.innerHTML;
                    currentList.style.opacity = '1';
                }

                // 2. Update pagination
                const newPagination = doc.querySelector('[style*="margin-top: 20px"]');
                const currentPagination = document.querySelector('[style*="margin-top: 20px"]');
                if (newPagination && currentPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                } else if (currentPagination) {
                    currentPagination.innerHTML = '';
                }

                // 3. Update result stats
                const newResultsText = doc.querySelector('.card-header div[style*="font-size: 13px"]');
                const currentResultsText = document.querySelector('.card-header div[style*="font-size: 13px"]');
                if (newResultsText && currentResultsText) {
                    currentResultsText.innerHTML = newResultsText.innerHTML;
                }

                const newTotalLinksBox = doc.querySelector('.stat-box:nth-child(2) strong');
                const currentTotalLinksBox = document.querySelector('.stat-box:nth-child(2) strong');
                if (newTotalLinksBox && currentTotalLinksBox) {
                    currentTotalLinksBox.innerHTML = newTotalLinksBox.innerHTML;
                }

                // 4. Update clear search button visibility
                const newSearchWrapper = doc.querySelector('input[name="search"]').parentElement;
                const currentSearchWrapper = document.querySelector('input[name="search"]').parentElement;
                if (newSearchWrapper && currentSearchWrapper) {
                    const clearBtn = currentSearchWrapper.querySelector('a');
                    const newClearBtn = newSearchWrapper.querySelector('a');
                    if (clearBtn && !newClearBtn) {
                        clearBtn.remove();
                    } else if (!clearBtn && newClearBtn) {
                        currentSearchWrapper.appendChild(newClearBtn);
                    }
                }

                // Update active segment tab on mobile if select exists
                if (sortSelect) {
                    const currentSort = sortSelect.value || 'newest';
                    document.querySelectorAll('.segment-tab').forEach(t => {
                        if (t.dataset.sort === currentSort) {
                            t.classList.add('active');
                        } else {
                            t.classList.remove('active');
                        }
                    });
                }

                // 5. Update URL
                history.pushState(null, '', url);
            })
            .catch(err => {
                console.error('Ajax search failed:', err);
                if (listContainer) listContainer.style.opacity = '1';
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("layouts.admin", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/rakanyuka/Documents/PKL/Linkan/resources/views/homeadminS/shortlink/create.blade.php ENDPATH**/ ?>