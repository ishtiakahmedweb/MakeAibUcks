<?php
/**
 * Contact Page - MakeAIBucks
 */
require_once '../includes/config.php';

$SEO_DATA = [
    'title' => 'Contact Us — MakeAIBucks Support',
    'description' => 'Got questions or feedback? Reach out to the MakeAIBucks team.'
];

include '../includes/header.php';
?>

<div class="bg-bg-page min-h-screen py-24">
    <main class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 mb-6 tracking-tighter">Get in <span class="income-text">Touch</span></h1>
            <p class="text-slate-500 text-lg font-medium">We're here to help you scale your AI-powered income.</p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-12">
            <div class="bg-white rounded-[40px] p-10 shadow-sm border border-slate-200">
                <h2 class="text-2xl font-black text-slate-900 mb-8">Send a Message</h2>
                <form class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-2">Your Name</label>
                        <input type="text" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 font-bold focus:ring-2 focus:ring-primary outline-none" placeholder="John Doe">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-2">Email Address</label>
                        <input type="email" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 font-bold focus:ring-2 focus:ring-primary outline-none" placeholder="john@example.com">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-2">Message</label>
                        <textarea rows="4" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 font-bold focus:ring-2 focus:ring-primary outline-none" placeholder="How can we help?"></textarea>
                    </div>
                    <button type="button" onclick="showToast('Message feature is a placeholder. Please email us directly!')" class="w-full bg-primary text-white py-5 rounded-2xl font-black text-sm hover:scale-105 transition-all shadow-xl shadow-primary/20">
                        SEND MESSAGE
                    </button>
                </form>
            </div>
            
            <div class="space-y-8">
                <div class="bg-slate-900 rounded-[40px] p-10 text-white">
                    <h3 class="text-xl font-black mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">mail</span>
                        Direct Email
                    </h3>
                    <p class="text-slate-400 font-medium mb-4">For partnerships, technical support, or tool removal requests:</p>
                    <a href="mailto:<?= ADMIN_EMAIL ?>" class="text-xl font-black text-primary hover:underline"><?= ADMIN_EMAIL ?></a>
                </div>
                
                <div class="bg-white rounded-[40px] p-10 border border-slate-200">
                    <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">forum</span>
                        Community
                    </h3>
                    <p class="text-slate-500 font-medium leading-relaxed">Join our roadmap discussions and vote for new features on our <a href="<?= url('requests') ?>" class="text-primary font-bold hover:underline">Requests Page</a>.</p>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
