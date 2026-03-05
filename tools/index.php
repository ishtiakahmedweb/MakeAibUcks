<?php
/**
 * Tools Directory - MakeAIBucks
 */
require_once '../includes/config.php';

$currentCategory = get('category', 'all');
$searchQuery = get('q', '');
$sort = get('sort', 'popular');

// SEO
$SEO_DATA = [
    'title' => 'AI Tools Directory — MakeAIBucks',
    'description' => 'Browse all free AI tools for freelancing, e-commerce, content and more. Find the perfect AI automation for your side hustle.'
];

// Fetch Categories for Sidebar
$categories = getCategories(true);
$totalTools = db()->fetchOne("SELECT COUNT(*) as cnt FROM tools WHERE is_active = 1")['cnt'];

// Build Query
$params = [];
$where = "WHERE is_active = 1";

if ($currentCategory !== 'all') {
    $where .= " AND category_slug = ?";
    $params[] = $currentCategory;
}

if ($searchQuery) {
    $where .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$searchQuery%";
    $params[] = "%$searchQuery%";
}

$orderBy = "uses_count DESC";
if ($sort === 'newest') $orderBy = "created_at DESC";
if ($sort === 'difficulty') $orderBy = "difficulty ASC";

$tools = db()->fetchAll("SELECT * FROM tools $where ORDER BY $orderBy", $params);

include '../includes/header.php';
?>

<div class="flex min-h-screen bg-bg-page">
    <!-- Sidebar -->
    <aside class="w-[260px] bg-bg-sidebar border-r border-slate-800 flex flex-col fixed h-[calc(100vh-60px)] z-40">
        <div class="p-6 overflow-y-auto">
            <div class="mb-8">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Quick Search</p>
                <form action="<?= url('tools') ?>" method="GET" class="relative group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-lg">search</span>
                    <input name="q" value="<?= h($searchQuery) ?>" class="w-full bg-slate-900 border border-slate-800 rounded-xl py-2.5 pl-10 pr-4 text-sm text-slate-300 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all" placeholder="Find a tool..." type="text"/>
                </form>
            </div>
            
            <nav class="space-y-1">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Niches & Categories</p>
                
                <a class="flex items-center justify-between px-4 py-3 rounded-xl border-l-4 transition-all <?= isActive('all', $currentCategory) ?>" href="<?= url('tools') ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">grid_view</span>
                        <span class="text-sm font-bold">All Tools</span>
                    </div>
                    <span class="text-[10px] font-black bg-white/10 px-2 py-0.5 rounded-md"><?= $totalTools ?></span>
                </a>

                <?php foreach ($categories as $cat): ?>
                <a class="flex items-center justify-between px-4 py-3 rounded-xl border-l-4 transition-all <?= isActive($cat['slug'], $currentCategory) ?>" href="<?= url('tools?category=' . $cat['slug']) ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]"><?= $cat['icon'] ?></span>
                        <span class="text-sm font-bold"><?= $cat['name'] ?></span>
                    </div>
                    <span class="text-[10px] font-black bg-white/10 px-2 py-0.5 rounded-md"><?= $cat['tool_count'] ?></span>
                </a>
                <?php endforeach; ?>
            </nav>
        </div>
        
        <div class="mt-auto p-6 border-t border-slate-800 bg-slate-950/50">
            <div class="bg-primary/10 border border-primary/20 rounded-2xl p-4 mb-4">
                <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Weekly Update</p>
                <p class="text-xs text-white font-bold leading-relaxed">3 new tools added to E-Commerce niche.</p>
            </div>
            <a href="<?= url('requests') ?>" class="w-full bg-primary hover:bg-primary-dark text-white font-black py-4 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-lg">add_circle</span>
                REQUEST TOOL
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-[260px]">
        <!-- Hero Header -->
        <header class="relative pt-16 pb-24 px-10 bg-bg-sidebar overflow-hidden">
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#16a34a 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
            
            <div class="relative z-10 max-w-6xl">
                <div class="flex items-center gap-2 text-slate-500 text-xs font-bold uppercase tracking-widest mb-6">
                    <a class="hover:text-primary transition-colors" href="<?= url() ?>">Home</a>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <span class="text-slate-300">Directory</span>
                </div>
                
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-10">
                    <div>
                        <h1 class="text-5xl lg:text-7xl font-black text-white leading-none mb-6 tracking-tighter">
                            AI Tools <span class="income-text">Library</span>
                        </h1>
                        <p class="text-xl text-slate-400 max-w-xl font-medium">Browse our collection of specialized AI generators designed to automate your digital income.</p>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        <div class="bg-white/5 border border-white/10 px-6 py-4 rounded-2xl backdrop-blur-md">
                            <span class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] block mb-1">Total Free Tools</span>
                            <span class="text-white text-2xl font-black"><?= $totalTools ?></span>
                        </div>
                        <div class="bg-white/5 border border-white/10 px-6 py-4 rounded-2xl backdrop-blur-md">
                            <span class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] block mb-1">Categories</span>
                            <span class="text-white text-2xl font-black"><?= count($categories) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="px-10 py-12 max-w-6xl">
            <!-- Toolbar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div class="flex items-center gap-3">
                    <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                        <?= count($tools) ?> Tools Found
                    </span>
                    <?php if ($searchQuery): ?>
                        <span class="text-sm font-bold text-slate-400 italic">"<?= h($searchQuery) ?>"</span>
                        <a href="<?= url('tools') ?>" class="text-slate-500 hover:text-red-500 transition-all"><span class="material-symbols-outlined text-lg">cancel</span></a>
                    <?php endif; ?>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest mr-2">Sort By:</span>
                    <form id="sort-form" class="relative">
                        <input type="hidden" name="category" value="<?= h($currentCategory) ?>">
                        <input type="hidden" name="q" value="<?= h($searchQuery) ?>">
                        <select name="sort" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200 rounded-xl pl-5 pr-12 py-3 text-sm font-bold focus:ring-primary focus:border-primary shadow-sm hover:border-primary transition-all cursor-pointer">
                            <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Most Popular</option>
                            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                            <option value="difficulty" <?= $sort === 'difficulty' ? 'selected' : '' ?>>Difficulty</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">expand_more</span>
                    </form>
                </div>
            </div>

            <!-- Grid -->
            <?php if (empty($tools)): ?>
                <div class="bg-white border border-slate-200 rounded-[40px] p-20 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center text-slate-300 mx-auto mb-8">
                        <span class="material-symbols-outlined text-5xl">search_off</span>
                    </div>
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-4">No tools found</h2>
                    <p class="text-slate-500 mb-10 max-w-md mx-auto">We couldn't find any tools matching your search. Try different keywords or request a new tool.</p>
                    <a href="<?= url('requests') ?>" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-4 rounded-xl font-bold shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                        Request This Tool
                    </a>
                </div>
            <?php else: ?>
                <div class="grid gap-4">
                    <?php foreach ($tools as $tool): ?>
                    <div class="group bg-white hover:bg-slate-50 border border-slate-200 hover:border-primary rounded-[32px] p-6 flex flex-col md:flex-row md:items-center gap-8 transition-all duration-300 hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all shadow-inner">
                            <span class="material-symbols-outlined text-3xl">bolt</span>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-2xl font-black text-slate-900 leading-none"><?= $tool['name'] ?></h3>
                                <div class="bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.15em]"><?= $tool['category_slug'] ?></div>
                            </div>
                            <p class="text-slate-500 font-medium leading-relaxed line-clamp-1"><?= $tool['description'] ?></p>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-8 border-t md:border-t-0 pt-6 md:pt-0">
                            <div class="flex flex-col items-center">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Uses</span>
                                <span class="text-base font-black text-slate-900"><?= formatNumber($tool['uses_count']) ?></span>
                            </div>
                            
                            <div class="flex flex-col items-center">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Difficulty</span>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?= $tool['difficulty'] === 'Beginner' ? 'bg-green-100 text-green-700' : ($tool['difficulty'] === 'Intermediate' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?>">
                                    <?= $tool['difficulty'] ?>
                                </span>
                            </div>
                            
                            <a href="<?= url('tool/' . $tool['slug']) ?>" class="bg-slate-900 hover:bg-primary text-white px-8 py-4 rounded-2xl font-black text-sm transition-all group-hover:shadow-xl group-hover:shadow-primary/20 flex items-center gap-2">
                                OPEN TOOL <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-all">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Newsletter Box -->
            <div class="mt-20 bg-gradient-to-br from-green-950 to-green-900 rounded-[40px] p-12 flex flex-col md:flex-row items-center gap-10 reveal">
                <div class="w-20 h-20 rounded-3xl bg-white/10 flex items-center justify-center text-white shrink-0 backdrop-blur-md">
                    <span class="material-symbols-outlined text-4xl">lightbulb</span>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-3xl font-extrabold text-white mb-2 tracking-tight">Missing a specific tool?</h3>
                    <p class="text-green-100/60 font-medium max-w-md">Our lab is always open. Request an AI automation and we'll build it for the community.</p>
                </div>
                <a href="<?= url('requests') ?>" class="bg-white text-green-950 font-black px-10 py-5 rounded-2xl hover:scale-105 transition-all shadow-2xl">
                    SUBMIT REQUEST
                </a>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
