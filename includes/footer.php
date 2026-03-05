</main>

<!-- 9. Footer -->
<footer class="bg-[#020617] text-slate-400 py-20 px-6">
    <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-12">
        <div>
            <h4 class="text-white font-extrabold text-xl mb-6">make<span class="text-primary">ai</span>bucks</h4>
            <p class="text-sm leading-relaxed mb-6">Empowering the next generation of freelancers with professional-grade AI tools that actually generate income.</p>
            <div class="flex gap-4">
                <a class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary transition-colors text-white" href="#">
                    <span class="material-symbols-outlined text-base">person</span>
                </a>
                <a class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary transition-colors text-white" href="#">
                    <span class="material-symbols-outlined text-base">forum</span>
                </a>
            </div>
        </div>
        <div>
            <h5 class="text-white font-bold mb-6">Popular Tools</h5>
            <ul class="space-y-4 text-sm">
                <li><a class="hover:text-primary transition-colors" href="<?= url('tool/fiverr-gig-writer') ?>">Fiverr Gig Writer</a></li>
                <li><a class="hover:text-primary transition-colors" href="<?= url('tool/youtube-script-hook') ?>">YouTube Script Hook</a></li>
                <li><a class="hover:text-primary transition-colors" href="<?= url('tool/affiliate-blog-post') ?>">Affiliate Blog Post</a></li>
                <li><a class="hover:text-primary transition-colors" href="<?= url('tool/ai-side-hustle-finder') ?>">AI Side Hustle Finder</a></li>
            </ul>
        </div>
        <div>
            <h5 class="text-white font-bold mb-6">Platform</h5>
            <ul class="space-y-4 text-sm">
                <li><a class="hover:text-primary transition-colors" href="<?= url('tools') ?>">All Tools</a></li>
                <li><a class="hover:text-primary transition-colors" href="<?= url('categories') ?>">Categories</a></li>
                <li><a class="hover:text-primary transition-colors" href="<?= url('my-activity') ?>">My Activity</a></li>
                <li><a class="hover:text-primary transition-colors" href="<?= url('requests') ?>">Request a Tool</a></li>
            </ul>
        </div>
        <div>
            <h5 class="text-white font-bold mb-6">Company</h5>
            <ul class="space-y-4 text-sm">
                <li><a class="hover:text-primary transition-colors" href="<?= url('about') ?>">About Us</a></li>
                <li><a class="hover:text-primary transition-colors" href="<?= url('contact') ?>">Contact Support</a></li>
                <li><a class="hover:text-primary transition-colors" href="<?= url('privacy') ?>">Privacy Policy</a></li>
                <li><a class="hover:text-primary transition-colors" href="<?= url('terms') ?>">Terms of Service</a></li>
            </ul>
        </div>
    </div>
    <div class="max-w-7xl mx-auto border-t border-slate-800 mt-20 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
        <p>© <?= date('Y') ?> makeaibucks. All rights reserved.</p>
        <p class="flex items-center gap-1">Made with <span class="material-symbols-outlined text-red-500 text-xs">favorite</span> for the creator economy.</p>
    </div>
</footer>

<script src="<?= url('assets/js/main.js') ?>"></script>
</body>
</html>
