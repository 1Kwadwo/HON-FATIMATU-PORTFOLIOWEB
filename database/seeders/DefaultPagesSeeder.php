<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user to set as the updater
        $adminUser = User::first();

        if (!$adminUser) {
            $this->command->error('No admin user found. Please run AdminUserSeeder first.');
            return;
        }

        $pages = [
            [
                'slug' => 'home',
                'title' => 'Home',
                'content' => $this->getHomeContent(),
                'meta_title' => 'Hon. Fatimatu Abubakar | Leadership & Legacy',
                'meta_description' => 'Official website of Hon. Fatimatu Abubakar - A dedicated leader committed to community development, education, and social progress.',
            ],
            [
                'slug' => 'about',
                'title' => 'About',
                'content' => $this->getAboutContent(),
                'meta_title' => 'About Hon. Fatimatu Abubakar | Biography & Career',
                'meta_description' => 'Learn about Hon. Fatimatu Abubakar\'s background, education, career achievements, and leadership philosophy.',
            ],
            [
                'slug' => 'initiatives',
                'title' => 'Initiatives',
                'content' => $this->getInitiativesContent(),
                'meta_title' => 'Initiatives & Projects | Hon. Fatimatu Abubakar',
                'meta_description' => 'Explore the community initiatives and projects led by Hon. Fatimatu Abubakar, making a lasting impact on education, healthcare, and social development.',
            ],
        ];

        foreach ($pages as $pageData) {
            $existingPage = Page::where('slug', $pageData['slug'])->first();

            if ($existingPage) {
                $this->command->info("Page '{$pageData['slug']}' already exists. Skipping...");
                continue;
            }

            Page::create([
                'slug' => $pageData['slug'],
                'title' => $pageData['title'],
                'content' => $pageData['content'],
                'meta_title' => $pageData['meta_title'],
                'meta_description' => $pageData['meta_description'],
                'updated_by' => $adminUser->id,
            ]);

            $this->command->info("Created page: {$pageData['title']}");
        }

        $this->command->info('Default pages seeded successfully!');
    }

    /**
     * Get home page content
     */
    private function getHomeContent(): string
    {
        return <<<'HTML'
<div class="hero-section">
    <h1>Welcome to the Official Website</h1>
    <p class="tagline">Leadership. Service. Impact.</p>
</div>

<div class="mission-statement">
    <h2>Our Mission</h2>
    <p>Dedicated to serving the community through visionary leadership, sustainable development initiatives, and unwavering commitment to social progress. Together, we are building a brighter future for all.</p>
</div>

<div class="featured-content">
    <h2>Making a Difference</h2>
    <p>Through strategic initiatives in education, healthcare, and community development, we are creating lasting positive change that empowers individuals and strengthens communities.</p>
</div>
HTML;
    }

    /**
     * Get about page content
     */
    private function getAboutContent(): string
    {
        return <<<'HTML'
<div class="biography">
    <h1>About Hon. Fatimatu Abubakar</h1>
    
    <section class="early-life">
        <h2>Early Life & Education</h2>
        <p>Hon. Fatimatu Abubakar's journey began with a strong foundation in education and a deep commitment to public service. From an early age, she demonstrated exceptional leadership qualities and a passion for community development.</p>
        <p>She pursued higher education with distinction, earning degrees that would later inform her approach to policy-making and community engagement. Her academic background combines expertise in public administration, social development, and strategic leadership.</p>
    </section>

    <section class="career">
        <h2>Career & Leadership</h2>
        <p>Throughout her distinguished career, Hon. Fatimatu Abubakar has held various leadership positions, each marked by transformative initiatives and measurable impact. Her approach to governance emphasizes transparency, accountability, and inclusive development.</p>
        <p>Key career milestones include:</p>
        <ul>
            <li>Ministerial appointments focused on social development and community welfare</li>
            <li>Leadership of major infrastructure and education initiatives</li>
            <li>Advocacy for women's empowerment and youth development</li>
            <li>Strategic partnerships with international development organizations</li>
        </ul>
    </section>

    <section class="philosophy">
        <h2>Leadership Philosophy</h2>
        <p>Hon. Fatimatu Abubakar believes in servant leadership - a philosophy that places the needs of the community at the center of all decision-making. Her approach is characterized by:</p>
        <ul>
            <li><strong>Inclusive Governance:</strong> Ensuring all voices are heard in the policy-making process</li>
            <li><strong>Sustainable Development:</strong> Creating programs that deliver long-term benefits</li>
            <li><strong>Transparency:</strong> Maintaining open communication with constituents</li>
            <li><strong>Innovation:</strong> Embracing new approaches to solve persistent challenges</li>
        </ul>
    </section>

    <section class="achievements">
        <h2>Notable Achievements</h2>
        <p>Over the years, Hon. Fatimatu Abubakar has been recognized for her outstanding contributions to public service and community development. Her work has positively impacted thousands of lives through education programs, healthcare initiatives, and economic empowerment projects.</p>
    </section>
</div>
HTML;
    }

    /**
     * Get initiatives page content
     */
    private function getInitiativesContent(): string
    {
        return <<<'HTML'
<div class="initiatives-overview">
    <h1>Our Initiatives</h1>
    <p class="lead">Transforming communities through strategic programs and sustainable development projects.</p>
    
    <section class="intro">
        <p>Hon. Fatimatu Abubakar's initiatives span multiple sectors, each designed to address critical needs and create lasting positive change. From education and healthcare to economic empowerment and infrastructure development, these programs are making a measurable difference in people's lives.</p>
    </section>

    <section class="focus-areas">
        <h2>Focus Areas</h2>
        
        <div class="focus-area">
            <h3>Education & Youth Development</h3>
            <p>Investing in the next generation through scholarship programs, school infrastructure improvements, and skills training initiatives that prepare young people for success in the modern economy.</p>
        </div>

        <div class="focus-area">
            <h3>Healthcare & Wellness</h3>
            <p>Improving access to quality healthcare services through mobile clinics, health education campaigns, and support for medical facilities in underserved communities.</p>
        </div>

        <div class="focus-area">
            <h3>Women's Empowerment</h3>
            <p>Creating opportunities for women through entrepreneurship training, microfinance programs, and advocacy for gender equality in all sectors of society.</p>
        </div>

        <div class="focus-area">
            <h3>Community Infrastructure</h3>
            <p>Building essential infrastructure including roads, water systems, and community centers that improve quality of life and enable economic development.</p>
        </div>
    </section>

    <section class="impact">
        <h2>Our Impact</h2>
        <p>Through these initiatives, we have reached thousands of beneficiaries, created hundreds of jobs, and established sustainable programs that continue to deliver benefits long after initial implementation.</p>
        <p>Each initiative is carefully designed with input from community members, implemented with transparency, and evaluated for effectiveness to ensure maximum impact.</p>
    </section>
</div>
HTML;
    }
}
