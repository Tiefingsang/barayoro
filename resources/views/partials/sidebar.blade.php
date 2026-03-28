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
        <li>
          <a href="{{ route('ecommerce') }}" class="menu-link vertical-menu">
            <i class="las la-shopping-bag text-xl text-primary-300"></i>
            <span>E-commerce</span>
          </a>
        </li>
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
                <a href="" class="dropdown-link submenu-link-v">Modifier</a>
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
                <a href="{{ route('products.grid') }}" class="dropdown-link submenu-link-v">Grille produits</a>
              </li>
              <li>
                <a href="{{ route('products.list') }}" class="dropdown-link submenu-link-v">Liste produits</a>
              </li>
              <li>
                <a href="" class="dropdown-link submenu-link-v">Détails produit</a>
              </li>
              <li>
                <a href="{{ route('products.create') }}" class="dropdown-link submenu-link-v">Créer produit</a>
              </li>
              <li>
                <a href="" class="dropdown-link submenu-link-v">Modifier produit</a>
              </li>
              <li>
                <a href="{{ route('reviews.manage') }}" class="dropdown-link submenu-link-v">Gérer les avis</a>
              </li>
              <li>
                <a href="{{ route('referrals') }}" class="dropdown-link submenu-link-v">Parrainages</a>
              </li>
              <li>
                <a href="{{ route('checkout') }}" class="dropdown-link submenu-link-v">Paiement</a>
              </li>
              <li>
                <a href="{{ route('checkout.success') }}" class="dropdown-link submenu-link-v">Paiement réussi</a>
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
                <a href="{{ route('orders.list') }}" class="dropdown-link submenu-link-v">Liste des commandes</a>
              </li>
              <li>
                <a href="" class="dropdown-link submenu-link-v">Détails commande</a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Gestion des factures -->
        <li class="relative">
          <button :class="opened=='invoice' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('invoice')" class="submenu-btn-v">
            <span class="flex items-center gap-2">
              <i class="las la-file-invoice text-xl text-primary-300"></i>
              <span>Factures</span>
            </span>
            <i :class="opened=='invoice' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
          </button>
          <div x-show="opened=='invoice'" x-collapse>
            <ul class="submenu-v" data-submenu="invoice">
              <li>
                <a href="" class="dropdown-link submenu-link-v">Liste des factures</a>
              </li>
              <li>
                <a href="" class="dropdown-link submenu-link-v">Détails facture</a>
              </li>
              <li>
                <a href="{{ route('invoices.create') }}" class="dropdown-link submenu-link-v">Créer facture</a>
              </li>
              <li>
                <a href="" class="dropdown-link submenu-link-v">Modifier facture</a>
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
          <a href="{{ route('mail') }}" class="menu-link vertical-menu">
            <i class="las la-envelope-open-text text-xl text-primary-300"></i>
            <span>Messagerie</span>
          </a>
        </li>
        <li>
          <a href="{{ route('chat') }}" class="menu-link vertical-menu">
            <i class="lab la-facebook-messenger text-xl text-primary-300"></i>
            <span>Chat</span>
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

      <p class="text-xs font-semibold mb-3 mt-5">PAGES</p>
      <ul class="flex flex-col gap-2 bb-dashed-n30 xl:mb-5 xl:pb-5 m-text font-medium">



        <!-- Offres d'emploi -->
        <li class="relative">
          <button :class="opened=='job' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('job')" class="submenu-btn-v">
            <span class="flex items-center gap-2">
              <i class="las la-briefcase text-xl text-primary-300"></i>
              <span>Offres d'emploi</span>
            </span>
            <i :class="opened=='job' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
          </button>
          <div x-show="opened=='job'" x-collapse>
            <ul class="submenu-v" data-submenu="job">
              <li>
                <a href="{{ route('jobs.list') }}" class="dropdown-link submenu-link-v">Liste des offres</a>
              </li>
              <li>
                <a href="" class="dropdown-link submenu-link-v">Détails offre</a>
              </li>
              <li>
                <a href="{{ route('jobs.create') }}" class="dropdown-link submenu-link-v">Créer offre</a>
              </li>
              <li>
                <a href="" class="dropdown-link submenu-link-v">Modifier offre</a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Tours et voyages -->
        <li class="relative">
          <button :class="opened=='tour' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('tour')" class="submenu-btn-v">
            <span class="flex items-center gap-2">
              <i class="las la-map-marked-alt text-xl text-primary-300"></i>
              <span>Tours et voyages</span>
            </span>
            <i :class="opened=='tour' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
          </button>
          <div x-show="opened=='tour'" x-collapse>
            <ul class="submenu-v" data-submenu="tour">
              <li>
                <a href="{{ route('tours.list') }}" class="dropdown-link submenu-link-v">Liste des tours</a>
              </li>
              <li>
                <a href="" class="dropdown-link submenu-link-v">Détails tour</a>
              </li>
              <li>
                <a href="{{ route('tours.create') }}" class="dropdown-link submenu-link-v">Créer tour</a>
              </li>
              <li>
                <a href="" class="dropdown-link submenu-link-v">Modifier tour</a>
              </li>
            </ul>
          </div>
        </li>

        <!-- Pages diverses -->
        <li class="relative">
          <button :class="opened=='pages' ? 'bg-primary-50 text-primary-300' : ''" @click="openMenu('pages')" class="submenu-btn-v">
            <span class="flex items-center gap-2">
              <i class="las la-book-open text-xl text-primary-300"></i>
              <span>Pages</span>
            </span>
            <i :class="opened=='pages' ? 'las la-minus rotate-180 text-primary-300' : 'las la-plus'" class="text-lg duration-300"></i>
          </button>
          <div x-show="opened=='pages'" x-collapse>
            <ul class="submenu-v" data-submenu="pages">
              <li>
                <a href="{{ route('about') }}" class="dropdown-link submenu-link-v">À propos</a>
              </li>
              <li>
                <a href="{{ route('contact') }}" class="dropdown-link submenu-link-v">Contact</a>
              </li>
              <li>
                <a href="{{ route('faq') }}" class="dropdown-link submenu-link-v">FAQ</a>
              </li>
              <li>
                <a href="{{ route('pricing') }}" class="dropdown-link submenu-link-v">Tarifs</a>
              </li>
              <li>
                <a href="{{ route('payment.page') }}" class="dropdown-link submenu-link-v">Paiement</a>
              </li>
              <li>
                <a href="{{ route('maintenance') }}" class="dropdown-link submenu-link-v">Maintenance</a>
              </li>
              <li>
                <a href="{{ route('coming.soon') }}" class="dropdown-link submenu-link-v">Bientôt disponible</a>
              </li>
            </ul>
          </div>
        </li>




      </ul>

      <p class="text-xs font-semibold mb-3 mt-5">COMPOSANTS</p>
      <ul class="flex flex-col gap-2 bb-dashed-n30 xl:mb-5 xl:pb-5 m-text font-medium">









      </ul>
    </div>
  </aside>
