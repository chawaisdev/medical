 <!-- SIDEBAR -->

 <aside class="app-sidebar sticky" id="sidebar">

     <!-- Start::main-sidebar-header -->
     <div class="main-sidebar-header">
         <a href="" class="header-logo text-center">
             <img src="{{ asset('assets/images/logo getwelll.png') }}" alt="Get Well">

         </a>
     </div>

     <!-- Start::main-sidebar -->
     <div class="main-sidebar" id="sidebar-scroll">
         <!-- Start::nav -->
         <nav class="main-menu-container nav nav-pills flex-column sub-open">
             <div class="slide-left" id="slide-left">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                     viewBox="0 0 24 24">
                     <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                 </svg>
             </div>
             <li class="slide__category"><span class="category-name">Main</span></li>
             <ul class="main-menu">
                 @php
                     $permissions = auth()->check() ? auth()->user()->getPermissions() : [];
                 @endphp

                 <!-- Dashboard -->
                 <!-- Dashboard -->
                 @if (auth()->user()->user_type === 'admin')
                     <li class="slide">
                         <a href="{{ route('dashboard') }}" class="side-menu__item">
                             <i class="fa-solid fa-chart-line side-menu__icon text-sm"></i>
                             <span class="side-menu__label">Dashboard</span>
                         </a>
                     </li>
                 @elseif (auth()->user()->user_type === 'reception')
                     <li class="slide">
                         <a href="{{ route('reception.dashboard') }}" class="side-menu__item">
                             <i class="fa-solid fa-chart-line side-menu__icon text-sm"></i>
                             <span class="side-menu__label">Dashboard</span>
                         </a>
                     </li>
                 @elseif (auth()->user()->user_type === 'patient')
                     <li class="slide">
                         <a href="{{ route('patient.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-chart-line side-menu__icon text-sm"></i>
                             <span class="side-menu__label">Dashboard</span>
                         </a>
                     </li>
                 @endif


                 <!-- User Management -->
                 @if (in_array('User Management', $permissions))
                     <li class="slide mt-2">
                         <a href="{{ route('adduser.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-users-gear side-menu__icon"></i>
                             <span class="side-menu__label">User Management</span>
                         </a>
                     </li>
                 @endif

                 <!-- Roles -->
                 @if (in_array('Roles', $permissions))
                     <li class="slide mt-2">
                         <a href="{{ route('roles.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-users-gear side-menu__icon"></i>
                             <span class="side-menu__label">Roles</span>
                         </a>
                     </li>
                 @endif

                 <!-- Clinics -->
                 @if (in_array('Clinics', $permissions))
                     <li class="slide mt-2">
                         <a href="{{ route('clinic.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-hospital side-menu__icon"></i>
                             <span class="side-menu__label">Clinics</span>
                         </a>
                     </li>
                 @endif

                 <!-- Services -->
                 @if (in_array('Services', $permissions))
                     <li class="slide mt-2">
                         <a href="{{ route('services.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-calendar-days side-menu__icon"></i>
                             <span class="side-menu__label">Services</span>
                         </a>
                     </li>
                 @endif

                 <!-- My Appointments -->
                 @if (in_array('My Appointments', $permissions))
                     <li class="slide mt-2">
                         <a href="{{ url('appointments') }}" class="side-menu__item">
                             <i class="fa-solid fa-calendar-check side-menu__icon"></i>
                             <span class="side-menu__label">My Appointments</span>
                         </a>
                     </li>
                 @endif

                 <!-- My Reports -->
                 @if (in_array('My Reports', $permissions))
                     <li class="slide mt-2">
                         <a href="{{ url('patient-reports/download') }}"
                             class="side-menu__item d-flex align-items-center">
                             <i class="fa-solid fa-file-medical side-menu__icon me-2"></i>
                             <span class="side-menu__label">My Reports</span>
                         </a>
                     </li>
                 @endif

                 <!-- Patients -->
                 @if (in_array('Patients', $permissions))
                     <li class="slide mt-2">
                         <a href="{{ route('reception.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-user-injured side-menu__icon"></i>
                             <span class="side-menu__label">Patients</span>
                         </a>
                     </li>
                 @endif

                 <!-- Appointments -->
                 @if (in_array('Appointments', $permissions))
                     <li class="slide mt-2">
                         <a href="{{ route('appointment.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-calendar-days side-menu__icon"></i>
                             <span class="side-menu__label">Appointments</span>
                         </a>
                     </li>
                 @endif

                 @if (auth()->user()->user_type === 'admin')
                     <li class="slide mt-2">
                         <a href="{{ route('settings.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-sliders side-menu__icon"></i>

                             <span class="side-menu__label">Settings</span>
                         </a>
                     </li>
                 @endif



                 <!-- Logout -->
                 <li class="slide mt-2">
                     <a href="#" class="side-menu__item"
                         onclick="event.preventDefault(); document.getElementById('logout-link').submit();">
                         <i class="fa-solid fa-right-from-bracket side-menu__icon"></i>
                         <span class="side-menu__label">Logout</span>
                     </a>
                     <form id="logout-link" action="{{ route('logout') }}" method="POST" style="display: none;">
                         @csrf
                     </form>
                 </li>
             </ul>


             <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                     width="24" height="24" viewBox="0 0 24 24">
                     <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                 </svg>
             </div>
         </nav>
     </div>
 </aside>
