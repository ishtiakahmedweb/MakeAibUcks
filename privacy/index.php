<?php
/**
 * Privacy Policy - MakeAIBucks
 */
require_once '../includes/config.php';

$SEO_DATA = [
    'title' => 'Privacy Policy — MakeAIBucks',
    'description' => 'How we handle your data and generated content.'
];

include '../includes/header.php';
?>

<div class="bg-bg-page min-h-screen py-24">
    <main class="max-w-4xl mx-auto px-6">
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 mb-12 tracking-tighter">Privacy Policy</h1>
        
        <div class="bg-white rounded-[40px] p-10 md:p-16 shadow-sm border border-slate-200">
            <div class="prose prose-slate prose-lg max-w-none text-slate-600 font-medium leading-relaxed space-y-10">
                <section>
                    <h2 class="text-2xl font-black text-slate-900 mb-4">1. Data Collection</h2>
                    <p>We don't require sign-ups. We collect minimal data including your IP address for rate limiting and basic usage diagnostics. When you use an AI tool, your inputs are processed to generate results and may be cached temporarily for performance.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-black text-slate-900 mb-4">2. Generated Content</h2>
                    <p>Content generated on MakeAIBucks may be saved publicly with a unique URL. You are responsible for the content you generate and publish. While we don't private your results by default, we do not associate them with your identity.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-black text-slate-900 mb-4">3. Third-Parties</h2>
                    <p>We use third-party AI providers (Google Gemini, Groq) to process your requests. We also use Google AdSense for advertising and Google Analytics to monitor traffic. These services may use cookies according to their own privacy policies.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-black text-slate-900 mb-4">4. Contact</h2>
                    <p>For any privacy concerns or request to delete a specific generated result, please contact us at <?= ADMIN_EMAIL ?>.</p>
                </section>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>
