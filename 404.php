<?php
/**
 * 404 Error Page - MakeAIBucks
 */
require_once 'includes/config.php';

$SEO_DATA = [
    'title' => 'Page Not Found — MakeAIBucks',
    'description' => 'The page you are looking for has been moved or deleted.'
];

http_response_code(404);
include 'includes/header.php';
?>

<div class="bg-[#020d05] min-h-screen flex items-center justify-center p-6 -mt-[60px]">
    <div class="max-w-2xl w-full text-center">
        <div class="relative mb-12">
            <h1 class="text-[12rem] md:text-[20rem] font-black text-white/5 leading-none tracking-tighter">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 bg-primary rounded-[32px] flex items-center justify-center text-white scale-125 shadow-2xl shadow-primary/40">
                    <span class="material-symbols-outlined text-5xl">warning</span>
                </div>
            </div>
        </div>
        
        <h2 class="text-3xl md:text-5xl font-black text-white mb-6">Lost in the <span class="income-text">Aether?</span></h2>
        <p class="text-slate-400 text-lg font-medium mb-12 max-w-md mx-auto">This tool or result might have been moved or the URL is incorrect. Let's get you back to earning.</p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <a href="<?= url() ?>" class="w-full sm:w-auto bg-primary text-white px-12 py-5 rounded-2xl font-black text-sm hover:scale-105 transition-all">
                RETURN TO HOMEPAGE
            </a>
            <a href="<?= url('tools') ?>" class="w-full sm:w-auto bg-white/5 border border-white/10 text-white px-12 py-5 rounded-2xl font-black text-sm hover:bg-white/10 transition-all">
                BROWSE ALL TOOLS
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
