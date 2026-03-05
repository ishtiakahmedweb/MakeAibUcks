<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <?php 
    // SEO implementation
    $seoData = isset($SEO_DATA) ? $SEO_DATA : [];
    echo SEO::tags($seoData);
    ?>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary":          "#16a34a",
                        "primary-dark":     "#15803d",
                        "primary-light":    "#dcfce7",
                        "bg-page":          "#f6f8f7",
                        "bg-sidebar":       "#0b1120",
                        "bg-hero":          "#020d05",
                        "accent-yellow":    "#fef3c7",
                    },
                    fontFamily: {
                        "display": ["Syne", "sans-serif"],
                        "body":    ["DM Sans", "sans-serif"],
                        "mono":    ["JetBrains Mono", "monospace"],
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg:      "0.5rem",
                        xl:      "0.75rem",
                        full:    "9999px",
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #f6f8f7; }
        h1, h2, h3, h4, .font-display { font-family: 'Syne', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #020d05 0%, #0c2810 100%); }
        .income-text {
            background: linear-gradient(to right, #22c55e, #16a34a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass-search {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .reveal { opacity: 0; transform: translateY(14px); transition: all 0.6s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }
    </style>

    <!-- Google Analytics Placeholder -->
    <?php if ($gaId = getSetting('analytics_id')): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $gaId ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?= $gaId ?>');
    </script>
    <?php endif; ?>

    <!-- AdSense Auto-Ads Placeholder -->
    <?php if ($adsCode = getSetting('adsense_code')): ?>
    <?= $adsCode ?>
    <?php endif; ?>
</head>
<body class="bg-bg-page text-slate-900">

<!-- 1. Announcement Bar -->
<div id="announcement-bar" class="w-full bg-gradient-to-r from-green-900 to-primary py-2 px-4 text-center relative">
    <p class="text-white text-sm font-medium tracking-wide">
        ⚡ 20+ free AI tools for freelancers and makers — no signup needed
    </p>
    <button onclick="dismissAnnouncement()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/60 hover:text-white">
        <span class="material-symbols-outlined text-sm">close</span>
    </button>
</div>

<!-- 2. Navbar -->
<nav class="sticky top-0 z-50 h-[60px] w-full bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-6 lg:px-20">
    <div class="flex items-center gap-8">
        <a href="<?= url() ?>" class="flex items-center gap-2">
            <span class="text-2xl font-extrabold tracking-tighter text-slate-900">make<span class="text-primary">ai</span>bucks</span>
        </a>
        <div class="hidden md:flex items-center gap-6">
            <a class="text-sm font-semibold hover:text-primary transition-colors" href="<?= url('tools') ?>">Tools</a>
            <a class="text-sm font-semibold hover:text-primary transition-colors" href="<?= url('categories') ?>">Categories</a>
            <a class="text-sm font-semibold hover:text-primary transition-colors" href="<?= url('my-activity') ?>">My Activity</a>
            <a class="text-sm font-semibold hover:text-primary transition-colors" href="<?= url('requests') ?>">Requests</a>
            <a class="text-sm font-semibold hover:text-primary transition-colors" href="<?= url('about') ?>">How It Works</a>
        </div>
    </div>
    <div class="flex items-center gap-4">
        <button class="text-slate-600 hover:text-primary">
            <span class="material-symbols-outlined">search</span>
        </button>
        <a href="<?= url('tools') ?>" class="bg-primary hover:bg-primary-dark text-white px-5 py-2.5 rounded-lg text-sm font-bold transition-all transform hover:-translate-y-0.5 shadow-md shadow-primary/20">
            Get Started Free
        </a>
    </div>
</nav>

<main>
