<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>منصة آجل | نظام تقسيط عصري</title>
    <meta name="description" content="منصة آجل لتنظيم مكاتب التقسيط والمهلة. إدارة الفروع، المستثمرين، المصروفات، العمولات، والعمليات بسهولة. تشمل أيضًا ميزة قائمة المتعثرين للحد من المخاطر المالية.">
    <meta name="keywords" content="آجل, تقسيط, مكاتب التقسيط, بيع بالآجل, منصة تقسيط, إدارة مالية, موظفين, فروع, خطة اشتراك, قائمة المتعثرين">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
            color: #2f3e46;
        }
        header {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 20px;
        }
        header img {
            height: 50px;
            margin-left: 20px;
        }
        nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
        nav a {
            text-decoration: none;
            color: #2f3e46;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        nav a:hover {
            background-color: #00b7c3;
            color: white;
        }
        .menu-toggle {
            display: none;
            font-size: 26px;
            background: none;
            border: none;
            color: #2f3e46;
        }
        @media (max-width: 768px) {
            nav {
                display: none;
                flex-direction: column;
                gap: 10px;
                background: #fff;
                position: absolute;
                top: 70px;
                right: 20px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                padding: 15px;
                border-radius: 8px;
            }
            nav.active { display: flex; }
            .menu-toggle { display: block; position: absolute; left: 20px; top: 20px; }
        }
        .hero {
            background: linear-gradient(rgba(0, 183, 195, 0.8), rgba(0, 183, 195, 0.8)), url('https://images.unsplash.com/photo-1605902711622-cfb43c4437d5?auto=format&fit=crop&w=1470&q=80') center/cover no-repeat;
            color: white;
            text-align: center;
            padding: 100px 20px;
        }
        .hero h1 {
            font-size: 40px;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 18px;
            max-width: 800px;
            margin: auto;
            line-height: 1.8;
        }
        .features-section {
            padding: 40px 20px;
            background-color: #fff;
        }
        .features-section h2 {
            text-align: center;
            font-size: 28px;
            color: #00b7c3;
            margin-bottom: 30px;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: auto;
        }
        .feature-box {
            background-color: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .feature-box h3 {
            color: #00b7c3;
            margin-bottom: 10px;
            font-size: 20px;
        }
        .feature-box p {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }
        .feature-delinquents {
            background-color: #e3f6f8;
            padding: 40px 20px;
            margin: 40px auto;
            border-radius: 16px;
            max-width: 1000px;
            text-align: center;
        }
        .feature-delinquents h2 {
            color: #00b7c3;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .feature-delinquents p {
            font-size: 18px;
            line-height: 1.8;
            color: #333;
        }
        .toggle-plan {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 40px auto 20px;
            gap: 10px;
        }
        .toggle-plan button {
            padding: 10px 20px;
            border: 1px solid #00b7c3;
            background-color: white;
            color: #00b7c3;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        .toggle-plan button.active {
            background-color: #00b7c3;
            color: white;
        }
        .plans-section {
            padding: 60px 20px 20px;
            background-color: #fff;
        }
        .plans-section h2 {
            text-align: center;
            font-size: 28px;
            color: #00b7c3;
            margin-bottom: 10px;
        }
        .plans {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto 60px;
        }
        .plan-box {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            padding: 20px;
            width: 260px;
            text-align: center;
        }
        .plan-box h3 {
            color: #00b7c3;
        }
        .plan-box ul {
            list-style: none;
            padding: 0;
            font-size: 14px;
            line-height: 1.8;
            margin: 20px 0;
        }
        .plan-box a {
            display: inline-block;
            background-color: #00b7c3;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
        }
        footer {
            background-color: #ffffff;
            border-top: 1px solid #ddd;
            padding: 40px 20px;
            text-align: center;
        }
        .footer-container {
            max-width: 1000px;
            margin: auto;
        }
        .footer-info {
            font-size: 14px;
            color: #444;
            line-height: 1.8;
        }
        .rights {
            margin-top: 10px;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>
<body>
<header>
    <img src="{{asset('assets/intro/logo.jpeg')}}" alt="شعار آجل" href="#">
    <button class="menu-toggle" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </button>
    <nav id="main-nav">
        <a href="#features">المميزات</a>
        <a href="#plans">الاشتراكات</a>
        <a href="#about">من نحن</a>
        <a href="#contact">اتصل بنا</a>
        <a href="{{url('/partner')}}">تسجيل الدخول</a>
        <a href="{{url('/register')}}">تسجيل حساب جديد</a>
    </nav>
</header>

<section class="hero">
    <h1>منصة آجل لتنظيم مكاتب المهله والتقسيط </h1>
    <p>نظام متكامل يساعدك في إدارة الفروع، المستثمرين، الموظفين، العمليات، المصروفات، العمولات، المخزون، والمتعثرين — كل ذلك من مكان واحد وباحترافية عالية.</p>
</section>

<section class="features-section" id="features">
    <h2>المميزات</h2>
    <div class="features">
        <div class="feature-box">
            <h3>📍 إدارة الفروع والمستثمرين</h3>
            <p>تحكم كامل في إضافة وتعديل الفروع والمستثمرين بشكل مرن.</p>
        </div>
        <div class="feature-box">
            <h3>💼 تنظيم مالي شامل</h3>
            <p>إدارة دقيقة للمصروفات، العمولات، والتحصيلات من مكان واحد.</p>
        </div>
        <div class="feature-box">
            <h3>💳 عمليات تقسيط منظمة</h3>
            <p>أرشفة العمليات وربطها بالعملاء والفروع والمستثمرين بسهولة.</p>
        </div>
        <div class="feature-box">
            <h3>⚙️ إدارة مخزون المستثمرين</h3>
            <p>ربط مباشر بين المخزون والعمليات لتتبع الأصول.</p>
        </div>
        <div class="feature-box">
            <h3>👥 إدارة الموظفين والصلاحيات</h3>
            <p>إدارة صلاحيات المستخدمين والحد من الأخطاء البشرية.</p>
        </div>
        <div class="feature-box">
            <h3>📊 تقارير وتحليلات تفصيلية</h3>
            <p>عرض بيانات الأداء لكل فرع أو مستثمر لاتخاذ قرارات دقيقة.</p>
        </div>
    </div>
</section>

<section class="feature-delinquents">
    <h2>📋 قائمة المتعثرين</h2>
    <p>
        تتيح المنصة ميزة التحقق من العميل قبل تنفيذ أي طلب تقسيط عبر البحث برقم الهوية. في حال وجود تعثر سابق في مكتب آخر، أو وجود طلب قائم حالياً، يتم تنبيه المستخدم فوراً، مما يساعد على تقليل المخاطر الائتمانية.
    </p>
</section>

<section class="plans-section" id="plans">
    <h2>الاشتراكات</h2>
    <div class="toggle-plan">
        <button class="active" onclick="togglePricing('monthly', this)">شهري</button>
        <button onclick="togglePricing('yearly', this)">سنوي</button>
    </div>
    <div class="plans" id="plans-container"></div>
</section>

<footer id="contact">
    <div class="footer-container">
        <div class="footer-info">
            <strong>من نحن:</strong> مؤسسة إعادة لتقنية نظم المعلومات - المملكة العربية السعودية - تبوك - حي الربوة<br>
            <strong>اتصل بنا:</strong> <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="bfdedbd2d6d1ffded5d5dad391d1dacb">[email&#160;protected]</a> - 0592229224
        </div>
        <div class="rights">جميع الحقوق محفوظة © 2025 منصة آجل</div>
    </div>
</footer>

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
    const nav = document.getElementById("main-nav");
    function toggleMenu() { nav.classList.toggle("active"); }
    document.addEventListener("click", function (event) {
        const isClickInsideNav = nav.contains(event.target);
        const isToggle = event.target.closest(".menu-toggle");
        if (!isClickInsideNav && !isToggle) nav.classList.remove("active");
    });
    nav.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", () => nav.classList.remove("active"));
    });

    const pricingPlans = {
        monthly: [
            { name: "الخطة الأساسية", price: "149 ريال / شهر", features: ["300 عملية", "2 موظف", "فرع واحد", "مستثمر واحد"] },
            { name: "الخطة المتقدمة", price: "299 ريال / شهر", features: ["1000 عملية", "5 موظفين", "3 فروع", "3 مستثمرين"] },
            { name: "الخطة الاحترافية", price: "499 ريال / شهر", features: ["عمليات غير محدودة", "10 موظفين", "5 فروع", "10 مستثمرين"] },
        ],
        yearly: [
            { name: "الخطة الأساسية", price: "1290 ريال / سنة (خصم 28%)", features: ["300 عملية", "2 موظف", "فرع واحد", "مستثمر واحد"] },
            { name: "الخطة المتقدمة", price: "2490 ريال / سنة (خصم 30%)", features: ["1000 عملية", "5 موظفين", "3 فروع", "3 مستثمرين"] },
            { name: "الخطة الاحترافية", price: "3390 ريال / سنة (خصم 33%)", features: ["عمليات غير محدودة", "10 موظفين", "5 فروع", "10 مستثمرين"] },
        ]
    };

    function renderPlans(type) {
        const container = document.getElementById("plans-container");
        container.innerHTML = "";
        pricingPlans[type].forEach(plan => {
            const box = document.createElement("div");
            box.className = "plan-box";
            box.innerHTML = `
          <h3>${plan.name}</h3>
          <p><strong>${plan.price}</strong></p>
          <ul>
            ${plan.features.map(f => `<li>${f}</li>`).join("")}
          </ul>
          <a href="#register">اشترك الآن</a>
        `;
            container.appendChild(box);
        });
    }

    function togglePricing(type, btn) {
        document.querySelectorAll(".toggle-plan button").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        renderPlans(type);
    }

    renderPlans("monthly");
</script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"94f7db6abb1d0d53","version":"2025.6.2","r":1,"token":"1c3c32a4da6845fc8249fafd5cf59799","serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}}}' crossorigin="anonymous"></script>
</body>
</html>


