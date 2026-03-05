<?php
/**
 * Database Installation Script
 * Run this ONCE and DELETE immediately after.
 */

require_once __DIR__ . '/../includes/config.php';

// Temporarily override error reporting for setup
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = db();

try {
    // 0. Drop existing tables for a clean start
    $tables = ['bookmarks', 'settings', 'tool_requests', 'subscribers', 'generations', 'api_cache', 'rate_limits', 'comments', 'results', 'tools', 'categories'];
    foreach ($tables as $table) {
        $db->query("DROP TABLE IF EXISTS $table");
    }
    echo "Existing tables dropped.<br>";

    // 1. Create Tables
    $queries = [
        "CREATE TABLE IF NOT EXISTS categories (
          id          INT AUTO_INCREMENT PRIMARY KEY,
          name        VARCHAR(100) NOT NULL,
          slug        VARCHAR(100) NOT NULL UNIQUE,
          description TEXT,
          icon        VARCHAR(50) DEFAULT 'category',
          color       VARCHAR(20) DEFAULT '#16a34a',
          gradient    VARCHAR(200),
          sort_order  INT DEFAULT 0
        )",
        "CREATE TABLE IF NOT EXISTS tools (
          id              INT AUTO_INCREMENT PRIMARY KEY,
          name            VARCHAR(200) NOT NULL,
          slug            VARCHAR(200) NOT NULL UNIQUE,
          description     TEXT NOT NULL,
          category_slug   VARCHAR(100) NOT NULL,
          system_prompt   LONGTEXT NOT NULL,
          fields_json     LONGTEXT NOT NULL,
          features_json   TEXT,
          how_to_use      TEXT,
          tip_text        TEXT,
          uses_count      INT DEFAULT 0,
          difficulty      ENUM('Beginner','Intermediate','Advanced') DEFAULT 'Beginner',
          is_featured     TINYINT DEFAULT 0,
          is_new          TINYINT DEFAULT 0,
          is_active       TINYINT DEFAULT 1,
          sort_order      INT DEFAULT 0,
          created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          INDEX idx_slug (slug),
          INDEX idx_category (category_slug),
          INDEX idx_active_featured (is_active, is_featured)
        )",
        "CREATE TABLE IF NOT EXISTS results (
          id            INT AUTO_INCREMENT PRIMARY KEY,
          tool_id       INT NOT NULL,
          tool_slug     VARCHAR(200) NOT NULL,
          slug          VARCHAR(250) NOT NULL UNIQUE,
          inputs_json   LONGTEXT NOT NULL,
          output_text   LONGTEXT NOT NULL,
          page_title    VARCHAR(250) NOT NULL,
          is_public     TINYINT DEFAULT 1,
          view_count    INT DEFAULT 0,
          share_count   INT DEFAULT 0,
          ip_address    VARCHAR(45),
          session_id    VARCHAR(64),
          delete_token  VARCHAR(64) NOT NULL,
          expires_at    TIMESTAMP NULL,
          created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          INDEX idx_tool_slug (tool_slug),
          INDEX idx_public (is_public),
          INDEX idx_session (session_id),
          INDEX idx_created (created_at)
        )",
        "CREATE TABLE IF NOT EXISTS comments (
          id          INT AUTO_INCREMENT PRIMARY KEY,
          result_id   INT NOT NULL,
          name        VARCHAR(100) NOT NULL,
          content     TEXT NOT NULL,
          ip_address  VARCHAR(45),
          is_approved TINYINT DEFAULT 1,
          created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          INDEX idx_result (result_id)
        )",
        "CREATE TABLE IF NOT EXISTS rate_limits (
          id          INT AUTO_INCREMENT PRIMARY KEY,
          ip_address  VARCHAR(45) NOT NULL,
          action_type VARCHAR(50) DEFAULT 'generate',
          created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          INDEX idx_ip_action_time (ip_address, action_type, created_at)
        )",
        "CREATE TABLE IF NOT EXISTS api_cache (
          id            INT AUTO_INCREMENT PRIMARY KEY,
          cache_key     VARCHAR(64) NOT NULL UNIQUE,
          tool_slug     VARCHAR(200),
          response_text LONGTEXT NOT NULL,
          created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          INDEX idx_key (cache_key)
        )",
        "CREATE TABLE IF NOT EXISTS generations (
          id          INT AUTO_INCREMENT PRIMARY KEY,
          tool_slug   VARCHAR(200),
          ip_address  VARCHAR(45),
          api_type    ENUM('gemini','groq','javascript','cache') DEFAULT 'gemini',
          created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          INDEX idx_created (created_at),
          INDEX idx_tool (tool_slug)
        )",
        "CREATE TABLE IF NOT EXISTS subscribers (
          id          INT AUTO_INCREMENT PRIMARY KEY,
          email       VARCHAR(255) NOT NULL UNIQUE,
          ip_address  VARCHAR(45),
          is_active   TINYINT DEFAULT 1,
          created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS tool_requests (
          id          INT AUTO_INCREMENT PRIMARY KEY,
          name        VARCHAR(200) NOT NULL,
          category    VARCHAR(100),
          description TEXT,
          use_case    TEXT,
          email       VARCHAR(255),
          votes       INT DEFAULT 1,
          status      ENUM('pending','reviewing','building','launched') DEFAULT 'pending',
          ip_address  VARCHAR(45),
          created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS settings (
          id            INT AUTO_INCREMENT PRIMARY KEY,
          setting_key   VARCHAR(100) NOT NULL UNIQUE,
          setting_value TEXT NOT NULL,
          updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS bookmarks (
          id         INT AUTO_INCREMENT PRIMARY KEY,
          session_id VARCHAR(64) NOT NULL,
          tool_slug  VARCHAR(200) NOT NULL,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          UNIQUE KEY unique_bookmark (session_id, tool_slug)
        )"
    ];

    foreach ($queries as $q) {
        $db->query($q);
    }
    echo "Tables created successfully!<br>";

    // 2. Insert Seed Data (Categories)
    $categories = [
        ['Freelancing', 'freelancing', 'Tools for Fiverr, Upwork, cold outreach and freelance business management.', 'work', '#3b82f6', 'linear-gradient(90deg,#1e40af,#3b82f6)', 1],
        ['E-Commerce', 'ecommerce', 'Tools for Etsy, Amazon, Shopify, dropshipping and print-on-demand sellers.', 'shopping_bag', '#f59e0b', 'linear-gradient(90deg,#92400e,#d97706)', 2],
        ['Content Creation', 'content', 'Tools for YouTube, blogs, newsletters, Twitter threads and Instagram.', 'play_circle', '#8b5cf6', 'linear-gradient(90deg,#5b21b6,#7c3aed)', 3],
        ['Business Growth', 'business', 'Tools for naming, planning and finding AI-powered side hustles.', 'trending_up', '#14b8a6', 'linear-gradient(90deg,#134e4a,#0d9488)', 4],
        ['Prompt Engineering', 'prompts', 'Tools for building powerful AI prompts and creating sellable prompt packs.', 'auto_awesome', '#16a34a', 'linear-gradient(90deg,#14532d,#16a34a)', 5]
    ];

    foreach ($categories as $cat) {
        $db->query("INSERT IGNORE INTO categories (name, slug, description, icon, color, gradient, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)", $cat);
    }
    echo "Categories seeded!<br>";

    // 3. Insert Default Settings
    $settings = [
        ['gemini_api_key', ''],
        ['groq_api_key', ''],
        ['admin_password', password_hash('changeme123', PASSWORD_DEFAULT)], // Default admin pass
        ['site_name', 'MakeAIBucks'],
        ['adsense_code', ''],
        ['analytics_id', ''],
        ['maintenance_mode', '0'],
        ['rate_limit_hour', '10'],
        ['rate_limit_day', '30']
    ];

    foreach ($settings as $setting) {
        $db->query("INSERT IGNORE INTO settings (setting_key, setting_value) VALUES (?, ?)", $setting);
    }
    echo "Settings seeded!<br>";

    // 4. Insert One Tool (Fiverr Gig Writer)
    $toolData = [
        'name' => 'Fiverr Gig Writer',
        'slug' => 'fiverr-gig-writer',
        'description' => 'Create high-converting Fiverr gig titles, descriptions, FAQs and tags that rank higher in Fiverr search and attract more buyers.',
        'category_slug' => 'freelancing',
        'system_prompt' => "You are an expert Fiverr gig copywriter with 5+ years experience helping freelancers earn more. Create a complete, professional Fiverr gig based on the user inputs. Structure your response with these exact sections:\n\nGIG TITLE:\n(Write a compelling title, max 80 characters, keyword-rich, specific)\n\nGIG DESCRIPTION:\n(Write 400-500 words. Start with the buyer problem, show you understand it. Present your solution. List what they get. Add social proof language. End with a clear call to action. Use short paragraphs, no walls of text.)\n\nFREQUENTLY ASKED QUESTIONS:\nQ1: [Common question]\nA1: [Helpful answer]\n(Write 5 Q&A pairs that address common buyer concerns)\n\nRECOMMENDED TAGS:\n(List 5 highly searched Fiverr tags, comma separated)\n\nBe professional, specific, and persuasive. Write in the tone specified by the user.",
        'fields_json' => json_encode([
            ["name"=>"service","label"=>"What service do you offer?","type"=>"text","required"=>true,"placeholder"=>"e.g. Logo design, Video editing, Blog writing"],
            ["name"=>"target_client","label"=>"Who is your ideal client?","type"=>"text","required"=>false,"placeholder"=>"e.g. Small businesses, YouTubers, Real estate agents"],
            ["name"=>"experience","label"=>"Years of experience","type"=>"select","options"=>["Less than 1 year","1-3 years","3-5 years","5+ years"],"required"=>true],
            ["name"=>"unique_value","label"=>"What makes you different from others?","type"=>"textarea","required"=>false,"placeholder"=>"e.g. 24hr delivery, unlimited revisions, native English"],
            ["name"=>"tone","label"=>"Tone","type"=>"radio","options"=>["Professional","Friendly","Bold","Conversational"],"required"=>true]
        ]),
        'features_json' => json_encode(["Gig Title","Full Description","5 FAQs","Search Tags"]),
        'how_to_use' => "Copy the GIG TITLE to your Fiverr title field. Paste the GIG DESCRIPTION into the description editor. Add all 5 Q&As to your FAQ section. Use the recommended tags in your gig tags field.",
        'tip_text' => "Share your result page URL in your Fiverr profile bio as a portfolio sample. Buyers love seeing examples of your work.",
        'difficulty' => 'Beginner',
        'is_featured' => 1,
        'uses_count' => 18200
    ];

    $db->insert('tools', $toolData);
    echo "Fiverr Gig Writer tool seeded!<br>";
    echo "<strong>SETUP COMPLETE. DELETE THIS FILE NOW.</strong>";

} catch (Exception $e) {
    die("Setup failed: " . $e->getMessage());
}
?>
