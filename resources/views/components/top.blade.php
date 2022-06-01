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
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              class="feather feather-home">
                              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                              <polyline points="9 22 9 12 15 12 15 22"></polyline>
                          </svg>
                          <span>Catalogo </span>
                      </div>
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="feather feather-chevron-down">
                          <polyline points="6 9 12 15 18 9"></polyline>
                      </svg>
                  </a>
              </li>

              {{-- <li class="menu single-menu">
                  <a href="{{ route('catalogo') }}" aria-expanded="false" class="dropdown-toggle">
                      <div class="">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              class="feather feather-cpu">
                              <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                              <rect x="9" y="9" width="6" height="6"></rect>
                              <line x1="9" y1="1" x2="9" y2="4"></line>
                              <line x1="15" y1="1" x2="15" y2="4"></line>
                              <line x1="9" y1="20" x2="9" y2="23"></line>
                              <line x1="15" y1="20" x2="15" y2="23"></line>
                              <line x1="20" y1="9" x2="23" y2="9"></line>
                              <line x1="20" y1="14" x2="23" y2="14"></line>
                              <line x1="1" y1="9" x2="4" y2="9"></line>
                              <line x1="1" y1="14" x2="4" y2="14"></line>
                          </svg>
                          <span>Catalogo</span>
                      </div>
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="feather feather-chevron-down">
                          <polyline points="6 9 12 15 18 9"></polyline>
                      </svg>
                  </a>
              </li> --}}
              <li class="menu single-menu">
                  <a href="{{ route('cotizacion') }}" aria-expanded="false" class="dropdown-toggle">
                      <div class="">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              class="feather feather-cpu">
                              <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                              <rect x="9" y="9" width="6" height="6"></rect>
                              <line x1="9" y1="1" x2="9" y2="4"></line>
                              <line x1="15" y1="1" x2="15" y2="4"></line>
                              <line x1="9" y1="20" x2="9" y2="23"></line>
                              <line x1="15" y1="20" x2="15" y2="23"></line>
                              <line x1="20" y1="9" x2="23" y2="9"></line>
                              <line x1="20" y1="14" x2="23" y2="14"></line>
                              <line x1="1" y1="9" x2="4" y2="9"></line>
                              <line x1="1" y1="14" x2="4" y2="14"></line>
                          </svg>
                          <span>Cotizacion</span>
                      </div>
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="feather feather-chevron-down">
                          <polyline points="6 9 12 15 18 9"></polyline>
                      </svg>
                  </a>
              </li>
              <li class="menu single-menu">
                  <a href="{{ route('cotizaciones') }}" aria-expanded="false" class="dropdown-toggle">
                      <div class="">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              class="feather feather-cpu">
                              <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                              <rect x="9" y="9" width="6" height="6"></rect>
                              <line x1="9" y1="1" x2="9" y2="4"></line>
                              <line x1="15" y1="1" x2="15" y2="4"></line>
                              <line x1="9" y1="20" x2="9" y2="23"></line>
                              <line x1="15" y1="20" x2="15" y2="23"></line>
                              <line x1="20" y1="9" x2="23" y2="9"></line>
                              <line x1="20" y1="14" x2="23" y2="14"></line>
                              <line x1="1" y1="9" x2="4" y2="9"></line>
                              <line x1="1" y1="14" x2="4" y2="14"></line>
                          </svg>
                          <span>Mis Cotizaciones</span>
                      </div>
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="feather feather-chevron-down">
                          <polyline points="6 9 12 15 18 9"></polyline>
                      </svg>
                  </a>
              </li>

              @role('admin')
                  <li class="menu single-menu">
                      <a href="#uiKit" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                          <div class="">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                  class="feather feather-zap">
                                  <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                              </svg>
                              <span>Conf. Tecnicas</span>
                          </div>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              class="feather feather-chevron-down">
                              <polyline points="6 9 12 15 18 9"></polyline>
                          </svg>
                      </a>
                      <ul class="collapse submenu list-unstyled" id="uiKit" data-parent="#topAccordion">
                          <li>
                              <a href="{{ url('/materials') }}"> Materiales </a>
                          </li>
                          <li>
                              <a href="{{ url('/techniques') }}"> Tecnicas </a>
                          </li>
                          <li>
                              <a href="{{ url('/sizes') }}"> Tamaños </a>
                          </li>
                          <li>
                              <a href="{{ url('/prices') }}"> Precios </a>
                          </li>
                      </ul>
                  </li>
              @endrole
          </ul>
      </nav>
  </div>
  <!--  END TOPBAR  -->
  {{--  --}}
