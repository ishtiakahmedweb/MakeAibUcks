<?php
/**
 * Categories Index - MakeAIBucks
 */
require_once '../includes/config.php';

// SEO
$SEO_DATA = [
    'title' => 'Browse AI Tools by Category — MakeAIBucks',
    'description' => 'Explore specialized AI income generators by niche: Freelancing, E-Commerce, Content Creation, and more.'
];

$categories = getCategories(true);

include '../includes/header.php';
?>

<div class="bg-bg-page min-h-screen">
    <!-- Hero Section -->
    <header class="hero-gradient relative pt-24 pb-32 px-6 overflow-hidden">
        <div class="max-w-5xl mx-auto text-center relative z-10">
            <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 leading-tight">
                Browse by <span class="income-text">Income Stream</span>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto">
                Find the perfect AI tools designed for your specific business model. Organized by high-demand digital niches.
            </p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full blur-[120px] -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary/10 rounded-full blur-[120px] -ml-48 -mb-48"></div>
    </header>

    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20 pb-24">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($categories as $cat): ?>
            <a href="<?= url('tools?category=' . $cat['slug']) ?>" class="group bg-white p-10 rounded-[40px] border border-slate-200 shadow-sm hover:shadow-2xl hover:shadow-primary/10 hover:-translate-y-2 transition-all flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-3xl mb-8 flex items-center justify-center text-white scale-110 shadow-lg" style="background: <?= $cat['gradient'] ?>;">
                    <span class="material-symbols-outlined text-4xl leading-none"><?= $cat['icon'] ?></span>
                </div>
                
                <h2 class="text-3xl font-extrabold text-slate-900 mb-4"><?= $cat['name'] ?></h2>
                <p class="text-slate-500 font-medium leading-relaxed mb-8 flex-1">
                    <?= $cat['description'] ?>
                </p>
                
                <div class="flex items-center gap-2 text-primary font-black uppercase tracking-widest text-xs">
                    View <?= $cat['tool_count'] ?> active tools
                    <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-all">arrow_forward</span>
                </div>
            </a>
            <?php endforeach; ?>
            
            <!-- Request Card -->
            <a href="<?= url('requests') ?>" class="group bg-slate-900 p-10 rounded-[40px] border border-slate-800 shadow-sm hover:-translate-y-2 transition-all flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-3xl mb-8 flex items-center justify-center text-white bg-white/10 backdrop-blur-md">
                    <span class="material-symbols-outlined text-4xl">add_circle</span>
                </div>
                
                <h2 class="text-3xl font-extrabold text-white mb-4">Request New</h2>
                <p class="text-slate-400 font-medium leading-relaxed mb-8">
                    Don't see your niche here? Request a specific income category and we'll build it.
                </p>
                
                <div class="flex items-center gap-2 text-primary font-black uppercase tracking-widest text-xs">
                    Submit a Request
                    <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-all">arrow_forward</span>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
