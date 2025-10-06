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
                     $user = auth()->user();
                 @endphp

                 <!-- DASHBOARD -->
                 @if ($user->user_type === 'admin')
                     <li class="slide">
                         <a href="{{ route('dashboard') }}" class="side-menu__item">
                             <i class="fa-solid fa-gauge-high side-menu__icon text-sm"></i>
                             <span class="side-menu__label">Dashboard</span>
                         </a>
                     </li>
                 @endif

                 <!-- USER reception -->
                 @if ($user->user_type === 'reception')
                     <li class="slide">
                         <a href="{{ route('reception.dashboard') }}" class="side-menu__item">
                             <i class="fa-solid fa-gauge-high side-menu__icon text-sm"></i>
                             <span class="side-menu__label">Dashboard</span>
                         </a>
                     </li>
                 @endif

                 <!-- USER PATINNTS -->
                 @if ($user->user_type === 'patient')
                     <li class="slide">
                         <a href="{{ route('patient.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-gauge-high side-menu__icon text-sm"></i>
                             <span class="side-menu__label">Dashboard</span>
                         </a>
                     </li>
                 @endif

                 <!-- USER MANAGEMENT -->
                 @if ($user->user_type === 'admin')
                     <li class="slide mt-2">
                         <a href="{{ route('adduser.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-user-gear side-menu__icon"></i>
                             <span class="side-menu__label">User Management</span>
                         </a>
                     </li>
                 @endif

                 <!-- ROLES -->
                 @if ($user->user_type === 'admin')
                     <li class="slide mt-2">
                         <a href="{{ route('roles.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-user-shield side-menu__icon"></i>
                             <span class="side-menu__label">Roles</span>
                         </a>
                     </li>
                 @endif

                 <!-- CLINICS -->
                 @if ($user->user_type === 'admin')
                     <li class="slide mt-2">
                         <a href="{{ route('clinic.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-hospital side-menu__icon"></i>
                             <span class="side-menu__label">Clinics</span>
                         </a>
                     </li>
                 @endif

                 <!-- REFUNDS -->
                 @if ($user->user_type === 'admin')
                     <li class="slide mt-2">
                         <a href="{{ route('refunds.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-rotate-left side-menu__icon"></i>
                             <span class="side-menu__label">Refunds</span>
                         </a>
                     </li>
                 @endif


                 <!-- SERVICES -->
                 @if ($user->user_type === 'admin')
                     <li class="slide mt-2">
                         <a href="{{ route('services.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-stethoscope side-menu__icon"></i>
                             <span class="side-menu__label">Services</span>
                         </a>
                     </li>
                 @endif

                 <!-- ALL PATIENTS (ADMIN ONLY) -->
                 @if ($user->user_type === 'admin')
                     <li class="slide mt-2">
                         <a href="{{ url('patients/list') }}" class="side-menu__item">
                             <i class="fa-solid fa-user-injured side-menu__icon"></i>
                             <span class="side-menu__label">All Patients List</span>
                         </a>
                     </li>
                 @endif

                 <!-- MY APPOINTMENTS For Patients -->
                 @if ($user->user_type === 'patient')
                     <li class="slide mt-2">
                         <a href="{{ url('my-appointments') }}" class="side-menu__item">
                             <i class="fa-solid fa-calendar-check side-menu__icon"></i>
                             <span class="side-menu__label">My Appointments</span>
                         </a>
                     </li>
                 @endif

                 <!-- MY REPORTS  For Patients-->
                 @if ($user->user_type === 'patient')
                     <li class="slide mt-2">
                         <a href="{{ url('patient-reports/download') }}"
                             class="side-menu__item d-flex align-items-center">
                             <i class="fa-solid fa-file-medical side-menu__icon me-2"></i>
                             <span class="side-menu__label">My Reports</span>
                         </a>
                     </li>
                 @endif

                 <!-- PATIENTS  Menu For the reception-->
                 @if ($user->user_type === 'reception')
                     <li class="slide mt-2">
                         <a href="{{ route('reception.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-user-injured side-menu__icon"></i>
                             <span class="side-menu__label">Patients</span>
                         </a>
                     </li>
                 @endif

                 <!-- APPOINTMENTS Menu For the reception -->
                 @if ($user->user_type === 'reception')
                     <li class="slide mt-2">
                         <a href="{{ route('appointment.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-calendar-days side-menu__icon"></i>
                             <span class="side-menu__label">Appointments</span>
                         </a>
                     </li>
                 @endif


                 <!-- DOCTOR APPOINTMENTS (Admin + Doctor) -->
                 @if ($user->user_type === 'doctor')
                     <li class="slide mt-2">
                         <a href="{{ route('doctor.appointments') }}" class="side-menu__item">
                             <i class="fa-solid fa-user-injured side-menu__icon"></i>
                             <span class="side-menu__label">Doctor Appointments</span>
                         </a>
                     </li>
                 @endif

                 <!-- SETTINGS -->
                 @if ($user->user_type === 'admin')
                     <li class="slide mt-2">
                         <a href="{{ route('settings.index') }}" class="side-menu__item">
                             <i class="fa-solid fa-gear side-menu__icon"></i>
                             <span class="side-menu__label">Settings</span>
                         </a>
                     </li>
                 @endif

                 <!-- LOGOUT -->
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
