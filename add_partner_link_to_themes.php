<?php
/**
 * Script to add Partner Login link to all theme headers
 * Run once: php add_partner_link_to_themes.php
 */

$themes = ['bold_blue', 'darkgray', 'material_pink', 'shadow_white', 'yellow'];
$base_path = __DIR__ . '/application/views/themes/';

$partner_link = <<<HTML
    <!-- Partner Portal Login Link -->
    <li>
        <a href="<?php echo base_url('partnerportal/login'); ?>">
            <i class="fa fa-handshake-o"></i> Partner Login
        </a>
    </li>
HTML;

foreach ($themes as $theme) {
    $file = $base_path . $theme . '/header.php';

    if (file_exists($file)) {
        $content = file_get_contents($file);

        // Prevent duplicate
        if (strpos($content, 'Partner Login') !== false) {
            echo "✓ $theme/header.php already has Partner Login link\n";
            continue;
        }

        // Regex: match </ul> (with optional whitespace/comments)
        $pattern = '/<\/ul>\s*<\/div[^>]*>\s*<!--.*?navbar-collapse.*?-->/is';

        if (preg_match($pattern, $content)) {
            $new_content = preg_replace(
                '/(<\/ul>)/i',
                $partner_link . "\n$1",
                $content,
                1
            );

            file_put_contents($file, $new_content);
            echo "✓ Added Partner Login link to $theme/header.php\n";
        } else {
            echo "✗ Could not update $theme/header.php (no matching </ul> found)\n";
        }
    } else {
        echo "✗ File not found: $file\n";
    }
}

echo "\n✅ Done! Partner Login link added to all possible themes.\n";
