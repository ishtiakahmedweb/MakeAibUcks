<?php
/**
 * SEO Manager Class
 */

class SEO {
    public static function tags($data = []) {
        $title = $data['title'] ?? SITE_NAME . ' — Build Your Income with AI';
        $desc = $data['description'] ?? 'Browse free AI tools designed to help freelancers, creators, and entrepreneurs grow their online income. Zero cost, instant results.';
        $canonical = $data['canonical'] ?? self::getCanonical();
        $noindex = isset($data['noindex']) && $data['noindex'];
        $image = SITE_URL . ($data['image'] ?? '/assets/img/og-default.jpg');

        $html = "<title>$title</title>\n";
        $html .= "    <meta name=\"description\" content=\"$desc\">\n";
        $html .= "    <meta name=\"robots\" content=\"" . ($noindex ? 'noindex, nofollow' : 'index, follow') . "\">\n";
        $html .= "    <link rel=\"canonical\" href=\"$canonical\">\n\n";

        // Open Graph
        $html .= "    <meta property=\"og:title\" content=\"$title\">\n";
        $html .= "    <meta property=\"og:description\" content=\"$desc\">\n";
        $html .= "    <meta property=\"og:url\" content=\"$canonical\">\n";
        $html .= "    <meta property=\"og:image\" content=\"$image\">\n";
        $html .= "    <meta property=\"og:type\" content=\"website\">\n\n";

        // Twitter Card
        $html .= "    <meta name=\"twitter:card\" content=\"summary_large_image\">\n";
        $html .= "    <meta name=\"twitter:title\" content=\"$title\">\n";
        $html .= "    <meta name=\"twitter:description\" content=\"$desc\">\n";
        $html .= "    <meta name=\"twitter:image\" content=\"$image\">\n";

        return $html;
    }

    public static function schema($type, $data = []) {
        // Simplified JSON-LD generator
        return "<script type=\"application/ld+json\">\n" . json_encode($data, JSON_PRETTY_PRINT) . "\n</script>";
    }

    private static function getCanonical() {
        return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}
