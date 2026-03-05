/**
 * MakeAIBucks - Global JavaScript
 */

// 1. Mobile Navigation Toggle
function toggleMobileNav() {
    const nav = document.getElementById('mobile-nav');
    if (nav) {
        nav.classList.toggle('hidden');
    }
}

// 2. Announcement Bar Dismissal
function dismissAnnouncement() {
    const bar = document.getElementById('announcement-bar');
    if (bar) {
        bar.style.display = 'none';
        localStorage.setItem('announcement_dismissed', 'true');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Check if announcement was previously dismissed
    if (localStorage.getItem('announcement_dismissed') === 'true') {
        const bar = document.getElementById('announcement-bar');
        if (bar) bar.style.display = 'none';
    }

    // 3. Scroll Reveal Animation
    const reveals = document.querySelectorAll('.reveal');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    reveals.forEach(el => revealObserver.observe(el));

    // 4. Newsletter AJAX Submission
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = newsletterForm.querySelector('input[name="email"]').value;
            const msgDiv = document.getElementById('newsletter-msg');
            const submitBtn = newsletterForm.querySelector('button');

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'JOINING...';

            try {
                const response = await fetch('api/subscribe.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `email=${encodeURIComponent(email)}`
                });
                const result = await response.json();

                if (result.success) {
                    msgDiv.innerHTML = '<span class="text-white">✓ Welcome to the list! Check your inbox soon.</span>';
                    newsletterForm.reset();
                } else {
                    msgDiv.innerHTML = `<span class="text-red-400">! ${result.message}</span>`;
                }
            } catch (error) {
                msgDiv.innerHTML = '<span class="text-red-400">! Connection error. Please try again.</span>';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'JOIN FREE';
            }
        });
    }
});

// 5. Toast Notification System
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-6 right-6 px-6 py-4 rounded-xl shadow-2xl z-[100] transform transition-all translate-y-20 flex items-center gap-3 font-bold text-sm ${type === 'success' ? 'bg-primary text-white' : 'bg-red-600 text-white'
        }`;

    const icon = type === 'success' ? 'check_circle' : 'error';
    toast.innerHTML = `
        <span class="material-symbols-outlined">${icon}</span>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);

    // Trigger animation
    setTimeout(() => {
        toast.classList.remove('translate-y-20');
    }, 100);

    // Auto-remove
    setTimeout(() => {
        toast.classList.add('translate-y-20');
        setTimeout(() => toast.remove(), 500);
    }, 4000);
}

// 6. Bookmark System
async function toggleBookmark(slug) {
    const btn = document.getElementById('bookmark-btn');
    if (!btn) return;

    try {
        const formData = new FormData();
        formData.append('tool_slug', slug);

        const response = await fetch('/makeaibucks/api/bookmark.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.success) {
            const icon = btn.querySelector('.material-symbols-outlined');
            if (result.data.bookmarked) {
                btn.classList.add('text-primary', 'border-primary');
                icon.style.fontVariationSettings = "'FILL' 1";
            } else {
                btn.classList.remove('text-primary', 'border-primary');
                icon.style.fontVariationSettings = "'FILL' 0";
            }
            showToast(result.message);
        } else {
            showToast(result.message, 'error');
        }
    } catch (e) {
        showToast('Error managing bookmark.', 'error');
    }
}
