<?php
/**
 * Individual Tool Page - MakeAIBucks
 */
require_once '../includes/config.php';

$slug = get('slug');

if (!$slug) {
    redirect(url('tools'));
}

$db = db();
$tool = $db->fetchOne("SELECT * FROM tools WHERE slug = ? AND is_active = 1", [$slug]);

if (!$tool) {
    redirect(url('tools'));
}

// SEO
$SEO_DATA = [
    'title' => $tool['name'] . ' — Free AI Tool',
    'description' => $tool['description']
];

$fields = json_decode($tool['fields_json'], true) ?: [];
$features = json_decode($tool['features_json'], true) ?: [];

// Fetch similar tools
$similarTools = $db->fetchAll("
    SELECT * FROM tools 
    WHERE category_slug = ? AND slug != ? AND is_active = 1 
    ORDER BY uses_count DESC LIMIT 3
", [$tool['category_slug'], $slug]);

include '../includes/header.php';
?>

<div class="bg-bg-page min-h-screen">
    <main class="max-w-7xl mx-auto px-6 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-500 mb-10">
            <a class="hover:text-primary transition-colors" href="<?= url() ?>">Home</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a class="hover:text-primary transition-colors" href="<?= url('tools') ?>">Tools</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-slate-900"><?= h($tool['name']) ?></span>
        </nav>

        <!-- Page Header -->
        <div class="mb-12">
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 mb-6 tracking-tighter"><?= h($tool['name']) ?></h1>
            <p class="text-xl text-slate-500 max-w-3xl font-medium leading-relaxed"><?= h($tool['description']) ?></p>
        </div>

        <!-- Main Content Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left Column: Tool Interface -->
            <div class="lg:col-span-8 space-y-12">
                <!-- Input Form Card -->
                <div class="bg-white border border-slate-200 rounded-[40px] p-8 md:p-12 shadow-sm">
                    <h2 class="text-2xl font-extrabold text-slate-900 mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">edit_note</span>
                        Configure Your Tool
                    </h2>
                    
                    <form id="ai-tool-form" class="space-y-8">
                        <input type="hidden" name="tool_slug" value="<?= h($slug) ?>">
                        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <?php foreach ($fields as $field): ?>
                            <div class="space-y-3 <?= ($field['type'] === 'textarea') ? 'md:col-span-2' : '' ?>">
                                <label class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                    <?= h($field['label']) ?>
                                    <?php if ($field['required']): ?><span class="text-red-500">*</span><?php endif; ?>
                                </label>
                                
                                <?php if ($field['type'] === 'text'): ?>
                                    <input name="<?= h($field['name']) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-900 focus:ring-2 focus:ring-primary focus:border-primary transition-all font-medium" placeholder="<?= h($field['placeholder'] ?? '') ?>" <?= $field['required'] ? 'required' : '' ?> type="text"/>
                                
                                <?php elseif ($field['type'] === 'textarea'): ?>
                                    <textarea name="<?= h($field['name']) ?>" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-900 focus:ring-2 focus:ring-primary focus:border-primary transition-all font-medium" placeholder="<?= h($field['placeholder'] ?? '') ?>" <?= $field['required'] ? 'required' : '' ?>></textarea>
                                
                                <?php elseif ($field['type'] === 'select'): ?>
                                    <div class="relative">
                                        <select name="<?= h($field['name']) ?>" class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-900 focus:ring-2 focus:ring-primary focus:border-primary transition-all font-medium cursor-pointer">
                                            <?php foreach ($field['options'] as $option): ?>
                                                <option value="<?= h($option) ?>"><?= h($option) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">expand_more</span>
                                    </div>

                                <?php elseif ($field['type'] === 'radio'): ?>
                                    <div class="flex flex-wrap gap-6 pt-2">
                                        <?php foreach ($field['options'] as $index => $option): ?>
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input name="<?= h($field['name']) ?>" value="<?= h($option) ?>" <?= $index === 0 ? 'checked' : '' ?> class="w-5 h-5 text-primary focus:ring-primary border-slate-300 transition-all" type="radio"/>
                                            <span class="text-sm font-bold text-slate-600 group-hover:text-slate-900 transition-colors"><?= h($option) ?></span>
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="submit" id="generate-btn" class="flex-1 bg-primary hover:bg-primary-dark text-white py-6 rounded-2xl font-black text-xl flex items-center justify-center gap-3 shadow-2xl shadow-primary/20 transition-all hover:scale-[1.02] active:scale-95">
                                <span class="material-symbols-outlined">bolt</span>
                                GENERATE NOW
                            </button>
                            <button type="button" onclick="toggleBookmark('<?= h($slug) ?>')" id="bookmark-btn" class="bg-white border border-slate-200 text-slate-400 hover:text-primary hover:border-primary px-8 rounded-2xl transition-all flex items-center justify-center group">
                                <span class="material-symbols-outlined group-hover:fill-1 transition-all">bookmark</span>
                            </button>
                        </div>
                    </form>
                </div>

                <?= getAdPlaceholder('horizontal') ?>

                <!-- Result Container (Hidden until generated) -->
                <div id="result-container" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <div class="bg-bg-sidebar border border-white/10 rounded-[40px] overflow-hidden shadow-2xl">
                        <div class="bg-white/5 px-8 py-6 flex flex-wrap items-center justify-between gap-4 border-b border-white/10">
                            <div class="flex items-center gap-4">
                                <span class="bg-primary text-white text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1.5 rounded-lg shadow-lg shadow-primary/20">AI Generated Output</span>
                                <h3 class="font-extrabold text-white text-lg">Results</h3>
                            </div>
                            <div class="flex items-center gap-3">
                                <button onclick="copyResult()" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-5 py-2.5 rounded-xl text-xs font-black transition-all">
                                    <span class="material-symbols-outlined text-sm">content_copy</span> COPY
                                </button>
                                <a id="view-result-btn" href="#" class="flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-5 py-2.5 rounded-xl text-xs font-black transition-all">
                                    <span class="material-symbols-outlined text-sm">visibility</span> PUBLIC URL
                                </a>
                            </div>
                        </div>
                        <div id="ai-output" class="p-8 md:p-12 text-slate-300 leading-relaxed text-lg whitespace-pre-wrap font-medium font-body selection:bg-primary selection:text-white">
                            <!-- AI Content will inject here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="lg:col-span-4 space-y-8">
                <!-- How to Use Card -->
                <?php if ($tool['how_to_use']): ?>
                <div class="bg-white border border-slate-200 rounded-[32px] p-8 shadow-sm">
                    <h3 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">info</span>
                        How to use
                    </h3>
                    <div class="prose prose-slate prose-sm max-w-none text-slate-500 font-medium">
                        <?= nl2br(h($tool['how_to_use'])) ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Features Card -->
                <?php if (!empty($features)): ?>
                <div class="bg-green-50 border border-green-100 rounded-[32px] p-8">
                    <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">stars</span>
                        Tool Features
                    </h3>
                    <ul class="space-y-4">
                        <?php foreach ($features as $feature): ?>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-primary text-lg mt-0.5">check_circle</span>
                            <span class="text-sm font-bold text-slate-700"><?= h($feature) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Pro Tip Card -->
                <?php if ($tool['tip_text']): ?>
                <div class="bg-primary rounded-[32px] p-8 text-white relative overflow-hidden group shadow-xl">
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                            <span class="material-symbols-outlined text-2xl">verified</span>
                        </div>
                        <h3 class="text-xl font-black mb-4">Pro Seller Tip</h3>
                        <p class="text-white/80 font-medium leading-relaxed mb-0 italic">
                            "<?= h($tool['tip_text']) ?>"
                        </p>
                    </div>
                    <span class="material-symbols-outlined absolute -bottom-10 -right-10 text-white/10 text-[200px] rotate-12 group-hover:rotate-0 transition-transform duration-700 pointer-events-none">lightbulb</span>
                </div>
                <?php endif; ?>

                <!-- Similar Tools Card -->
                <?php if (!empty($similarTools)): ?>
                <div class="bg-white border border-slate-200 rounded-[32px] p-8">
                    <h3 class="text-xl font-black text-slate-900 mb-8 mt-2">More Tools for Makers</h3>
                    <div class="space-y-4">
                        <?php foreach ($similarTools as $st): ?>
                        <a class="flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-all group" href="<?= url('tool/' . $st['slug']) ?>">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-primary group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all shadow-inner">
                                <span class="material-symbols-outlined text-2xl">bolt</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-slate-900 mb-1"><?= h($st['name']) ?></h4>
                                <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest"><?= h($st['difficulty']) ?></p>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script>
// Logic specific to Tool Page
document.addEventListener('DOMContentLoaded', () => {
    const toolForm = document.getElementById('ai-tool-form');
    if (toolForm) {
        toolForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const btn = document.getElementById('generate-btn');
            const outputContainer = document.getElementById('result-container');
            const outputBox = document.getElementById('ai-output');
            const publicBtn = document.getElementById('view-result-btn');
            
            // Loading State
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> GENERATING...';
            outputContainer.classList.add('hidden');
            
            try {
                const formData = new FormData(toolForm);
                const response = await fetch('<?= url('api/generate.php') ?>', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    outputBox.innerHTML = result.data.output;
                    publicBtn.href = '<?= url('result/') ?>' + result.data.result_slug;
                    outputContainer.classList.remove('hidden');
                    window.scrollTo({ top: outputContainer.offsetTop - 100, behavior: 'smooth' });
                    showToast('AI content generated successfully!');
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('A connection error occurred.', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span class="material-symbols-outlined">bolt</span> GENERATE NOW';
            }
        });
    }
});

function copyResult() {
    const text = document.getElementById('ai-output').innerText;
    navigator.clipboard.writeText(text).then(() => {
        showToast('Copied to clipboard!');
    });
}
</script>

<?php include '../includes/footer.php'; ?>
