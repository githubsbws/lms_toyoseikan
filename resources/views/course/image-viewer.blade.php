<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ดูรูปภาพ</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            background: #2c2c2c; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            min-height: 100vh;
        }

        #toolbar {
            width: 100%; 
            background: #1a1a1a; 
            padding: 12px 20px;
            display: flex; 
            align-items: center; 
            gap: 15px;
            position: sticky; 
            top: 0; 
            z-index: 10; 
            color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        #toolbar span {
            font-size: 16px;
            font-weight: 500;
        }

        #image-container { 
            padding: 40px 20px; 
            width: 100%; 
            max-width: 1400px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }

        #main-image {
            max-width: 100%;
            max-height: calc(100vh - 100px);
            height: auto;
            display: block;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
            border-radius: 8px;
            /* ป้องกัน right-click */
            -webkit-user-select: none;
            user-select: none;
            pointer-events: none;
        }

        .zoom-controls {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .zoom-btn {
            background: #444;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s;
        }

        .zoom-btn:hover {
            background: #666;
        }

        #zoom-level {
            color: #ccc;
            min-width: 60px;
            text-align: center;
        }
    </style>
</head>
<body>

{{-- Toolbar --}}
<div id="toolbar">
    <span>{{ $fileName ?? 'รูปภาพ' }}</span>
    
    <div class="zoom-controls" style="margin-left: auto;">
        <button class="zoom-btn" onclick="zoomOut()">−</button>
        <span id="zoom-level">100%</span>
        <button class="zoom-btn" onclick="zoomIn()">+</button>
        <button class="zoom-btn" onclick="resetZoom()">รีเซ็ต</button>
    </div>
</div>

<div id="image-container">
    <img id="main-image" src="{{ $imageUrl }}" alt="{{ $fileName ?? 'รูปภาพ' }}">
</div>

<script>
    // ปิด right-click
    document.addEventListener('contextmenu', e => e.preventDefault());

    let currentZoom = 1;
    const img = document.getElementById('main-image');
    const zoomLevel = document.getElementById('zoom-level');

    function zoomIn() {
        currentZoom += 0.1;
        if (currentZoom > 3) currentZoom = 3; // จำกัดไม่เกิน 300%
        updateZoom();
    }

    function zoomOut() {
        currentZoom -= 0.1;
        if (currentZoom < 0.5) currentZoom = 0.5; // จำกัดไม่ต่ำกว่า 50%
        updateZoom();
    }

    function resetZoom() {
        currentZoom = 1;
        updateZoom();
    }

    function updateZoom() {
        img.style.transform = `scale(${currentZoom})`;
        img.style.transition = 'transform 0.2s';
        zoomLevel.textContent = Math.round(currentZoom * 100) + '%';
    }

    // ใช้ Mouse Wheel เพื่อ Zoom (Optional)
    img.addEventListener('wheel', function(e) {
        e.preventDefault();
        if (e.deltaY < 0) {
            zoomIn();
        } else {
            zoomOut();
        }
    });
</script>
</body>
</html>
