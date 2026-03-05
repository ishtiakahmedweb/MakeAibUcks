<?php
/**
 * Homepage - MakeAIBucks
 */
require_once 'includes/config.php';

// SEO Settings
$SEO_DATA = [
    'title' => 'Free AI Tools to Make Money Online — MakeAIBucks',
    'description' => 'Browse 24+ free AI tools designed to help freelancers, Etsy sellers, YouTubers and side hustlers earn more. No signup required. Instant results.'
];

// Fetch Featured Tools
$featuredTools = db()->fetchAll("SELECT * FROM tools WHERE is_featured = 1 AND is_active = 1 ORDER BY uses_count DESC LIMIT 6");

// Fetch Latest Results
$latestResults = db()->fetchAll("
    SELECT r.*, t.name as tool_name, c.color as category_color 
    FROM results r 
    JOIN tools t ON r.tool_slug = t.slug 
    JOIN categories c ON t.category_slug = c.slug 
    WHERE r.is_public = 1 
    ORDER BY r.created_at DESC 
    LIMIT 6
");

// Stats for hero
$toolCount = db()->fetchOne("SELECT COUNT(*) as cnt FROM tools WHERE is_active = 1")['cnt'];
$resultCount = db()->fetchOne("SELECT COUNT(*) as cnt FROM results")['cnt'];

include 'includes/header.php';
?>

<!-- 3. Hero Section -->
<section class="hero-gradient relative pt-24 pb-32 px-6 overflow-hidden">
    <div class="max-w-5xl mx-auto text-center relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 rounded-full px-4 py-1.5 mb-8 backdrop-blur-sm">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
            </span>
            <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">Top-rated AI tools for freelancers</span>
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 leading-[1.1]">
            Turn AI Into Your <br/><span class="income-text">Income Machine</span>
        </h1>
        <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto mb-12">
            The largest directory of purpose-built AI tools designed specifically to help you earn online. Fast, free, and no account required.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-14">
            <a href="<?= url('tools') ?>" class="w-full sm:w-auto bg-primary text-white px-10 py-5 rounded-xl font-bold text-lg shadow-xl shadow-primary/20 hover:shadow-primary/40 transition-all hover:-translate-y-1 flex items-center justify-center gap-2 group">
                Start Generating Free <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
            <a href="<?= url('about') ?>" class="w-full sm:w-auto bg-white/5 text-white px-10 py-5 rounded-xl font-bold text-lg hover:bg-white/10 transition-all border border-white/10 flex items-center justify-center">
                How It Works
            </a>
        </div>
        
        <!-- Glass Search Bar -->
        <div class="max-w-2xl mx-auto mb-10 reveal">
            <form action="<?= url('tools') ?>" method="GET" class="glass-search rounded-2xl p-2.5 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-500 ml-3">search</span>
                <input name="q" class="bg-transparent border-none focus:ring-0 text-white w-full placeholder:text-slate-500 py-3 text-lg" placeholder="Search for tools like 'Fiverr' or 'YouTube'..." type="text"/>
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-xl font-bold transition-all">Search</button>
            </form>
        </div>
        
        <!-- Popular Tags -->
        <div class="flex flex-wrap justify-center gap-2 mb-20 reveal">
            <span class="text-slate-500 text-sm font-medium mr-2 self-center">Popular:</span>
            <a href="<?= url('tools?q=fiverr') ?>" class="bg-white/5 border border-white/10 rounded-full px-5 py-2 text-xs font-semibold text-slate-300 hover:bg-primary/20 hover:text-white cursor-pointer transition-colors">Fiverr Gig Writer</a>
            <a href="<?= url('tools?q=tiktok') ?>" class="bg-white/5 border border-white/10 rounded-full px-5 py-2 text-xs font-semibold text-slate-300 hover:bg-primary/20 hover:text-white cursor-pointer transition-colors">TikTok Hook</a>
            <a href="<?= url('tools?q=etsy') ?>" class="bg-white/5 border border-white/10 rounded-full px-5 py-2 text-xs font-semibold text-slate-300 hover:bg-primary/20 hover:text-white cursor-pointer transition-colors">Etsy Tags</a>
            <a href="<?= url('tools?q=blog') ?>" class="bg-white/5 border border-white/10 rounded-full px-5 py-2 text-xs font-semibold text-slate-300 hover:bg-primary/20 hover:text-white cursor-pointer transition-colors">Blog Writer</a>
        </div>
        
        <!-- Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto border-t border-white/5 pt-12 reveal">
            <div>
                <div class="text-4xl font-extrabold text-white"><?= $toolCount ?>+</div>
                <div class="text-[10px] uppercase tracking-[0.2em] text-slate-500 font-black mt-2">Free AI Tools</div>
            </div>
            <div>
                <div class="text-4xl font-extrabold text-white">100%</div>
                <div class="text-[10px] uppercase tracking-[0.2em] text-slate-500 font-black mt-2">Free Forever</div>
            </div>
            <div>
                <div class="text-4xl font-extrabold text-white"><?= formatNumber($resultCount) ?></div>
                <div class="text-[10px] uppercase tracking-[0.2em] text-slate-500 font-black mt-2">Results Created</div>
            </div>
            <div>
                <div class="text-4xl font-extrabold text-white">24/7</div>
                <div class="text-[10px] uppercase tracking-[0.2em] text-slate-500 font-black mt-2">AI Support</div>
            </div>
        </div>
    </div>
    
    <!-- Abstract Background Decorative Elements -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] -mr-64 -mt-64"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] -ml-64 -mb-64"></div>
</section>

<!-- 4. Tools Grid Section -->
<section class="bg-white py-24 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-6">
            <div class="max-w-2xl">
                <div class="inline-block bg-primary/10 text-primary px-4 py-1 rounded-full text-xs font-bold mb-4 uppercase tracking-wider">AI Tool Directory</div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight">Featured tools for your next side hustle</h2>
            </div>
            <a href="<?= url('tools') ?>" class="group flex items-center gap-2 text-primary font-bold hover:text-primary-dark transition-all">
                View all tools <span class="material-symbols-outlined group-hover:translate-x-1 transition-all">arrow_forward</span>
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <?php foreach ($featuredTools as $tool): 
                $catColor = '#16a34a'; // Default
                // We'll fetch category color or use a helper later
            ?>
            <div class="group bg-white p-8 rounded-3xl border border-slate-200 hover:border-primary hover:shadow-2xl hover:shadow-primary/10 transition-all hover:-translate-y-2">
                <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-8 group-hover:scale-110 transition-all">
                    <span class="material-symbols-outlined text-3xl">auto_awesome</span>
                </div>
                <div class="inline-block bg-green-50 text-primary border border-green-100 rounded-full text-[10px] font-bold px-3 py-1 mb-4 uppercase tracking-wider"><?= ucfirst($tool['category_slug']) ?></div>
                <h3 class="text-2xl font-extrabold mb-4 text-slate-900"><?= $tool['name'] ?></h3>
                <p class="text-slate-500 text-sm mb-8 leading-relaxed">
                    <?= truncate($tool['description'], 120) ?>
                </p>
                <a href="<?= url('tool/' . $tool['slug']) ?>" class="w-full bg-slate-50 group-hover:bg-primary group-hover:text-white py-4 rounded-xl flex items-center justify-center gap-2 font-bold transition-all">
                    Use Tool Free <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-all">bolt</span>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 5. How It Works -->
<section class="bg-green-50 py-24 px-6 border-y border-green-100">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-4xl md:text-5xl font-extrabold text-center mb-24 text-slate-900">Start Earning in 3 Simple Steps</h2>
        <div class="grid md:grid-cols-3 gap-12 relative">
            <!-- Connector Lines (Desktop) -->
            <div class="hidden md:block absolute top-1/2 left-[20%] right-[20%] h-[1px] bg-green-200 -z-0"></div>
            
            <div class="relative bg-white p-12 rounded-[40px] shadow-sm hover:shadow-xl transition-all reveal">
                <div class="absolute -top-10 left-12 w-20 h-20 bg-primary text-white text-4xl font-extrabold flex items-center justify-center rounded-3xl shadow-xl shadow-primary/20">01</div>
                <h3 class="text-2xl font-extrabold mb-6 mt-6">Choose Your Tool</h3>
                <p class="text-slate-500 leading-relaxed">Select from our library of 20+ specialized AI tools built for different high-demand side hustles.</p>
            </div>

            <div class="relative bg-white p-12 rounded-[40px] shadow-sm hover:shadow-xl transition-all reveal" style="transition-delay: 100ms;">
                <div class="absolute -top-10 left-12 w-20 h-20 bg-primary text-white text-4xl font-extrabold flex items-center justify-center rounded-3xl shadow-xl shadow-primary/20">02</div>
                <h3 class="text-2xl font-extrabold mb-6 mt-6">Generate Content</h3>
                <p class="text-slate-500 leading-relaxed">Enter your requirements and let our fine-tuned AI engine create high-quality outputs instantly.</p>
            </div>

            <div class="relative bg-white p-12 rounded-[40px] shadow-sm hover:shadow-xl transition-all reveal" style="transition-delay: 200ms;">
                <div class="absolute -top-10 left-12 w-20 h-20 bg-primary text-white text-4xl font-extrabold flex items-center justify-center rounded-3xl shadow-xl shadow-primary/20">03</div>
                <h3 class="text-2xl font-extrabold mb-6 mt-6">Deliver & Get Paid</h3>
                <p class="text-slate-500 leading-relaxed">Use the generated assets to scale your freelance services, content creation, or e-commerce store.</p>
            </div>
        </div>
    </div>
</section>

<!-- 6. Live Activity Feed -->
<section class="bg-bg-sidebar py-24 px-6 overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-16">
            <div>
                <h2 class="text-4xl font-extrabold text-white mb-4">Happening right now</h2>
                <p class="text-slate-500">Real-time usage across our global marketplace</p>
            </div>
            <div class="flex items-center gap-3 bg-primary/10 border border-primary/20 px-6 py-3 rounded-full text-primary font-black uppercase tracking-widest text-xs">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                </span>
                LIVE ACTIVITY
            </div>
        </div>
        
        <div class="grid md:grid-cols-3 gap-6">
            <?php if (empty($latestResults)): ?>
                <!-- Placeholders -->
                <?php for($i=1; $i<=3; $i++): ?>
                <div class="bg-white/5 border border-white/10 p-6 rounded-2xl flex items-start gap-4 backdrop-blur-sm">
                    <div class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center text-slate-600">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <div>
                        <p class="text-slate-200 text-sm leading-relaxed">
                            <span class="font-bold">Someone</span> just used the <span class="text-primary font-bold">AI Tool</span>
                        </p>
                        <p class="text-slate-500 text-[10px] uppercase font-black tracking-widest mt-2">Recently</p>
                    </div>
                </div>
                <?php endfor; ?>
            <?php else: ?>
                <?php foreach ($latestResults as $res): ?>
                <a href="<?= url('result/' . $res['slug']) ?>" class="bg-white/5 border border-white/10 p-6 rounded-2xl flex items-start gap-4 backdrop-blur-sm hover:bg-white/10 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center text-primary group-hover:scale-110 transition-all">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                    <div>
                        <p class="text-slate-200 text-sm leading-relaxed">
                            <span class="font-bold">Visitor</span> generated <span class="text-primary font-bold"><?= $res['tool_name'] ?></span>
                        </p>
                        <p class="text-slate-500 text-[10px] uppercase font-black tracking-widest mt-2"><?= timeAgo($res['created_at']) ?></p>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 7. Trust Strip -->
<div class="bg-accent-yellow py-8 overflow-hidden border-y border-amber-200">
    <div class="max-w-7xl mx-auto px-6 flex flex-wrap justify-center md:justify-between gap-12 items-center font-black text-[10px] uppercase tracking-[0.2em] text-slate-800">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary text-xl">verified_user</span>
            <span>No account required</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary text-xl">payments</span>
            <span>Free forever</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary text-xl">flash_on</span>
            <span>Instant delivery</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary text-xl">lock</span>
            <span>Private & Secure</span>
        </div>
    </div>
</div>

<!-- 8. Newsletter Section -->
<section class="py-24 px-6 bg-white">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-br from-green-950 via-green-900 to-primary rounded-[50px] p-12 md:p-20 text-center shadow-3xl relative overflow-hidden">
            <!-- Decoration -->
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-primary/20 rounded-full blur-[100px]"></div>
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-white/5 rounded-full blur-[80px]"></div>
            
            <div class="relative z-10">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-white mx-auto mb-8 backdrop-blur-md">
                    <span class="material-symbols-outlined text-3xl">mail</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Master the art of AI income</h2>
                <p class="text-green-100/70 mb-12 text-lg max-w-lg mx-auto leading-relaxed">Join 15,000+ creators getting curated AI prompts and money-making secrets every single week.</p>
                
                <form id="newsletter-form" class="flex flex-col md:flex-row gap-3 max-w-lg mx-auto">
                    <input name="email" class="flex-1 px-7 py-5 rounded-2xl border-none focus:ring-2 focus:ring-primary bg-white/10 backdrop-blur-md text-white placeholder:text-green-300/50 text-base" placeholder="Enter your best email..." type="email" required/>
                    <button type="submit" class="bg-white text-green-950 font-black px-10 py-5 rounded-2xl hover:bg-green-50 transition-all hover:scale-105 active:scale-95 shadow-2xl">
                        JOIN FREE
                    </button>
                </form>
                <div id="newsletter-msg" class="mt-4 text-sm font-bold"></div>
                <p class="text-green-900/40 text-[10px] uppercase font-black tracking-widest mt-8">Zero spam. High value only. One-click unsubscribe.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
