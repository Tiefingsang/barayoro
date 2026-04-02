<aside
    :class="[$store.app.sidebar?'translate-x-0':'ltr:-translate-x-full rtl:translate-x-full',
    $store.app.menu == 'vertical'?'block':'hidden', $store.app.menu == 'horizontal'?'max-xl:block':'']"
    class="fixed top-0 z-[12] h-full w-[280px] bg-neutral-0 duration-300 dark:bg-neutral-904 ltr:left-0 rtl:right-0"
  >
    <div class="px-3 xxl:px-4 pt-3 sm:pt-4">
      <a href="{{ route('dashboard') }}" class="text-primary-300 flex gap-3 items-center bb-dashed-n30 xl:pb-3.5 !mb-0">
        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
          <!-- Logo SVG -->
        </svg>
        <span class="h4 shrink-0 text-neutral-700 dark:text-neutral-0">Barayoro</span>
      </a>
    </div>
    <div
      x-data="{
          opened:null,
          openMenu(name){
          this.opened==name ? this.opened = null : this.opened=name
          },
          setActiveMenu(){
          const submenus = document.querySelectorAll('.submenu-link-v')
          const sidebar= document.querySelector('.vertical-sidebar')
          submenus.forEach((submenu) => {
          const currentUrl = window.location.href
          const href = submenu.getAttribute('href')
          const cleanHref = href.replace(/^\.\.\//, '')

          const url = new URL(currentUrl);
          const filename = url.pathname.split('/').pop();

          if (filename==cleanHref) {
              submenu.classList.add('text-primary-300')
              const sidebarRect = sidebar.getBoundingClientRect()
              const elementRect = submenu.getBoundingClientRect()
              const offsetTop = elementRect.top - sidebarRect.top;

              const scrollPosition = offsetTop - (sidebarRect.height / 2) + (elementRect.height / 2);

              sidebar.scrollTo({
                  top: scrollPosition*35/100,
                  behavior: 'smooth'
              });
              const submenuName = submenu.parentElement.parentElement.getAttribute('data-submenu')
              this.opened = submenuName
          }
          })
    }
    }"
      x-init="setActiveMenu"
      class="overflow-y-auto h-full px-3 xxl:px-4 pb-6 custom-scrollbar-hovered pt-4 vertical-sidebar"
    >
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
      {{--   <li>
          <a href="{{ route('ecommerce') }}" class="menu-link vertical-menu">
            <i class="las la-shopping-bag text-xl text-primary-300"></i>
            <span>E-commerce</span>
          </a>
        </li> --}}
        <li>
          <a href="{{ route('finance.index') }}" class="menu-link vertical-menu">
            <i class="las la-piggy-bank text-xl text-primary-300"></i>
            <span>Finance</span>
          </a>
        </li>

        <li>
          <a href="{{ route('files') }}" class="menu-link vertical-menu">
            <i class="las la-file text-xl text-primary-300"></i>
            <span>Fichiers</span>
          </a>
        </li>
      </ul>

      <p class="text-xs font-semibold mb-3 mt-5">OPÉRATIONS</p>
      <ul class="flex flex-col gap-2 bb-dashed-n30 xl:mb-5 xl:pb-5 m-text font-medium">

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
              <li>
                <a href="" class="dropdown-link submenu-link-v">Profil</a>
              </li>
              <li>
                <a href="" class="dropdown-link submenu-link-v">Cartes</a>
              </li>
              <li>
                <a href="{{ route('users.index') }}" class="dropdown-link submenu-link-v">Liste</a>
              </li>
              <li>
                <a href="{{ route('users.create') }}" class="dropdown-link submenu-link-v">Créer</a>
              </li>

              <li>
                <a href="{{ route('users.index') }}" class="dropdown-link submenu-link-v">Compte</a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Gestion du magasin -->
        <li class="relative">
          <button :class="opened=='store' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('store')" class="submenu-btn-v">
            <span class="flex items-center gap-2">
              <i class="las la-store text-xl text-primary-300"></i>
              <span>Boutique</span>
            </span>
            <i :class="opened=='store' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
          </button>
          <div x-show="opened=='store'" x-collapse>
            <ul class="submenu-v" data-submenu="store">

              <li>
                <a href="{{ route('products.index') }}" class="dropdown-link submenu-link-v">Liste produits</a>
              </li>

              <li>
                <a href="{{ route('products.create') }}" class="dropdown-link submenu-link-v">Créer produit</a>
              </li>
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
                    <li>
                        <a href="{{ route('clients.index') }}" class="dropdown-link submenu-link-v">Liste des clients</a>
                    </li>
                    <li>
                        <a href="{{ route('clients.create') }}" class="dropdown-link submenu-link-v">Ajouter un client</a>
                    </li>
                </ul>
            </div>
        </li>


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
                    <li>
                        <a href="{{ route('invoices.index') }}" class="dropdown-link submenu-link-v">Liste des factures</a>
                    </li>
                    <li>
                        <a href="{{ route('invoices.create') }}" class="dropdown-link submenu-link-v">Créer une facture</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Gestion des commandes -->
        <li class="relative">
          <button :class="opened=='order' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('order')" class="submenu-btn-v">
            <span class="flex items-center gap-2">
              <i class="las la-shopping-cart text-xl text-primary-300"></i>
              <span>Commandes</span>
            </span>
            <i :class="opened=='order' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
          </button>
          <div x-show="opened=='order'" x-collapse>
            <ul class="submenu-v" data-submenu="order">
              <li>
                <a href="{{ route('orders.index') }}" class="dropdown-link submenu-link-v">Liste des commandes</a>
              </li>
              <li>
                <a href="" class="dropdown-link submenu-link-v">Détails commande</a>
              </li>
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
                <li>
                    <a href="{{ route('departments.index') }}" class="dropdown-link submenu-link-v">Liste des départements</a>
                </li>
                <li>
                    <a href="{{ route('departments.create') }}" class="dropdown-link submenu-link-v">Ajouter un département</a>
                </li>
            </ul>
        </div>
    </li>


        <!-- Projets -->
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
                <li>
                    <a href="{{ route('projects.index') }}" class="dropdown-link submenu-link-v">Liste des projets</a>
                </li>
                <li>
                    <a href="{{ route('projects.create') }}" class="dropdown-link submenu-link-v">Ajouter un projet</a>
                </li>
                <li>
                    <a href="{{ route('tasks.index', ['view' => 'all']) }}" class="dropdown-link submenu-link-v">Toutes les tâches</a>
                </li>
            </ul>
        </div>
    </li>




        <li>
          <a href="" class="menu-link vertical-menu">
            <i class="las la-file text-xl text-primary-300"></i>
            <span>Gestionnaire de fichiers</span>
          </a>
        </li>


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
      </ul>










      <p class="text-xs font-semibold mb-3 mt-5">COMPOSANTS</p>
      <ul class="flex flex-col gap-2 bb-dashed-n30 xl:mb-5 xl:pb-5 m-text font-medium">









      </ul>
    </div>
  </aside>
