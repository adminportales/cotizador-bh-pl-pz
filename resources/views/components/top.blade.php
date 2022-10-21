  <!--  BEGIN TOPBAR  -->
  <div class="topbar-nav header navbar" role="banner">
      <nav id="topbar">
          <ul class="navbar-nav theme-brand flex-row  text-center">
              <li class="nav-item theme-logo">
                  <a href="index.html">
                      <img src="assets/img/90x90.jpg" class="navbar-logo" alt="logo">
                  </a>
              </li>
              <li class="nav-item theme-text">
                  <a href="index.html" class="nav-link"> PROMO LIFE </a>
              </li>
          </ul>

          <ul class="list-unstyled menu-categories" id="topAccordion">

              <li class="menu single-menu active">
                  <a href="{{ route('catalogo') }}" class="dropdown-toggle autodroprown">
                      <div class="">
                          <span>Cotizador </span>
                      </div>
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" class="feather feather-chevron-down">
                          <polyline points="6 9 12 15 18 9"></polyline>
                      </svg>
                  </a>
              </li>

              @role('admin')
                  <li class="menu single-menu">
                      <a href="{{ route('all.cotizacion') }}" aria-expanded="false" class="dropdown-toggle">
                          <div class="">
                              <span>Cotizaciones</span>
                          </div>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round" class="feather feather-chevron-down">
                              <polyline points="6 9 12 15 18 9"></polyline>
                          </svg>
                      </a>
                  </li>
                  <li class="menu single-menu">
                      <a href="#uiKit" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                          <div class="">
                              <span>Conf. Tecnicas</span>
                          </div>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round" class="feather feather-chevron-down">
                              <polyline points="6 9 12 15 18 9"></polyline>
                          </svg>
                      </a>
                      <ul class="collapse submenu list-unstyled" id="uiKit" data-parent="#topAccordion">
                          <li>
                              <a href="{{ url('admin/materials') }}"> Materiales y Tecnicas</a>
                          </li>
                          <li>
                              <a href="{{ url('admin/sizes') }}"> Tamaños </a>
                          </li>
                          <li>
                              <a href="{{ url('admin/prices') }}"> Precios </a>
                          </li>
                          <li>
                              <a href="{{ url('admin/importTechniques') }}">Importar</a>
                          </li>
                      </ul>
                  </li>
                  <li class="menu single-menu">
                      <a href="{{ url('admin/users') }}" aria-expanded="false" class="dropdown-toggle">
                          <div class="">
                              <span>Usuarios</span>
                          </div>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round" class="feather feather-chevron-down">
                              <polyline points="6 9 12 15 18 9"></polyline>
                          </svg>
                      </a>
                  </li>
                  <li class="menu single-menu">
                      <a href="{{ url('admin/clients') }}" aria-expanded="false" class="dropdown-toggle">
                          <div class="">
                              <span>Clientes</span>
                          </div>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round" class="feather feather-chevron-down">
                              <polyline points="6 9 12 15 18 9"></polyline>
                          </svg>
                      </a>
                  </li>
                  <li class="menu single-menu">
                      <a href="{{ url('admin/tradenames') }}" aria-expanded="false" class="dropdown-toggle">
                          <div class="">
                              <span>Etiquetas de Clientes</span>
                          </div>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round" class="feather feather-chevron-down">
                              <polyline points="6 9 12 15 18 9"></polyline>
                          </svg>
                      </a>
                  </li>
                  <li class="menu single-menu">
                      <a href="{{ url('admin/companies') }}" aria-expanded="false" class="dropdown-toggle">
                          <div class="">
                              <span>Empresas</span>
                          </div>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round" class="feather feather-chevron-down">
                              <polyline points="6 9 12 15 18 9"></polyline>
                          </svg>
                      </a>
                  </li>
              @endrole
          </ul>
      </nav>
  </div>
  <!--  END TOPBAR  -->
  {{--  --}}
