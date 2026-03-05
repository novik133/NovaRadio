<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Page;
use App\Models\TeamMember;
use App\Models\ScheduleShow;
use App\Models\Setting;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Event;
use App\Models\DjProfile;
use App\Models\Media;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@novikradio.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Settings with admin and legal info
        $settings = [
            ['key' => 'site_name', 'value' => 'NovaRadio', 'type' => 'string', 'group' => 'general', 'label' => 'Site Name'],
            ['key' => 'site_tagline', 'value' => 'Your Soundtrack to Life', 'type' => 'string', 'group' => 'general', 'label' => 'Site Tagline'],
            ['key' => 'site_description', 'value' => 'Listen to the best internet radio. Live streaming music 24/7.', 'type' => 'string', 'group' => 'general', 'label' => 'Site Description'],
            ['key' => 'admin_email', 'value' => 'admin@novikradio.com', 'type' => 'string', 'group' => 'legal', 'label' => 'Administrator Email'],
            ['key' => 'admin_name', 'value' => 'NovaRadio Administration', 'type' => 'string', 'group' => 'legal', 'label' => 'Administrator Name'],
            ['key' => 'company_address', 'value' => '123 Broadcast Avenue, Creative District, CA 90210, USA', 'type' => 'string', 'group' => 'legal', 'label' => 'Company Address'],
            ['key' => 'cookie_policy_version', 'value' => '1.0', 'type' => 'string', 'group' => 'legal', 'label' => 'Cookie Policy Version'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        // Pages
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<div style="text-align: center; margin-bottom: 40px;">
    <img src="' . $this->downloadImage('https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=1200&q=80', 'images/pages/about-studio.jpg') . '" alt="Radio Studio" style="width: 100%; max-width: 800px; border-radius: 16px;">
</div>
<h2>Our Story</h2>
<p>Founded in 2019, NovaRadio emerged from a simple yet powerful vision: to create a digital space where music lovers could discover, connect, and experience the transformative power of sound.</p>
<p>What started as a small passion project has grown into one of the most respected internet radio stations, reaching thousands of listeners across the globe every day.</p>
<h2>Our Mission</h2>
<p>We believe in the universal language of music. Our mission is to curate exceptional listening experiences that inspire, entertain, and bring people together.</p>
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 30px 0;">
    <img src="' . $this->downloadImage('https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=600&q=80', 'images/pages/about-equipment.jpg') . '" alt="Studio" style="width: 100%; border-radius: 12px;">
    <img src="' . $this->downloadImage('https://images.unsplash.com/photo-1519508234439-4f23643125c1?w=600&q=80', 'images/pages/about-dj.jpg') . '" alt="DJ" style="width: 100%; border-radius: 12px;">
</div>',
                'meta_title' => 'About Us - NovaRadio',
                'meta_description' => 'Learn about NovaRadio, our mission, and our team of passionate music enthusiasts.',
                'status' => 'published',
                'author_id' => 1,
                'published_at' => now(),
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'content' => '<div style="text-align: center; margin-bottom: 40px;">
    <img src="' . $this->downloadImage('https://images.unsplash.com/photo-1423666639041-f56000c27a9a?w=1200&q=80', 'images/pages/contact.jpg') . '" alt="Contact" style="width: 100%; max-width: 800px; border-radius: 16px;">
</div>
<h2>Get in Touch</h2>
<p>We love hearing from our listeners. Whether you have a song request, feedback, or want to collaborate, reach out to us.</p>
<ul>
    <li><strong>Email:</strong> contact@novikradio.com</li>
    <li><strong>Studio:</strong> 123 Broadcast Avenue, Creative District, CA 90210</li>
</ul>',
                'meta_title' => 'Contact Us - NovaRadio',
                'meta_description' => 'Get in touch with NovaRadio. Send us your song requests and feedback.',
                'status' => 'published',
                'author_id' => 1,
                'published_at' => now(),
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<div style="text-align: center; margin-bottom: 40px;">
    <img src="' . $this->downloadImage('https://images.unsplash.com/photo-1563986768609-322da13575f3?w=1200&q=80', 'images/pages/privacy.jpg') . '" alt="Privacy" style="width: 100%; max-width: 800px; border-radius: 16px;">
</div>

<div style="max-width: 800px; margin: 0 auto;">
    <h1>Privacy Policy</h1>
    <p style="color: #64748b; margin-bottom: 24px;"><strong>Last Updated:</strong> March 4, 2026 | <strong>Version:</strong> 1.0 | <strong>Effective Date:</strong> March 4, 2026</p>

    <h2>1. Data Controller</h2>
    <p>The data controller responsible for processing your personal data is:</p>
    <ul>
        <li><strong>Name:</strong> NovaRadio Administration</li>
        <li><strong>Email:</strong> admin@novikradio.com</li>
        <li><strong>Address:</strong> 123 Broadcast Avenue, Creative District, CA 90210, USA</li>
    </ul>

    <h2>2. What Data We Collect</h2>
    <p>We collect and process the following types of personal data:</p>
    <ul>
        <li><strong>Technical Data:</strong> IP address, browser type and version, time zone setting, browser plug-in types and versions, operating system and platform</li>
        <li><strong>Usage Data:</strong> Information about how you use our website, including stream listening statistics</li>
        <li><strong>Cookie Data:</strong> Information stored in cookies on your device (see our Cookie Policy)</li>
        <li><strong>Contact Data:</strong> Only when you voluntarily contact us via email</li>
    </ul>

    <h2>3. Legal Basis for Processing</h2>
    <p>We process your personal data based on the following legal grounds:</p>
    <ul>
        <li><strong>Consent:</strong> For non-essential cookies and marketing communications</li>
        <li><strong>Legitimate Interests:</strong> For website analytics and improving our services</li>
        <li><strong>Legal Obligation:</strong> For compliance with applicable laws</li>
    </ul>

    <h2>4. Your Rights Under GDPR</h2>
    <p>As a data subject, you have the following rights:</p>
    <ul>
        <li><strong>Right to Access:</strong> Request a copy of your personal data</li>
        <li><strong>Right to Rectification:</strong> Request correction of inaccurate data</li>
        <li><strong>Right to Erasure ("Right to be Forgot"):</strong> Request deletion of your data</li>
        <li><strong>Right to Restrict Processing:</strong> Request limitation of data processing</li>
        <li><strong>Right to Data Portability:</strong> Receive data in a structured format</li>
        <li><strong>Right to Object:</strong> Object to processing based on legitimate interests</li>
        <li><strong>Right to Withdraw Consent:</strong> Withdraw cookie consent at any time</li>
    </ul>

    <h2>5. How to Exercise Your Rights</h2>
    <p>To exercise any of your rights, please contact us at:</p>
    <ul>
        <li><strong>Email:</strong> admin@novikradio.com</li>
        <li><strong>Subject:</strong> "GDPR Request"</li>
    </ul>
    <p>We will respond to all requests within 30 days as required by law.</p>

    <h2>6. Data Retention</h2>
    <p>We retain your personal data only for as long as necessary:</p>
    <ul>
        <li>Analytics data: 26 months</li>
        <li>Contact form submissions: 3 years</li>
        <li>Cookie preferences: Until you clear your browser cookies</li>
    </ul>

    <h2>7. Data Security</h2>
    <p>We implement appropriate technical and organizational measures to protect your data:</p>
    <ul>
        <li>SSL/TLS encryption for all data transmission</li>
        <li>Regular security assessments</li>
        <li>Limited access to personal data (authorized personnel only)</li>
    </ul>

    <h2>8. Third-Party Processors</h2>
    <p>We use the following third-party services that may process your data:</p>
    <ul>
        <li><strong>Font Awesome:</strong> For icon fonts (CDN)</li>
        <li><strong>AzuraCast:</strong> For streaming services (if configured)</li>
    </ul>

    <h2>9. Complaints</h2>
    <p>If you believe we have not handled your data properly, you have the right to lodge a complaint with your local data protection authority.</p>

    <h2>10. Changes to This Policy</h2>
    <p>We may update this Privacy Policy periodically. The latest version will always be available on this page with the updated date.</p>
</div>',
                'meta_title' => 'Privacy Policy - NovaRadio',
                'meta_description' => 'NovaRadio Privacy Policy. Learn how we collect, use, and protect your personal data in compliance with GDPR.',
                'status' => 'published',
                'author_id' => 1,
                'published_at' => now(),
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<div style="text-align: center; margin-bottom: 40px;">
    <img src="' . $this->downloadImage('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=1200&q=80', 'images/pages/terms.jpg') . '" alt="Legal" style="width: 100%; max-width: 800px; border-radius: 16px;">
</div>

<div style="max-width: 800px; margin: 0 auto;">
    <h1>Terms of Service</h1>
    <p style="color: #64748b; margin-bottom: 24px;"><strong>Last Updated:</strong> March 4, 2026 | <strong>Version:</strong> 1.0</p>

    <h2>1. Acceptance of Terms</h2>
    <p>By accessing and using NovaRadio ("we", "our", "us"), you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our website or services.</p>

    <h2>2. Description of Service</h2>
    <p>NovaRadio provides an internet radio streaming service that allows users to listen to music broadcasts online. Our service includes:</p>
    <ul>
        <li>Live audio streaming</li>
        <li>Program schedules and show information</li>
        <li>Team and contact information</li>
        <li>Website with related content</li>
    </ul>

    <h2>3. Eligibility</h2>
    <p>By using our service, you represent and warrant that:</p>
    <ul>
        <li>You are at least 16 years of age</li>
        <li>You have the legal capacity to enter into these Terms</li>
        <li>You will use the service in compliance with all applicable laws</li>
    </ul>

    <h2>4. User Conduct</h2>
    <p>You agree not to:</p>
    <ul>
        <li>Use our service for any unlawful purpose</li>
        <li>Redistribute, record, or rebroadcast our audio streams without authorization</li>
        <li>Attempt to interfere with or disrupt our servers or networks</li>
        <li>Use automated systems to access our service without permission</li>
        <li>Impersonate any person or entity</li>
    </ul>

    <h2>5. Intellectual Property</h2>
    <p>All content on NovaRadio, including but not limited to:</p>
    <ul>
        <li>Audio content (music, broadcasts)</li>
        <li>Text, graphics, logos, and images</li>
        <li>Software and code</li>
        <li>Trademarks and service marks</li>
    </ul>
    <p>is the property of NovaRadio or its licensors and is protected by copyright, trademark, and other intellectual property laws.</p>

    <h2>6. Streaming Usage</h2>
    <p>Our audio streams are provided for personal, non-commercial use only. You may not:</p>
    <ul>
        <li>Rebroadcast or redistribute our streams</li>
        <li>Use our streams in commercial establishments without proper licensing</li>
        <li>Record and redistribute our broadcasts</li>
    </ul>

    <h2>7. Disclaimer of Warranties</h2>
    <p>THE SERVICE IS PROVIDED "AS IS" WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED. WE DO NOT GUARANTEE THAT THE SERVICE WILL BE UNINTERRUPTED, TIMELY, SECURE, OR ERROR-FREE.</p>

    <h2>8. Limitation of Liability</h2>
    <p>IN NO EVENT SHALL NOVARADIO BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES ARISING OUT OF OR RELATING TO YOUR USE OF THE SERVICE.</p>

    <h2>9. Governing Law</h2>
    <p>These Terms shall be governed by and construed in accordance with the laws of the State of California, USA, without regard to conflict of law principles.</p>

    <h2>10. Changes to Terms</h2>
    <p>We reserve the right to modify these Terms at any time. Changes will be effective immediately upon posting to this page. Your continued use of the service after changes constitutes acceptance of the new Terms.</p>

    <h2>11. Contact Information</h2>
    <p>For questions about these Terms, please contact us at:</p>
    <ul>
        <li><strong>Email:</strong> admin@novikradio.com</li>
        <li><strong>Address:</strong> 123 Broadcast Avenue, Creative District, CA 90210, USA</li>
    </ul>
</div>',
                'status' => 'published',
                'author_id' => 1,
                'published_at' => now(),
            ],
            [
                'title' => 'Cookie Policy',
                'slug' => 'cookie-policy',
                'content' => '<div style="text-align: center; margin-bottom: 40px;">
    <img src="' . $this->downloadImage('https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?w=1200&q=80', 'images/pages/cookies.jpg') . '" alt="Cookies" style="width: 100%; max-width: 800px; border-radius: 16px;">
</div>

<div style="max-width: 800px; margin: 0 auto;">
    <h1>Cookie Policy</h1>
    <p style="color: #64748b; margin-bottom: 24px;"><strong>Last Updated:</strong> March 4, 2026 | <strong>Version:</strong> 1.0 | <strong>Policy ID:</strong> CP-2026-001</p>

    <h2>1. What Are Cookies</h2>
    <p>Cookies are small text files that are placed on your computer or mobile device when you visit a website. They are widely used to make websites work more efficiently and provide information to the website owners.</p>

    <h2>2. How We Use Cookies</h2>
    <p>NovaRadio uses cookies for the following purposes:</p>

    <h3>2.1 Essential Cookies (Required)</h3>
    <p>These cookies are necessary for the website to function properly. They cannot be disabled.</p>
    <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
        <tr style="background: #f8fafc;">
            <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Cookie Name</th>
            <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Purpose</th>
            <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Duration</th>
        </tr>
        <tr>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">XSRF-TOKEN</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">Security - prevents cross-site request forgery attacks</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">Session</td>
        </tr>
        <tr>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">novaradio_session</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">Maintains your session state across page requests</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">2 hours</td>
        </tr>
        <tr>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">cookie_consent</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">Stores your cookie consent preferences</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">1 year</td>
        </tr>
    </table>

    <h3>2.2 Analytics Cookies (Optional)</h3>
    <p>These cookies help us understand how visitors interact with our website.</p>
    <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
        <tr style="background: #f8fafc;">
            <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Cookie Name</th>
            <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Purpose</th>
            <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Duration</th>
        </tr>
        <tr>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">_ga</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">Google Analytics - distinguishes users</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">2 years</td>
        </tr>
        <tr>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">_gid</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">Google Analytics - distinguishes users</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">24 hours</td>
        </tr>
    </table>

    <h3>2.3 Functional Cookies (Optional)</h3>
    <p>These cookies enable enhanced functionality and personalization.</p>
    <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
        <tr style="background: #f8fafc;">
            <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Cookie Name</th>
            <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Purpose</th>
            <th style="padding: 12px; text-align: left; border: 1px solid #e2e8f0;">Duration</th>
        </tr>
        <tr>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">player_volume</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">Remembers your audio player volume setting</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">30 days</td>
        </tr>
        <tr>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">theme_preference</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">Stores your theme preference (if available)</td>
            <td style="padding: 12px; border: 1px solid #e2e8f0;">1 year</td>
        </tr>
    </table>

    <h2>3. Third-Party Cookies</h2>
    <p>We use services from third parties that may set cookies:</p>
    <ul>
        <li><strong>Font Awesome:</strong> Used for loading icon fonts</li>
        <li><strong>Google Analytics:</strong> Used for website analytics (if enabled)</li>
    </ul>

    <h2>4. Managing Cookies</h2>
    <p>You can control cookies through your browser settings:</p>
    <ul>
        <li><strong>Chrome:</strong> Settings → Privacy and security → Cookies and other site data</li>
        <li><strong>Firefox:</strong> Settings → Privacy & Security → Cookies and Site Data</li>
        <li><strong>Safari:</strong> Preferences → Privacy → Cookies and website data</li>
        <li><strong>Edge:</strong> Settings → Cookies and site permissions → Manage and delete cookies</li>
    </ul>
    <p><strong>Note:</strong> Disabling essential cookies may prevent the website from functioning properly.</p>

    <h2>5. Your Consent</h2>
    <p>When you first visit our website, you will see a cookie banner requesting your consent for non-essential cookies. You can:</p>
    <ul>
        <li>Accept all cookies</li>
        <li>Reject optional cookies (only essential cookies will be used)</li>
        <li>Modify your preferences at any time by clearing browser cookies</li>
    </ul>

    <h2>6. Data Controller</h2>
    <p>The entity responsible for cookie processing:</p>
    <ul>
        <li><strong>Name:</strong> NovaRadio Administration</li>
        <li><strong>Email:</strong> admin@novikradio.com</li>
        <li><strong>Address:</strong> 123 Broadcast Avenue, Creative District, CA 90210, USA</li>
    </ul>

    <h2>7. Changes to This Policy</h2>
    <p>We may update this Cookie Policy from time to time. Any changes will be posted on this page with an updated revision date.</p>

    <h2>8. Contact Us</h2>
    <p>If you have any questions about our Cookie Policy, please contact us at <strong>admin@novikradio.com</strong> with the subject line "Cookie Policy Inquiry".</p>
</div>',
                'status' => 'published',
                'author_id' => 1,
                'published_at' => now(),
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(['slug' => $page['slug']], $page);
        }

        // Team Members with local images
        $teamMembers = [
            [
                'name' => 'Alex Chen',
                'slug' => 'alex-chen',
                'role' => 'Station Manager',
                'bio' => 'With 15 years in broadcasting, Alex brings energy and expertise to every show.',
                'photo' => 'images/team/alex-chen.jpg',
                'email' => 'alex@novikradio.com',
                'status' => 'active',
            ],
            [
                'name' => 'Sarah Mitchell',
                'slug' => 'sarah-mitchell',
                'role' => 'Head of Programming',
                'bio' => 'Sarah curates our main playlist with an ear for emerging artists.',
                'photo' => 'images/team/sarah-mitchell.jpg',
                'email' => 'sarah@novikradio.com',
                'status' => 'active',
            ],
            [
                'name' => 'Marcus Johnson',
                'slug' => 'marcus-johnson',
                'role' => 'Evening Host',
                'bio' => 'A trained saxophonist and jazz historian, Marcus brings smooth sophistication.',
                'photo' => 'images/team/marcus-johnson.jpg',
                'email' => 'marcus@novikradio.com',
                'status' => 'active',
            ],
            [
                'name' => 'Emma Rodriguez',
                'slug' => 'emma-rodriguez',
                'role' => 'Electronic Music Director',
                'bio' => 'Emma keeps our pulse on the global electronic scene.',
                'photo' => 'images/team/emma-rodriguez.jpg',
                'email' => 'emma@novikradio.com',
                'status' => 'active',
            ],
        ];

        foreach ($teamMembers as $member) {
            TeamMember::firstOrCreate(['slug' => $member['slug']], $member);
        }

        // Schedule Shows
        $shows = [
            ['title' => 'Morning Rise', 'day' => 'monday', 'start_time' => '06:00', 'end_time' => '10:00', 'host' => 'Alex Chen', 'status' => 'active'],
            ['title' => 'Work Flow', 'day' => 'monday', 'start_time' => '10:00', 'end_time' => '14:00', 'status' => 'active'],
            ['title' => 'Evening Wind Down', 'day' => 'monday', 'start_time' => '18:00', 'end_time' => '22:00', 'status' => 'active'],
            ['title' => 'Morning Rise', 'day' => 'tuesday', 'start_time' => '06:00', 'end_time' => '10:00', 'host' => 'Alex Chen', 'status' => 'active'],
            ['title' => 'Indie Discovery', 'day' => 'tuesday', 'start_time' => '14:00', 'end_time' => '16:00', 'host' => 'Sarah Mitchell', 'status' => 'active'],
            ['title' => 'Jazz Lounge', 'day' => 'tuesday', 'start_time' => '18:00', 'end_time' => '20:00', 'host' => 'Marcus Johnson', 'status' => 'active'],
            ['title' => 'Party Time Friday', 'day' => 'friday', 'start_time' => '18:00', 'end_time' => '22:00', 'status' => 'active'],
            ['title' => 'Weekend Chill', 'day' => 'saturday', 'start_time' => '08:00', 'end_time' => '12:00', 'status' => 'active'],
        ];

        foreach ($shows as $show) {
            ScheduleShow::firstOrCreate(
                ['title' => $show['title'], 'day' => $show['day']],
                $show
            );
        }
        
        // Categories
        $categories = [
            ['name' => 'Music News', 'slug' => 'music-news', 'color' => '#6366f1', 'order' => 1],
            ['name' => 'Artist Spotlight', 'slug' => 'artist-spotlight', 'color' => '#8b5cf6', 'order' => 2],
            ['name' => 'Station Updates', 'slug' => 'station-updates', 'color' => '#22c55e', 'order' => 3],
            ['name' => 'Events', 'slug' => 'events', 'color' => '#f59e0b', 'order' => 4],
        ];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
        
        // Tags
        $tags = [
            ['name' => 'electronic', 'slug' => 'electronic'],
            ['name' => 'jazz', 'slug' => 'jazz'],
            ['name' => 'indie', 'slug' => 'indie'],
            ['name' => 'new-release', 'slug' => 'new-release'],
            ['name' => 'live', 'slug' => 'live'],
        ];
        foreach ($tags as $tag) {
            Tag::firstOrCreate(['slug' => $tag['slug']], $tag);
        }
        
        // Articles
        $articles = [
            [
                'title' => 'Welcome to NovaRadio - Your New Home for Great Music',
                'slug' => 'welcome-to-novaradio',
                'excerpt' => 'Discover a world of curated music, from jazz to electronic, indie to classical.',
                'content' => '<p>Welcome to NovaRadio, your premier destination for curated music streaming. We bring you the finest selection of tracks from around the world, carefully selected by our team of passionate DJs and music enthusiasts.</p><p>Whether you are into jazz, electronic, indie, or classical music, we have something for everyone. Tune in and discover your next favorite song!</p>',
                'featured_image' => $this->downloadImage('https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800&q=80', 'images/articles/welcome.jpg'),
                'category_id' => 3,
                'author_id' => 1,
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Top 10 Electronic Tracks This Month',
                'slug' => 'top-10-electronic-tracks',
                'excerpt' => 'Our picks for the best electronic music released this month.',
                'content' => '<p>From house to techno, ambient to drum and bass - here are our top 10 electronic tracks that you need to hear right now.</p><h3>1. Track Name - Artist</h3><p>Description...</p><h3>2. Track Name - Artist</h3><p>Description...</p>',
                'featured_image' => 'images/articles/electronic-music.jpg',
                'category_id' => 1,
                'author_id' => 1,
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
        ];
        foreach ($articles as $article) {
            $art = Article::firstOrCreate(['slug' => $article['slug']], $article);
            if ($art->wasRecentlyCreated) {
                $art->tags()->attach([1, 4]);
            }
        }
        
        // DJ Profiles
        DjProfile::firstOrCreate(
            ['team_member_id' => 2],
            [
                'stage_name' => 'DJ Sarah',
                'genre' => 'Indie / Alternative',
                'biography' => 'Sarah has been curating indie playlists for over a decade.',
                'equipment' => 'Pioneer DJM-900, Technics SL-1200',
                'soundcloud_url' => 'https://soundcloud.com',
                'is_resident' => true,
                'years_experience' => 10,
                'top_tracks' => [
                    ['title' => 'Indie Vibes', 'artist' => 'The Weekenders'],
                    ['title' => 'Summer Dreams', 'artist' => 'Coastal Kids'],
                ],
            ]
        );
        
        DjProfile::firstOrCreate(
            ['team_member_id' => 3],
            [
                'stage_name' => 'Smooth Marcus',
                'genre' => 'Jazz / Soul',
                'biography' => 'A trained saxophonist and jazz historian.',
                'equipment' => 'Rane MP2015, Technics SL-1200MK2',
                'mixcloud_url' => 'https://mixcloud.com',
                'is_resident' => true,
                'years_experience' => 15,
            ]
        );
        
        // Events
        Event::firstOrCreate(
            ['slug' => 'summer-music-festival-2026'],
            [
                'title' => 'Summer Music Festival 2026',
                'description' => 'Join us for a day of amazing music.',
                'image' => 'images/events/summer-festival.jpg',
                'start_date' => now()->addMonths(3),
                'end_date' => now()->addMonths(3)->addHours(8),
                'venue' => 'Central Park Amphitheater',
                'address' => '123 Park Avenue',
                'city' => 'New York',
                'ticket_price' => 45.00,
                'status' => 'upcoming',
                'featured_dj_id' => 2,
            ]
        );
        
        Event::firstOrCreate(
            ['slug' => 'jazz-night-live'],
            [
                'title' => 'Jazz Night Live',
                'description' => 'An intimate evening of smooth jazz.',
                'image' => $this->downloadImage('https://images.unsplash.com/photo-1511192336575-5a79af67a629?w=800&q=80', 'images/events/jazz-night.jpg'),
                'start_date' => now()->addWeeks(2),
                'venue' => 'Blue Note Lounge',
                'address' => '456 Jazz Street',
                'city' => 'Chicago',
                'ticket_price' => 25.00,
                'status' => 'upcoming',
                'featured_dj_id' => 3,
            ]
        );

        // Register all existing images in media library
        $this->call(MediaSeeder::class);
    }
    
    /**
     * Download image from URL and save to public directory
     */
    private function downloadImage(string $url, string $path): string
    {
        try {
            $fullPath = public_path($path);
            
            // Check if already exists
            if (file_exists($fullPath)) {
                return $path;
            }
            
            // Create directory
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Download image
            $response = Http::timeout(30)->get($url);
            
            if ($response->successful()) {
                file_put_contents($fullPath, $response->body());
                return $path;
            }
        } catch (\Exception $e) {
            \Log::error('Failed to download image: ' . $url . ' - ' . $e->getMessage());
        }
        
        return $path;
    }
}
