<?php

return [
    'header' => [
        [
            'key' => 'home',
            'label' => 'Beranda',
            'route' => 'home',
            'icon' => 'heroicon-o-home',
            'visible' => true,
        ],
        [
            'key' => 'about',
            'label' => 'Tentang Kami',
            'icon' => 'heroicon-o-building-office-2',
            'visible' => true,
            'children' => [
                [
                    'key' => 'about.profile',
                    'label' => 'Profil Kami',
                    'route' => 'about',
                    'icon' => 'heroicon-o-building-office-2',
                ],
                [
                    'key' => 'about.team',
                    'label' => 'Tim Kami',
                    'route' => 'team.index',
                    'icon' => 'heroicon-o-user-group',
                ],
                [
                    'key' => 'about.careers',
                    'label' => 'Karier',
                    'route' => 'careers.index',
                    'icon' => 'heroicon-o-briefcase',
                ],
                [
                    'key' => 'about.portfolio',
                    'label' => 'Portfolio',
                    'route' => 'portfolio.index',
                    'icon' => 'heroicon-o-folder-open',
                ],
                [
                    'key' => 'about.testimonials',
                    'label' => 'Testimoni',
                    'route' => 'testimonials.index',
                    'icon' => 'heroicon-o-chat-bubble-left-right',
                ],
            ],
        ],
        [
            'key' => 'services',
            'label' => 'Layanan',
            'icon' => 'heroicon-o-squares-2x2',
            'visible' => true,
            'children' => [
                [
                    'key' => 'services.index',
                    'label' => 'Semua Layanan',
                    'route' => 'services.index',
                    'icon' => 'heroicon-o-squares-2x2',
                ],
                [
                    'key' => 'services.category',
                    'label' => 'Kategori Layanan',
                    'route' => 'services.category',
                    'parameters' => ['slug' => 'general'],
                    'icon' => 'heroicon-o-tag',
                ],
                [
                    'key' => 'services.process',
                    'label' => 'Proses Layanan',
                    'route' => 'services.process',
                    'icon' => 'heroicon-o-clipboard-document-list',
                ],
                [
                    'key' => 'services.solutions',
                    'label' => 'Solusi',
                    'route' => 'solutions.index',
                    'icon' => 'heroicon-o-light-bulb',
                ],
                [
                    'key' => 'services.pricing',
                    'label' => 'Harga',
                    'route' => 'pricing.index',
                    'icon' => 'heroicon-o-currency-dollar',
                ],
                [
                    'key' => 'services.comparison',
                    'label' => 'Perbandingan Harga',
                    'route' => 'pricing.comparison',
                    'icon' => 'heroicon-o-scale',
                ],
            ],
        ],
        [
            'key' => 'information',
            'label' => 'Informasi',
            'icon' => 'heroicon-o-information-circle',
            'visible' => true,
            'children' => [
                [
                    'key' => 'information.blog',
                    'label' => 'Blog',
                    'route' => 'blog.index',
                    'icon' => 'heroicon-o-newspaper',
                ],
                [
                    'key' => 'information.faq',
                    'label' => 'FAQ',
                    'route' => 'faq.index',
                    'icon' => 'heroicon-o-question-mark-circle',
                ],
                [
                    'key' => 'information.categories',
                    'label' => 'Kategori',
                    'route' => 'categories.index',
                    'icon' => 'heroicon-o-squares-2x2',
                ],
                [
                    'key' => 'information.tags',
                    'label' => 'Tag',
                    'route' => 'tags.index',
                    'icon' => 'heroicon-o-tag',
                ],
                [
                    'key' => 'information.search',
                    'label' => 'Pencarian',
                    'route' => 'search.index',
                    'icon' => 'heroicon-o-magnifying-glass',
                ],
                [
                    'key' => 'information.contact',
                    'label' => 'Hubungi Kami',
                    'route' => 'contact',
                    'icon' => 'heroicon-o-chat-bubble-left-right',
                ],
            ],
        ],
    ],
    'footer' => [
        'Sumber Daya' => [
            [
                'key' => 'footer.blog.archive',
                'label' => 'Arsip Blog',
                'route' => 'blog.archive',
                'icon' => 'heroicon-o-archive-box',
            ],
            [
                'key' => 'footer.system.status',
                'label' => 'Status Sistem',
                'route' => 'status.index',
                'icon' => 'heroicon-o-signal',
            ],
            [
                'key' => 'footer.newsletter',
                'label' => 'Berlangganan Newsletter',
                'route' => 'newsletter.subscribe',
                'icon' => 'heroicon-o-envelope',
            ],
        ],
        'Bantuan' => [
            [
                'key' => 'footer.support.index',
                'label' => 'Pusat Bantuan',
                'route' => 'support.index',
                'icon' => 'heroicon-o-lifebuoy',
            ],
            [
                'key' => 'footer.support.knowledge',
                'label' => 'Basis Pengetahuan',
                'route' => 'support.knowledge-base',
                'icon' => 'heroicon-o-book-open',
            ],
            [
                'key' => 'footer.support.search',
                'label' => 'Pencarian Bantuan',
                'route' => 'support.search',
                'icon' => 'heroicon-o-magnifying-glass',
            ],
            [
                'key' => 'footer.support.create-ticket',
                'label' => 'Buat Tiket Bantuan',
                'route' => 'support.tickets.create',
                'icon' => 'heroicon-o-ticket',
            ],
        ],
        'Kebijakan' => [
            [
                'key' => 'footer.legal.privacy',
                'label' => 'Kebijakan Privasi',
                'route' => 'legal.privacy-policy',
                'icon' => 'heroicon-o-shield-check',
            ],
            [
                'key' => 'footer.legal.terms',
                'label' => 'Syarat dan Ketentuan',
                'route' => 'legal.terms-of-service',
                'icon' => 'heroicon-o-document-text',
            ],
            [
                'key' => 'footer.legal.cookies',
                'label' => 'Kebijakan Cookie',
                'route' => 'legal.cookie-policy',
                'icon' => 'heroicon-o-rectangle-stack',
            ],
            [
                'key' => 'footer.legal.acceptable-use',
                'label' => 'Kebijakan Penggunaan',
                'route' => 'legal.acceptable-use-policy',
                'icon' => 'heroicon-o-check-circle',
            ],
            [
                'key' => 'footer.legal.data-processing',
                'label' => 'Kebijakan Pemrosesan Data',
                'route' => 'legal.data-processing-policy',
                'icon' => 'heroicon-o-document-check',
            ],
        ],
        'Informasi Legal' => [
            [
                'key' => 'footer.legal.accessibility',
                'label' => 'Pernyataan Aksesibilitas',
                'route' => 'legal.accessibility-statement',
                'icon' => 'heroicon-o-eye',
            ],
            [
                'key' => 'footer.legal.copyright',
                'label' => 'Hak Cipta',
                'route' => 'legal.copyright',
                'icon' => 'heroicon-o-document',
            ],
            [
                'key' => 'footer.legal.disclaimer',
                'label' => 'Penafian',
                'route' => 'legal.disclaimer',
                'icon' => 'heroicon-o-information-circle',
            ],
            [
                'key' => 'footer.legal.refund',
                'label' => 'Kebijakan Pengembalian Dana',
                'route' => 'legal.refund-policy',
                'icon' => 'heroicon-o-arrow-uturn-left',
            ],
        ],
    ],
    'account' => [
        [
            'key' => 'account.dashboard',
            'label' => 'Dashboard',
            'route' => 'account.index',
            'icon' => 'heroicon-o-squares-2x2',
        ],
        [
            'key' => 'account.profile',
            'label' => 'Profil Saya',
            'route' => 'account.profile',
            'icon' => 'heroicon-o-user',
        ],
        [
            'key' => 'account.settings',
            'label' => 'Pengaturan Akun',
            'route' => 'account.settings',
            'icon' => 'heroicon-o-cog-6-tooth',
        ],
        [
            'key' => 'account.preferences',
            'label' => 'Preferensi',
            'route' => 'account.preferences',
            'icon' => 'heroicon-o-adjustments-horizontal',
        ],
        [
            'key' => 'account.activity',
            'label' => 'Aktivitas',
            'route' => 'account.activity',
            'icon' => 'heroicon-o-clock',
        ],
        [
            'key' => 'account.notifications',
            'label' => 'Notifikasi',
            'route' => 'account.notifications',
            'icon' => 'heroicon-o-bell',
        ],
        [
            'key' => 'account.privacy',
            'label' => 'Privasi',
            'route' => 'account.privacy',
            'icon' => 'heroicon-o-shield-check',
        ],
        [
            'key' => 'account.security',
            'label' => 'Keamanan',
            'route' => 'account.security',
            'icon' => 'heroicon-o-lock-closed',
        ],
        [
            'key' => 'account.sessions',
            'label' => 'Sesi Aktif',
            'route' => 'account.sessions',
            'icon' => 'heroicon-o-computer-desktop',
        ],
        [
            'key' => 'account.connected-accounts',
            'label' => 'Akun Terhubung',
            'route' => 'account.connected-accounts',
            'icon' => 'heroicon-o-link',
        ],
        [
            'key' => 'account.subscriptions',
            'label' => 'Langganan',
            'route' => 'account.subscriptions',
            'icon' => 'heroicon-o-credit-card',
        ],
        [
            'key' => 'account.billing',
            'label' => 'Tagihan',
            'route' => 'account.billing',
            'icon' => 'heroicon-o-banknotes',
        ],
        [
            'key' => 'account.invoices',
            'label' => 'Invoice',
            'route' => 'account.invoices',
            'icon' => 'heroicon-o-document-text',
        ],
        [
            'key' => 'account.support',
            'label' => 'Dukungan',
            'route' => 'account.support',
            'icon' => 'heroicon-o-lifebuoy',
        ],
        [
            'key' => 'account.delete-account',
            'label' => 'Hapus Akun',
            'route' => 'account.delete-account',
            'icon' => 'heroicon-o-trash',
        ],
    ],
    'auth' => [
        [
            'key' => 'auth.login',
            'label' => 'Masuk',
            'route' => 'login',
            'icon' => 'heroicon-o-arrow-right-end-on-rectangle',
            'guest' => true,
        ],
        [
            'key' => 'auth.register',
            'label' => 'Daftar',
            'route' => 'register',
            'icon' => 'heroicon-o-user-plus',
            'guest' => true,
        ],
        [
            'key' => 'auth.logout',
            'label' => 'Keluar',
            'route' => 'logout',
            'icon' => 'heroicon-o-arrow-left-start-on-rectangle',
            'auth' => true,
        ],
        [
            'key' => 'auth.password.request',
            'label' => 'Lupa Password',
            'route' => 'password.request',
            'guest' => true,
        ],
        [
            'key' => 'auth.password.confirm',
            'label' => 'Konfirmasi Password',
            'route' => 'password.confirm',
            'auth' => true,
        ],
        [
            'key' => 'auth.verification.notice',
            'label' => 'Verifikasi Email',
            'route' => 'verification.notice',
            'auth' => true,
        ],
        [
            'key' => 'auth.two-factor.login',
            'label' => 'Verifikasi Dua Faktor',
            'route' => 'two-factor.login',
            'guest' => true,
        ],
        [
            'key' => 'auth.two-factor.setup',
            'label' => 'Pengaturan Dua Faktor',
            'route' => 'two-factor.setup',
            'auth' => true,
        ],
        [
            'key' => 'auth.recovery-codes',
            'label' => 'Kode Pemulihan',
            'route' => 'recovery-codes',
            'auth' => true,
        ],
        [
            'key' => 'auth.onboarding',
            'label' => 'Onboarding',
            'route' => 'onboarding.index',
            'auth' => true,
        ],
        [
            'key' => 'auth.onboarding.profile',
            'label' => 'Profil Onboarding',
            'route' => 'onboarding.profile',
            'auth' => true,
        ],
        [
            'key' => 'auth.onboarding.preferences',
            'label' => 'Preferensi Onboarding',
            'route' => 'onboarding.preferences',
            'auth' => true,
        ],
        [
            'key' => 'auth.onboarding.complete',
            'label' => 'Selesaikan Onboarding',
            'route' => 'onboarding.complete',
            'auth' => true,
        ],
    ],
    'notifications' => [
        [
            'key' => 'notifications.index',
            'label' => 'Semua Notifikasi',
            'route' => 'notifications.index',
            'icon' => 'heroicon-o-bell',
        ],
        [
            'key' => 'notifications.preferences',
            'label' => 'Preferensi Notifikasi',
            'route' => 'notifications.preferences',
            'icon' => 'heroicon-o-cog-6-tooth',
        ],
    ],
    'content' => [
        'blog' => [
            [
                'key' => 'content.blog.index',
                'label' => 'Blog',
                'route' => 'blog.index',
            ],
            [
                'key' => 'content.blog.archive',
                'label' => 'Arsip Blog',
                'route' => 'blog.archive',
            ],
            [
                'key' => 'content.blog.author',
                'label' => 'Penulis Blog',
                'route' => 'blog.author',
                'parameters' => ['slug' => 'author'],
            ],
            [
                'key' => 'content.blog.category',
                'label' => 'Kategori Blog',
                'route' => 'blog.category',
                'parameters' => ['slug' => 'category'],
            ],
            [
                'key' => 'content.blog.tag',
                'label' => 'Tag Blog',
                'route' => 'blog.tag',
                'parameters' => ['slug' => 'tag'],
            ],
            [
                'key' => 'content.blog.search',
                'label' => 'Pencarian Blog',
                'route' => 'blog.search',
            ],
            [
                'key' => 'content.blog.show',
                'label' => 'Detail Blog',
                'route' => 'blog.show',
                'parameters' => ['slug' => 'article'],
            ],
        ],
        'careers' => [
            [
                'key' => 'content.careers.index',
                'label' => 'Karier',
                'route' => 'careers.index',
            ],
            [
                'key' => 'content.careers.application',
                'label' => 'Lamaran',
                'route' => 'careers.application',
            ],
            [
                'key' => 'content.careers.show',
                'label' => 'Detail Karier',
                'route' => 'careers.show',
                'parameters' => ['slug' => 'position'],
            ],
        ],
        'portfolio' => [
            [
                'key' => 'content.portfolio.index',
                'label' => 'Portfolio',
                'route' => 'portfolio.index',
            ],
            [
                'key' => 'content.portfolio.category',
                'label' => 'Kategori Portfolio',
                'route' => 'portfolio.category',
                'parameters' => ['slug' => 'category'],
            ],
            [
                'key' => 'content.portfolio.show',
                'label' => 'Detail Portfolio',
                'route' => 'portfolio.show',
                'parameters' => ['slug' => 'project'],
            ],
        ],
        'team' => [
            [
                'key' => 'content.team.index',
                'label' => 'Tim Kami',
                'route' => 'team.index',
            ],
            [
                'key' => 'content.team.show',
                'label' => 'Detail Anggota Tim',
                'route' => 'team.show',
                'parameters' => ['slug' => 'member'],
            ],
        ],
        'testimonials' => [
            [
                'key' => 'content.testimonials.index',
                'label' => 'Testimoni',
                'route' => 'testimonials.index',
            ],
        ],
        'pricing' => [
            [
                'key' => 'content.pricing.index',
                'label' => 'Harga',
                'route' => 'pricing.index',
            ],
            [
                'key' => 'content.pricing.comparison',
                'label' => 'Perbandingan Harga',
                'route' => 'pricing.comparison',
            ],
            [
                'key' => 'content.pricing.show',
                'label' => 'Detail Harga',
                'route' => 'pricing.show',
                'parameters' => ['slug' => 'plan'],
            ],
        ],
        'services' => [
            [
                'key' => 'content.services.index',
                'label' => 'Layanan',
                'route' => 'services.index',
            ],
            [
                'key' => 'content.services.category',
                'label' => 'Kategori Layanan',
                'route' => 'services.category',
                'parameters' => ['slug' => 'category'],
            ],
            [
                'key' => 'content.services.process',
                'label' => 'Proses Layanan',
                'route' => 'services.process',
            ],
            [
                'key' => 'content.services.show',
                'label' => 'Detail Layanan',
                'route' => 'services.show',
                'parameters' => ['slug' => 'service'],
            ],
        ],
        'solutions' => [
            [
                'key' => 'content.solutions.index',
                'label' => 'Solusi',
                'route' => 'solutions.index',
            ],
            [
                'key' => 'content.solutions.category',
                'label' => 'Kategori Solusi',
                'route' => 'solutions.category',
                'parameters' => ['slug' => 'category'],
            ],
            [
                'key' => 'content.solutions.show',
                'label' => 'Detail Solusi',
                'route' => 'solutions.show',
                'parameters' => ['slug' => 'solution'],
            ],
        ],
        'faq' => [
            [
                'key' => 'content.faq.index',
                'label' => 'FAQ',
                'route' => 'faq.index',
            ],
            [
                'key' => 'content.faq.category',
                'label' => 'Kategori FAQ',
                'route' => 'faq.category',
                'parameters' => ['slug' => 'category'],
            ],
        ],
        'categories' => [
            [
                'key' => 'content.categories.index',
                'label' => 'Kategori Konten',
                'route' => 'categories.index',
            ],
        ],
        'tags' => [
            [
                'key' => 'content.tags.index',
                'label' => 'Tag Konten',
                'route' => 'tags.index',
            ],
        ],
        'search' => [
            [
                'key' => 'content.search.index',
                'label' => 'Pencarian',
                'route' => 'search.index',
            ],
        ],
        'pages' => [
            [
                'key' => 'content.pages.show',
                'label' => 'Halaman Dinamis',
                'route' => 'pages.show',
                'parameters' => ['slug' => 'page'],
            ],
        ],
        'support' => [
            [
                'key' => 'content.support.index',
                'label' => 'Pusat Bantuan',
                'route' => 'support.index',
            ],
            [
                'key' => 'content.support.knowledge-base',
                'label' => 'Basis Pengetahuan',
                'route' => 'support.knowledge-base',
            ],
            [
                'key' => 'content.support.search',
                'label' => 'Pencarian Bantuan',
                'route' => 'support.search',
            ],
            [
                'key' => 'content.support.tickets.create',
                'label' => 'Buat Tiket',
                'route' => 'support.tickets.create',
            ],
            [
                'key' => 'content.support.tickets.show',
                'label' => 'Detail Tiket',
                'route' => 'support.tickets.show',
                'parameters' => ['id' => 1],
            ],
        ],
    ],
    'contact' => [
        [
            'key' => 'contact.index',
            'label' => 'Kontak',
            'route' => 'contact',
        ],
        [
            'key' => 'contact.success',
            'label' => 'Kontak Berhasil',
            'route' => 'contact.success',
        ],
    ],
    'newsletter' => [
        [
            'key' => 'newsletter.subscribe',
            'label' => 'Berlangganan Newsletter',
            'route' => 'newsletter.subscribe',
        ],
        [
            'key' => 'newsletter.success',
            'label' => 'Berhasil Berlangganan',
            'route' => 'newsletter.success',
        ],
        [
            'key' => 'newsletter.unsubscribe',
            'label' => 'Berhenti Berlangganan',
            'route' => 'newsletter.unsubscribe',
        ],
    ],
    'cookies' => [
        [
            'key' => 'cookies.preferences',
            'label' => 'Preferensi Cookie',
            'route' => 'cookies.preferences',
        ],
    ],
    'invitation' => [
        [
            'key' => 'invitation.show',
            'label' => 'Undangan',
            'route' => 'invitation',
            'auth' => true,
        ],
    ],
    'system' => [
        [
            'key' => 'system.status',
            'label' => 'Status Sistem',
            'route' => 'status.index',
        ],
    ],
    'legal' => [
        [
            'key' => 'legal.privacy-policy',
            'label' => 'Kebijakan Privasi',
            'route' => 'legal.privacy-policy',
        ],
        [
            'key' => 'legal.terms-of-service',
            'label' => 'Syarat dan Ketentuan',
            'route' => 'legal.terms-of-service',
        ],
        [
            'key' => 'legal.cookie-policy',
            'label' => 'Kebijakan Cookie',
            'route' => 'legal.cookie-policy',
        ],
        [
            'key' => 'legal.acceptable-use-policy',
            'label' => 'Kebijakan Penggunaan',
            'route' => 'legal.acceptable-use-policy',
        ],
        [
            'key' => 'legal.accessibility-statement',
            'label' => 'Pernyataan Aksesibilitas',
            'route' => 'legal.accessibility-statement',
        ],
        [
            'key' => 'legal.copyright',
            'label' => 'Hak Cipta',
            'route' => 'legal.copyright',
        ],
        [
            'key' => 'legal.data-processing-policy',
            'label' => 'Kebijakan Pemrosesan Data',
            'route' => 'legal.data-processing-policy',
        ],
        [
            'key' => 'legal.disclaimer',
            'label' => 'Penafian',
            'route' => 'legal.disclaimer',
        ],
        [
            'key' => 'legal.refund-policy',
            'label' => 'Kebijakan Pengembalian Dana',
            'route' => 'legal.refund-policy',
        ],
    ],
    'settings' => [
        'filter_missing_routes' => true,
        'filter_by_access' => true,
        'hide_dynamic_routes_without_parameters' => true,
        'header_max_items' => 6,
        'footer_max_items' => 0,
        'show_icons' => true,
        'show_active_state' => true,
    ],
];
