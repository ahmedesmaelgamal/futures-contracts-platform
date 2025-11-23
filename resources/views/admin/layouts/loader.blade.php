{{-- HTML + Blade --}}
<style>
    .Global-Loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(255, 255, 255, 0.8); /* خلفية شفافة */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loader-img {
        width: 150px;
        height: auto;
        animation: spin 1.2s linear infinite;
    }

    @keyframes spin {
        0%   { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Animation for fade in/out (اختياري) */
    .Global-Loader.fade-out {
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease;
    }
</style>

<div class="Global-Loader" id="global-loader">
    <img src="{{ getFile(isset($setting) ? getFile(getAuthSetting('loader')) : null) }}"
         class="loader-img"
         alt="Loader">
</div>

<script>
    // عند انتهاء تحميل الصفحة، يتم إخفاء اللودر
    window.addEventListener('load', function () {
        const loader = document.getElementById('global-loader');
        loader.classList.add('fade-out');
    });

    // دوال لإظهار وإخفاء اللودر يدويًا
    function showLoader() {
        const loader = document.getElementById('global-loader');
        loader.classList.remove('fade-out');
        loader.style.display = 'flex';
    }

    function hideLoader() {
        const loader = document.getElementById('global-loader');
        loader.classList.add('fade-out');
    }
</script>
