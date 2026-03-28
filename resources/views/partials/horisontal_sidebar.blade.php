<nav :class="$store.app.menu=='horizontal'?'hidden xl:block':'hidden'" class="fixed z-[9] px-4 top-0 left-0 right-0 w-full mx-auto bg-neutral-0 dark:bg-neutral-904 mt-[60px] md:mt-[66px]">
    <div class="max-w-[1704px] mx-auto">
      <ul class="flex gap-5 items-center">
        <li class="relative group">
          <button class="inline-flex py-3 text-sm font-medium items-center gap-2">Admin <i class="las la-plus group-hover:hidden text-lg"></i><i class="las la-minus hidden text-lg group-hover:inline-block"></i></button>
          <ul class="submenu-horiz">
            <li><a href="{{ route('dashboard') }}" class="link-horiz menu-link-horiz">HOME</a></li>

            <li><a href="e-commerce.html" class="link-horiz menu-link-horiz">E-commerce</a></li>
            <li><a href="analytics.html" class="link-horiz menu-link-horiz">Analytics</a></li>
            <li><a href="{{ route('finance.index') }}" class="link-horiz menu-link-horiz">Finance</a></li>
            <li><a href="booking.html" class="link-horiz menu-link-horiz">Booking</a></li>
            <li><a href="file.html" class="link-horiz menu-link-horiz">File</a></li>
          </ul>
        </li>
        <li class="relative group">
          <button class="inline-flex py-3 text-sm font-medium items-center gap-2">User <i class="las la-plus group-hover:hidden text-lg"></i><i class="las la-minus hidden text-lg group-hover:inline-block"></i></button>
          <ul class="submenu-horiz">
            <li><a href="user-profile.html" class="link-horiz menu-link-horiz">Profile</a></li>
            <li><a href="user-cards.html" class="link-horiz menu-link-horiz">Cards</a></li>
            <li><a href="user-list.html" class="link-horiz menu-link-horiz">List</a></li>
            <li><a href="create-user.html" class="link-horiz menu-link-horiz">Create</a></li>
            <li><a href="edit-user.html" class="link-horiz menu-link-horiz">Edit</a></li>
            <li><a href="user-account.html" class="link-horiz menu-link-horiz">Account</a></li>
          </ul>
        </li>
        <li class="relative group">
          <button class="inline-flex py-3 text-sm font-medium items-center gap-2">Product <i class="las la-plus group-hover:hidden text-lg"></i><i class="las la-minus hidden text-lg group-hover:inline-block"></i></button>
          <ul class="submenu-horiz">
            <li><a href="product-list.html" class="link-horiz menu-link-horiz">List</a></li>
            <li><a href="product-details.html" class="link-horiz menu-link-horiz">Details</a></li>
            <li><a href="create-product.html" class="link-horiz menu-link-horiz">Create</a></li>
            <li><a href="edit-product.html" class="link-horiz menu-link-horiz">Edit</a></li>
            <li><a href="manage-review.html" class="link-horiz menu-link-horiz">Manage Review</a></li>
            <li><a href="referrals.html" class="link-horiz menu-link-horiz">Referrals</a></li>
          </ul>
        </li>
        <li class="relative group">
          <button class="inline-flex py-3 text-sm font-medium items-center gap-2">Order <i class="las la-plus group-hover:hidden text-lg"></i><i class="las la-minus hidden text-lg group-hover:inline-block"></i></button>
          <ul class="submenu-horiz">
            <li><a href="order-list.html" class="link-horiz menu-link-horiz">List</a></li>
            <li><a href="order-details.html" class="link-horiz menu-link-horiz">Details</a></li>
          </ul>
        </li>
        <li class="relative group">
          <button class="inline-flex py-3 text-sm font-medium items-center gap-2">Invoice <i class="las la-plus group-hover:hidden text-lg"></i><i class="las la-minus hidden text-lg group-hover:inline-block"></i></button>
          <ul class="submenu-horiz">
            <li><a href="invoice-list.html" class="link-horiz menu-link-horiz">List</a></li>
            <li><a href="invoice-details.html" class="link-horiz menu-link-horiz">Details</a></li>
            <li><a href="create-invoice.html" class="link-horiz menu-link-horiz">Create</a></li>
            <li><a href="edit-invoice.html" class="link-horiz menu-link-horiz">Edit</a></li>
          </ul>
        </li>
        <li class="relative group">
          <button class="inline-flex py-3 text-sm font-medium items-center gap-2">Blog <i class="las la-plus group-hover:hidden text-lg"></i><i class="las la-minus hidden text-lg group-hover:inline-block"></i></button>
          <ul class="submenu-horiz">
            <li><a href="blog-list.html" class="link-horiz menu-link-horiz">List</a></li>
            <li><a href="blog-details.html" class="link-horiz menu-link-horiz">Details</a></li>
            <li><a href="create-blog.html" class="link-horiz menu-link-horiz">Create</a></li>
            <li><a href="edit-blog.html" class="link-horiz menu-link-horiz">Edit</a></li>
          </ul>
        </li>
        <li class="relative group">
          <button class="inline-flex py-3 text-sm font-medium items-center gap-2">Job <i class="las la-plus group-hover:hidden text-lg"></i><i class="las la-minus hidden text-lg group-hover:inline-block"></i></button>
          <ul class="submenu-horiz">
            <li><a href="job-list.html" class="link-horiz menu-link-horiz">List</a></li>
            <li><a href="job-details.html" class="link-horiz menu-link-horiz">Details</a></li>
            <li><a href="create-job.html" class="link-horiz menu-link-horiz">Create</a></li>
            <li><a href="edit-job.html" class="link-horiz menu-link-horiz">Edit</a></li>
          </ul>
        </li>
        <li class="relative group">
          <button class="inline-flex py-3 text-sm font-medium items-center gap-2">Tour <i class="las la-plus group-hover:hidden text-lg"></i><i class="las la-minus hidden text-lg group-hover:inline-block"></i></button>
          <ul class="submenu-horiz">
            <li><a href="tour-list.html" class="link-horiz menu-link-horiz">List</a></li>
            <li><a href="tour-details.html" class="link-horiz menu-link-horiz">Details</a></li>
            <li><a href="create-tour.html" class="link-horiz menu-link-horiz">Create</a></li>
            <li><a href="edit-tour.html" class="link-horiz menu-link-horiz">Edit</a></li>
          </ul>
        </li>
        <li class="relative group">
          <button class="inline-flex py-3 text-sm font-medium items-center gap-2">Others <i class="las la-plus group-hover:hidden text-lg"></i><i class="las la-minus hidden text-lg group-hover:inline-block"></i></button>
          <ul class="submenu-horiz">
            <li><a href="file-manager.html" class="link-horiz menu-link-horiz">File Manager</a></li>
            <li><a href="mail.html" class="link-horiz menu-link-horiz">Mail</a></li>
            <li><a href="chat.html" class="link-horiz menu-link-horiz">Chat</a></li>
            <li><a href="calendar.html" class="link-horiz menu-link-horiz">Calendar</a></li>
            <li><a href="kanban.html" class="link-horiz menu-link-horiz">Kanban</a></li>
            <li><a href="roles.html" class="link-horiz menu-link-horiz">Roles</a></li>
            <li><a href="permissions.html" class="link-horiz menu-link-horiz">Permissions</a></li>
          </ul>
        </li>
        <li class="relative group">
          <button class="inline-flex py-3 text-sm font-medium items-center gap-2">Pages <i class="las la-plus group-hover:hidden text-lg"></i><i class="las la-minus hidden text-lg group-hover:inline-block"></i></button>
          <ul class="submenu-horiz">
            <li><a href="about-us.html" class="link-horiz menu-link-horiz">About Us</a></li>
            <li><a href="contact-us.html" class="link-horiz menu-link-horiz">Contact Us</a></li>
            <li><a href="faqs.html" class="link-horiz menu-link-horiz">FAQs</a></li>
            <li><a href="pricing-plan.html" class="link-horiz menu-link-horiz">Pricing Plan</a></li>
            <li><a href="payment.html" class="link-horiz menu-link-horiz">Payment</a></li>
            <li><a href="maintenance.html" class="link-horiz menu-link-horiz">Maintenance</a></li>
            <li><a href="coming-soon.html" class="link-horiz menu-link-horiz">Coming Soon</a></li>
          </ul>
        </li>
        <li class="relative group">
          <button class="inline-flex py-3 text-sm font-medium items-center gap-2">Components <i class="las la-plus group-hover:hidden text-lg"></i><i class="las la-minus hidden text-lg group-hover:inline-block"></i></button>
          <ul class="submenu-horiz">
            <li>
              <a href="alert.html" class="link-horiz menu-link-horiz">Alert</a>
            </li>
            <li>
              <a href="accordion.html" class="link-horiz menu-link-horiz">Accordion</a>
            </li>
            <li>
              <a href="avatar.html" class="link-horiz menu-link-horiz">Avatar</a>
            </li>
            <li>
              <a href="badge.html" class="link-horiz menu-link-horiz">Badge</a>
            </li>
            <li>
              <a href="breadcrumbs.html" class="link-horiz menu-link-horiz">Breadcrumbs</a>
            </li>
            <li>
              <a href="buttons.html" class="link-horiz menu-link-horiz">Buttons</a>
            </li>
            <li>
              <a href="chip.html" class="link-horiz menu-link-horiz">Chip</a>
            </li>
            <li>
              <a href="dialog.html" class="link-horiz menu-link-horiz">Modal</a>
            </li>
            <li>
              <a href="list.html" class="link-horiz menu-link-horiz">List</a>
            </li>
            <li>
              <a href="menu.html" class="link-horiz menu-link-horiz">Menu</a>
            </li>
            <li>
              <a href="mega-menu.html" class="link-horiz menu-link-horiz">Mega Menu</a>
            </li>
            <li>
              <a href="pagination.html" class="link-horiz menu-link-horiz">Pagination</a>
            </li>
            <li>
              <a href="popover.html" class="link-horiz menu-link-horiz">Popover</a>
            </li>
            <li>
              <a href="progress.html" class="link-horiz menu-link-horiz">Progress</a>
            </li>
            <li>
              <a href="rating.html" class="link-horiz menu-link-horiz">Rating</a>
            </li>
            <li>
              <a href="stepper.html" class="link-horiz menu-link-horiz">Stepper</a>
            </li>
            <li>
              <a href="tabs.html" class="link-horiz menu-link-horiz">Tabs</a>
            </li>
            <li>
              <a href="timeline.html" class="link-horiz menu-link-horiz">Timeline</a>
            </li>
            <li>
              <a href="transfer-list.html" class="link-horiz menu-link-horiz">Transfer List</a>
            </li>
            <li>
              <a href="copy-to-clipboard.html" class="link-horiz menu-link-horiz">Copy to Clipboard</a>
            </li>
            <li>
              <a href="image.html" class="link-horiz menu-link-horiz">Image</a>
            </li>
            <li>
              <a href="label.html" class="link-horiz menu-link-horiz">Label</a>
            </li>
            <li>
              <a href="scroll.html" class="link-horiz menu-link-horiz">Scroll</a>
            </li>
            <li>
              <a href="scroll-progress.html" class="link-horiz menu-link-horiz">Scroll Progress</a>
            </li>
            <li>
              <a href="text-max-line.html" class="link-horiz menu-link-horiz">Text max line</a>
            </li>
            <li>
              <a href="navigation-bar.html" class="link-horiz menu-link-horiz">Navigation Bar</a>
            </li>
            <li>
              <a href="organization-chart.html" class="link-horiz menu-link-horiz">Organization Chart</a>
            </li>
            <li>
              <a href="input.html" class="link-horiz menu-link-horiz">Input</a>
            </li>
            <li>
              <a href="autocomplete.html" class="link-horiz menu-link-horiz">Autocomplete</a>
            </li>
            <li>
              <a href="checkbox.html" class="link-horiz menu-link-horiz">Checkbox</a>
            </li>
            <li>
              <a href="pickers.html" class="link-horiz menu-link-horiz">Pickers</a>
            </li>
            <li>
              <a href="radio-button.html" class="link-horiz menu-link-horiz">Radio Button</a>
            </li>
            <li>
              <a href="switch.html" class="link-horiz menu-link-horiz">Switch</a>
            </li>
            <li>
              <a href="slider.html" class="link-horiz menu-link-horiz">Slider</a>
            </li>
            <li>
              <a href="tooltip.html" class="link-horiz menu-link-horiz">Tooltip</a>
            </li>
            <li>
              <a href="editor.html" class="link-horiz menu-link-horiz">Editor</a>
            </li>
            <li>
              <a href="upload.html" class="link-horiz menu-link-horiz">Upload</a>
            </li>
            <li>
              <a href="carousel.html" class="link-horiz menu-link-horiz">Carousel</a>
            </li>
            <li>
              <a href="form-validation.html" class="link-horiz menu-link-horiz">Form Validation</a>
            </li>
            <li>
              <a href="lightbox.html" class="link-horiz menu-link-horiz">Lightbox</a>
            </li>
            <li>
              <a href="snackbar.html" class="link-horiz menu-link-horiz">Toast</a>
            </li>
            <li>
              <a href="walktour.html" class="link-horiz menu-link-horiz">Walktour</a>
            </li>
            <li>
              <a href="table.html" class="link-horiz menu-link-horiz">Basic Table</a>
            </li>
            <li>
              <a href="data-grid.html" class="link-horiz menu-link-horiz">Data Grid</a>
            </li>
            <li>
              <a href="colors.html" class="link-horiz menu-link-horiz">Colors</a>
            </li>
            <li>
              <a href="typography.html" class="link-horiz menu-link-horiz">Typography</a>
            </li>
            <li>
              <a href="shadows.html" class="link-horiz menu-link-horiz">Shadows</a>
            </li>
            <li>
              <a href="grid.html" class="link-horiz menu-link-horiz">Grid</a>
            </li>
            <li>
              <a href="chart.html" class="link-horiz menu-link-horiz">Charts</a>
            </li>
            <li>
              <a href="map.html" class="link-horiz menu-link-horiz">Maps</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
