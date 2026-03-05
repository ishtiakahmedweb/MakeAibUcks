<?php
/**
 * About Page - MakeAIBucks
 */
require_once '../includes/config.php';

$SEO_DATA = [
    'title' => 'About MakeAIBucks — Our Mission',
    'description' => 'Learn how MakeAIBucks helps digital entrepreneurs leverage AI to build income-generating assets for free.'
];

include '../includes/header.php';
?>

<div class="bg-bg-page min-h-screen">
    <!-- Hero Header -->
    <header class="hero-gradient pt-24 pb-32 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tighter">Our <span class="income-text">Mission</span></h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto font-medium">To democratize access to high-end AI tools, helping anyone in the world build digital wealth without technical barriers or upfront costs.</p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 -mt-16 relative z-10 pb-24">
        <div class="bg-white rounded-[40px] p-10 md:p-16 shadow-2xl space-y-12">
            <section class="space-y-6">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">The "Frictionless" Vision</h2>
                <div class="prose prose-slate prose-lg max-w-none text-slate-600 font-medium leading-relaxed">
                    <p>Most AI platforms today are designed for software engineers or corporations with deep pockets. They require complex prompts, expensive subscriptions, and steep learning curves.</p>
                    <p><strong>MakeAIBucks is different.</strong> We believe the power of AI should be in the hands of the freelancer, the side-hustler, and the creative entrepreneur. We build single-purpose, highly-optimized AI generators that do one thing perfectly: help you make digital bucks.</p>
                </div>
            </section>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-slate-50 p-8 rounded-[32px] border border-slate-100">
                    <h3 class="text-xl font-black text-slate-900 mb-4 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">volunteer_activism</span>
                        Zero Cost
                    </h3>
                    <p class="text-sm text-slate-500 font-medium">No credit cards. No "credits." No tiers. Every tool on MakeAIBucks is 100% free to use, supported solely by non-intrusive advertisements.</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-[32px] border border-slate-100">
                    <h3 class="text-xl font-black text-slate-900 mb-4 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">psychology</span>
                        Expert Prompted
                    </h3>
                    <p class="text-sm text-slate-500 font-medium">You don't need to be a "prompt enthusiast." We've already spent thousands of hours refining the instructions behind every tool to ensure professional results every time.</p>
                </div>
            </div>

            <section class="space-y-6">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">How we keep the lights on</h2>
                <div class="prose prose-slate prose-lg max-w-none text-slate-600 font-medium leading-relaxed">
                    <p>We believe in transparency. We pay for the expensive AI server costs through Google AdSense and occasional context-relevant affiliate links (like hosting for your new AI-built website).</p>
                    <p>This allows us to keep the tools free for you, the user, forever.</p>
                </div>
            </section>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
