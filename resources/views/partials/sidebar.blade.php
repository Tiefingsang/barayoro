<aside
    :class="[$store.app.sidebar?'translate-x-0':'ltr:-translate-x-full rtl:translate-x-full',
    $store.app.menu == 'vertical'?'block':'hidden', $store.app.menu == 'horizontal'?'max-xl:block':'']"
    class="fixed top-0 z-[12] h-full w-[280px] bg-neutral-0 duration-300 dark:bg-neutral-904 ltr:left-0 rtl:right-0"
>
    <div class="px-3 xxl:px-4 pt-3 sm:pt-4">
        <a href="{{ route('dashboard') }}" class="text-primary-300 flex gap-3 items-center bb-dashed-n30 xl:pb-3.5 !mb-0">
            <img src="{{ asset('assets/images/Barayoro_logo.png') }}" 
                 alt="Barayoro Logo" 
                 width="36" 
                 height="36"
                 class="object-contain dark:hidden">
            <img src="{{ asset('assets/images/Barayoro_logo.png') }}" 
                 alt="Barayoro Logo" 
                 width="36" 
                 height="36"
                 class="object-contain hidden dark:block">
            <span class="h4 shrink-0 text-neutral-700 dark:text-neutral-0">Barayoro</span>
        </a>
    </div>

    <div x-data="{
        opened: null,
        openMenu(name) {
            this.opened == name ? this.opened = null : this.opened = name
        },
        setActiveMenu() {
            const submenus = document.querySelectorAll('.submenu-link-v')
            const sidebar = document.querySelector('.vertical-sidebar')
            submenus.forEach((submenu) => {
                const currentUrl = window.location.href
                const href = submenu.getAttribute('href')
                const cleanHref = href.replace(/^\.\.\//, '')
                const url = new URL(currentUrl);
                const filename = url.pathname.split('/').pop();
                if (filename == cleanHref) {
                    submenu.classList.add('text-primary-300')
                    const sidebarRect = sidebar.getBoundingClientRect()
                    const elementRect = submenu.getBoundingClientRect()
                    const offsetTop = elementRect.top - sidebarRect.top;
                    const scrollPosition = offsetTop - (sidebarRect.height / 2) + (elementRect.height / 2);
                    sidebar.scrollTo({
                        top: scrollPosition * 35 / 100,
                        behavior: 'smooth'
                    });
                    const submenuName = submenu.parentElement.parentElement.getAttribute('data-submenu')
                    this.opened = submenuName
                }
            })
        }
    }" x-init="setActiveMenu" class="overflow-y-auto h-full px-3 xxl:px-4 pb-6 custom-scrollbar-hovered pt-4 vertical-sidebar">

        <!-- MENU PRINCIPAL -->
        <p class="text-xs font-semibold mb-3">MENU PRINCIPAL</p>
        <ul class="flex flex-col gap-2 bb-dashed-n30 xl:mb-5 xl:pb-5 text-sm font-medium">
            <li>
                <a href="{{ route('dashboard') }}" class="menu-link vertical-menu">
                    <i class="las la-tachometer-alt text-xl text-primary-300"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>
            <li>
                <a href="{{ route('analytics') }}" class="menu-link vertical-menu">
                    <i class="las la-chart-bar text-xl text-primary-300"></i>
                    <span>Analytiques</span>
                </a>
            </li>
            <li>
                <a href="{{ route('finance.index') }}" class="menu-link vertical-menu">
                    <i class="las la-piggy-bank text-xl text-primary-300"></i>
                    <span>Finance</span>
                </a>
            </li>
            <li>
                <a href="{{ route('files.index') }}" class="menu-link vertical-menu">
                    <i class="las la-file text-xl text-primary-300"></i>
                    <span>Fichiers</span>
                </a>
            </li>
        </ul>

        <!-- OPÉRATIONS -->
        <p class="text-xs font-semibold mb-3 mt-5">OPÉRATIONS</p>
        <ul class="flex flex-col gap-2 bb-dashed-n30 xl:mb-5 xl:pb-5 text-sm font-medium">

            <!-- Gestion des utilisateurs -->
            <li class="relative">
                <button :class="opened=='user' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('user')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-user-alt text-xl text-primary-300"></i>
                        <span>Utilisateurs</span>
                    </span>
                    <i :class="opened=='user' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='user'" x-collapse>
                    <ul class="submenu-v" data-submenu="user">
                        <li><a href="{{ route('users.index') }}" class="dropdown-link submenu-link-v">Liste des utilisateurs</a></li>
                        <li><a href="{{ route('users.create') }}" class="dropdown-link submenu-link-v">Ajouter un utilisateur</a></li>
                        <li><a href="{{ route('profile.edit') }}" class="dropdown-link submenu-link-v">Mon profil</a></li>
                    </ul>
                </div>
            </li>

            <!-- Clients -->
            <li class="relative">
                <button :class="opened=='clients' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('clients')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-users text-xl text-primary-300"></i>
                        <span>Clients</span>
                    </span>
                    <i :class="opened=='clients' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='clients'" x-collapse>
                    <ul class="submenu-v" data-submenu="clients">
                        <li><a href="{{ route('clients.index') }}" class="dropdown-link submenu-link-v">Liste des clients</a></li>
                        <li><a href="{{ route('clients.create') }}" class="dropdown-link submenu-link-v">Ajouter un client</a></li>
                    </ul>
                </div>
            </li>

            <!-- Produits / Boutique -->
            <li class="relative">
                <button :class="opened=='products' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('products')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-store text-xl text-primary-300"></i>
                        <span>Produits</span>
                    </span>
                    <i :class="opened=='products' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='products'" x-collapse>
                    <ul class="submenu-v" data-submenu="products">
                        <li><a href="{{ route('products.index') }}" class="dropdown-link submenu-link-v">Liste des produits</a></li>
                        <li><a href="{{ route('products.create') }}" class="dropdown-link submenu-link-v">Ajouter un produit</a></li>
                        <li><a href="{{ route('products.index', ['low_stock' => true]) }}" class="dropdown-link submenu-link-v">Stock faible</a></li>
                    </ul>
                </div>
            </li>

            <!-- Commandes -->
            <li class="relative">
                <button :class="opened=='orders' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('orders')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-shopping-cart text-xl text-primary-300"></i>
                        <span>Commandes</span>
                    </span>
                    <i :class="opened=='orders' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='orders'" x-collapse>
                    <ul class="submenu-v" data-submenu="orders">
                        <li><a href="{{ route('orders.index') }}" class="dropdown-link submenu-link-v">Toutes les commandes</a></li>
                        <li><a href="{{ route('orders.index', ['status' => 'pending']) }}" class="dropdown-link submenu-link-v">Commandes en attente</a></li>
                        <li><a href="{{ route('orders.index', ['status' => 'delivered']) }}" class="dropdown-link submenu-link-v">Commandes livrées</a></li>
                    </ul>
                </div>
            </li>

            <!-- Factures -->
            <li class="relative">
                <button :class="opened=='invoices' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('invoices')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-file-invoice text-xl text-primary-300"></i>
                        <span>Factures</span>
                    </span>
                    <i :class="opened=='invoices' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='invoices'" x-collapse>
                    <ul class="submenu-v" data-submenu="invoices">
                        <li><a href="{{ route('invoices.index') }}" class="dropdown-link submenu-link-v">Liste des factures</a></li>
                        <li><a href="{{ route('invoices.create') }}" class="dropdown-link submenu-link-v">Créer une facture</a></li>
                        <li><a href="{{ route('invoices.index', ['status' => 'paid']) }}" class="dropdown-link submenu-link-v">Factures payées</a></li>
                        <li><a href="{{ route('invoices.index', ['status' => 'pending']) }}" class="dropdown-link submenu-link-v">Factures impayées</a></li>
                    </ul>
                </div>
            </li>

            <!-- Paiements -->
            <li class="relative">
                <button :class="opened=='payments' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('payments')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-credit-card text-xl text-primary-300"></i>
                        <span>Paiements</span>
                    </span>
                    <i :class="opened=='payments' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='payments'" x-collapse>
                    <ul class="submenu-v" data-submenu="payments">
                        <li><a href="{{ route('payments.index') }}" class="dropdown-link submenu-link-v">Historique des paiements</a></li>
                        <li><a href="{{ route('payments.create') }}" class="dropdown-link submenu-link-v">Enregistrer un paiement</a></li>
                    </ul>
                </div>
            </li>

            <!-- Dépenses -->
            <li class="relative">
                <button :class="opened=='expenses' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('expenses')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-wallet text-xl text-primary-300"></i>
                        <span>Dépenses</span>
                    </span>
                    <i :class="opened=='expenses' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='expenses'" x-collapse>
                    <ul class="submenu-v" data-submenu="expenses">
                        <li><a href="{{ route('expenses.index') }}" class="dropdown-link submenu-link-v">Liste des dépenses</a></li>
                        <li><a href="{{ route('expenses.create') }}" class="dropdown-link submenu-link-v">Ajouter une dépense</a></li>
                        <li><a href="{{ route('expense-categories.index') }}" class="dropdown-link submenu-link-v">Catégories de dépenses</a></li>
                    </ul>
                </div>
            </li>

            <!-- Départements -->
            <li class="relative">
                <button :class="opened=='departments' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('departments')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-building text-xl text-primary-300"></i>
                        <span>Départements</span>
                    </span>
                    <i :class="opened=='departments' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='departments'" x-collapse>
                    <ul class="submenu-v" data-submenu="departments">
                        <li><a href="{{ route('departments.index') }}" class="dropdown-link submenu-link-v">Liste des départements</a></li>
                        <li><a href="{{ route('departments.create') }}" class="dropdown-link submenu-link-v">Ajouter un département</a></li>
                    </ul>
                </div>
            </li>

            <!-- Projets -->
            <li class="relative">
                <button :class="opened=='projects' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('projects')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-project-diagram text-xl text-primary-300"></i>
                        <span>Projets</span>
                    </span>
                    <i :class="opened=='projects' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='projects'" x-collapse>
                    <ul class="submenu-v" data-submenu="projects">
                        <li><a href="{{ route('projects.index') }}" class="dropdown-link submenu-link-v">Liste des projets</a></li>
                        <li><a href="{{ route('projects.create') }}" class="dropdown-link submenu-link-v">Ajouter un projet</a></li>
                        <li><a href="{{ route('tasks.index') }}" class="dropdown-link submenu-link-v">Toutes les tâches</a></li>
                    </ul>
                </div>
            </li>

            <!-- Tâches -->
            <li class="relative">
                <button :class="opened=='tasks' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('tasks')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-tasks text-xl text-primary-300"></i>
                        <span>Tâches</span>
                    </span>
                    <i :class="opened=='tasks' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='tasks'" x-collapse>
                    <ul class="submenu-v" data-submenu="tasks">
                        <li><a href="{{ route('tasks.index') }}" class="dropdown-link submenu-link-v">Toutes les tâches</a></li>
                        <li><a href="{{ route('tasks.create') }}" class="dropdown-link submenu-link-v">Créer une tâche</a></li>
                        <li><a href="{{ route('tasks.index', ['status' => 'pending']) }}" class="dropdown-link submenu-link-v">Tâches en attente</a></li>
                    </ul>
                </div>
            </li>

            <!-- Blog -->
           <li class="relative">
    <button :class="opened=='blog' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('blog')" class="submenu-btn-v">
        <span class="flex items-center gap-2">
            <i class="las la-newspaper text-xl text-primary-300"></i>
            <span>Blog</span>
        </span>
        <i :class="opened=='blog' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
    </button>
    <div x-show="opened=='blog'" x-collapse>
        <ul class="submenu-v" data-submenu="blog">
            <li><a href="{{ route('blog.list') }}" class="dropdown-link submenu-link-v">Articles publiés</a></li>
            <li><a href="{{ route('blog.create') }}" class="dropdown-link submenu-link-v">Ajouter un article</a></li>
            <li><a href="{{ route('blog.index') }}" class="dropdown-link submenu-link-v">Gérer le Blog</a></li>
        </ul>
    </div>
</li>

            <!-- Offres d'emploi -->
            <li class="relative">
                <button :class="opened=='jobs' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('jobs')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-briefcase text-xl text-primary-300"></i>
                        <span>Offres d'emploi</span>
                    </span>
                    <i :class="opened=='jobs' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='jobs'" x-collapse>
                    <ul class="submenu-v" data-submenu="jobs">
                        <li><a href="{{ route('jobs.list') }}" class="dropdown-link submenu-link-v">Offres publiées</a></li>
                        <li><a href="{{ route('jobs.create') }}" class="dropdown-link submenu-link-v">Ajouter une offre</a></li>
                        <li><a href="{{ route('jobs.applications') }}" class="dropdown-link submenu-link-v">Candidatures reçues</a></li>
                    </ul>
                </div>
            </li>

            <!-- Tours -->
            <li class="relative">
                <button :class="opened=='tours' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('tours')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-map-marked-alt text-xl text-primary-300"></i>
                        <span>Tours</span>
                    </span>
                    <i :class="opened=='tours' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='tours'" x-collapse>
                    <ul class="submenu-v" data-submenu="tours">
                        <li><a href="{{ route('tours.list') }}" class="dropdown-link submenu-link-v">Liste des tours</a></li>
                        <li><a href="{{ route('tours.create') }}" class="dropdown-link submenu-link-v">Ajouter un tour</a></li>
                    </ul>
                </div>
            </li>

            <!-- Avis -->
            <li class="relative">
                <button :class="opened=='reviews' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('reviews')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-star text-xl text-primary-300"></i>
                        <span>Avis clients</span>
                    </span>
                    <i :class="opened=='reviews' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='reviews'" x-collapse>
                    <ul class="submenu-v" data-submenu="reviews">
                        <li><a href="{{ route('reviews.manage') }}" class="dropdown-link submenu-link-v">Gérer les avis</a></li>
                    </ul>
                </div>
            </li>

            <!-- Parrainage -->
            <li class="relative">
                <a href="{{ route('referrals') }}" class="menu-link vertical-menu">
                    <i class="las la-gift text-xl text-primary-300"></i>
                    <span>Parrainage</span>
                </a>
            </li>
        </ul>

        <!-- OUTILS -->
        <p class="text-xs font-semibold mb-3 mt-5">OUTILS</p>
        <ul class="flex flex-col gap-2 bb-dashed-n30 xl:mb-5 xl:pb-5 text-sm font-medium">
            <li>
                <a href="{{ route('calendar') }}" class="menu-link vertical-menu">
                    <i class="las la-calendar-alt text-xl text-primary-300"></i>
                    <span>Calendrier</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kanban') }}" class="menu-link vertical-menu">
                    <i class="las la-table text-xl text-primary-300"></i>
                    <span>Kanban</span>
                </a>
            </li>
            <li>
                <a href="{{ route('chat') }}" class="menu-link vertical-menu">
                    <i class="las la-comment-dots text-xl text-primary-300"></i>
                    <span>Chat</span>
                </a>
            </li>
            
        </ul>

        <!-- RAPPORTS -->
        <p class="text-xs font-semibold mb-3 mt-5">RAPPORTS</p>
        <ul class="flex flex-col gap-2 text-sm font-medium">
            <li class="relative">
                <button :class="opened=='reports' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('reports')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-chart-pie text-xl text-primary-300"></i>
                        <span>Rapports</span>
                    </span>
                    <i :class="opened=='reports' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='reports'" x-collapse>
                    <ul class="submenu-v" data-submenu="reports">
                        <li><a href="{{ route('reports.daily') }}" class="dropdown-link submenu-link-v">Rapport journalier</a></li>
                        <li><a href="{{ route('reports.weekly') }}" class="dropdown-link submenu-link-v">Rapport hebdomadaire</a></li>
                        <li><a href="{{ route('reports.monthly') }}" class="dropdown-link submenu-link-v">Rapport mensuel</a></li>
                        <li><a href="{{ route('reports.annual') }}" class="dropdown-link submenu-link-v">Rapport annuel</a></li>
                        <li><a href="{{ route('reports.custom') }}" class="dropdown-link submenu-link-v">Rapport personnalisé</a></li>
                    </ul>
                </div>
            </li>
        </ul>

                <p class="text-xs font-semibold mb-3 mt-5">Fin</p>

        <!-- ADMINISTRATION -->
        @can('access_admin')
        <p class="text-xs font-semibold mb-3 mt-5">ADMINISTRATION</p>
        <ul class="flex flex-col gap-2 text-sm font-medium">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="menu-link vertical-menu">
                    <i class="las la-shield-alt text-xl text-primary-300"></i>
                    <span>Admin Dashboard</span>
                </a>
            </li>
            <li class="relative">
                <button :class="opened=='roles' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('roles')" class="submenu-btn-v">
                    <span class="flex items-center gap-2">
                        <i class="las la-lock text-xl text-primary-300"></i>
                        <span>Rôles & Permissions</span>
                    </span>
                    <i :class="opened=='roles' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
                </button>
                <div x-show="opened=='roles'" x-collapse>
                    <ul class="submenu-v" data-submenu="roles">
                        <li><a href="{{ route('admin.roles.index') }}" class="dropdown-link submenu-link-v">Gérer les rôles</a></li>
                        <li><a href="{{ route('admin.permissions.index') }}" class="dropdown-link submenu-link-v">Gérer les permissions</a></li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="{{ route('admin.logs') }}" class="menu-link vertical-menu">
                    <i class="las la-history text-xl text-primary-300"></i>
                    <span>Logs système</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.backups') }}" class="menu-link vertical-menu">
                    <i class="las la-database text-xl text-primary-300"></i>
                    <span>Sauvegardes</span>
                </a>
            </li>
        </ul>
        @endcan
    </div>
</aside>