<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<aside
  :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
  class="absolute left-0 top-0 z-9999 flex h-screen w-70 flex-col overflow-y-hidden bg-white drop-shadow-1 duration-300 ease-linear dark:bg-boxdark lg:static lg:translate-x-0"
  @click.outside="sidebarToggle = false"
>
  <!-- SIDEBAR HEADER -->
  <div class="flex items-center justify-center gap-2 px-6 py-4 lg:py-4">
    <a href="{{ route('dashboard') }}" wire:navigate>
      <img src="{{ asset('build/assets/images/prime-logo.png') }}" class="w-35 h-24 object-cover object-center transition ease-linear" alt="Logo" />
      {{-- <img src="data:image/jpeg;base64,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" class="w-35 h-24 object-cover object-center transition ease-linear" alt="Logo" /> --}}
  </a>

    <button
      class="block lg:hidden"
      @click.stop="sidebarToggle = !sidebarToggle"
    >
      <svg
        class="fill-current"
        width="20"
        height="18"
        viewBox="0 0 20 18"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M19 8.175H2.98748L9.36248 1.6875C9.69998 1.35 9.69998 0.825 9.36248 0.4875C9.02498 0.15 8.49998 0.15 8.16248 0.4875L0.399976 8.3625C0.0624756 8.7 0.0624756 9.225 0.399976 9.5625L8.16248 17.4375C8.31248 17.5875 8.53748 17.7 8.76248 17.7C8.98748 17.7 9.17498 17.625 9.36248 17.475C9.69998 17.1375 9.69998 16.6125 9.36248 16.275L3.02498 9.8625H19C19.45 9.8625 19.825 9.4875 19.825 9.0375C19.825 8.55 19.45 8.175 19 8.175Z"
          fill=""
        />
      </svg>
    </button>
  </div>
  <!-- SIDEBAR HEADER -->

  <div
    class="flex flex-col overflow-y-hidden hover:overflow-y-auto duration-300 ease-linear h-full"
  >
    <!-- Sidebar Menu -->
    <nav
      class="mt-5 px-4 py-4 lg:mt-5 lg:px-6 flex flex-col justify-between h-full"
    >
      <!-- Menu Group -->
      <div>
        <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">MENU</h3>

        <ul class="mb-6 flex flex-col gap-1.5">
          @if(auth()->user()->role === 'admin')
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black duration-300 ease-in-out hover:bg-primary hover:text-white dark:text-white"
              href="{{ route('dashboard') }}"
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Dashboard') }"
            >
            <x-icons.dashboard-icon />
            

              Dashboard
            </a>
          </li>
          @endif

          @if(auth()->user()->role !== 'admin')
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black duration-300 ease-in-out hover:bg-primary hover:text-white dark:text-white"
              href="{{ route('student-dashboard') }}"
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Dashboard') }"
            >
            <x-icons.dashboard-icon />
            

              Dashboard
            </a>
          </li>
          @endif

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('schedules') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Schedule') }"
            >
            <x-icons.booking />
            
            

              Schedule
            </a>
          </li>

          @if(auth()->user()->role === 'student')

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black duration-300 ease-in-out hover:bg-primary hover:text-white dark:text-white"
              href="{{ route('student-page-reports') }}"
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Student Reports') }"
            >
            <x-icons.student-report />
            

              Reports
            </a>
          </li>
        @endif

        @if(auth()->user()->role !== 'admin')
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('profile') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Profile') }"
            >
            <x-icons.student />          
            


              Profile
            </a>
          </li>
        @endif

          @if(auth()->user()->role === 'admin')
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('students') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Students') }"
            >
            <x-icons.student />          
            


              Students
            </a>
          </li>
          @endif

          @if(auth()->user()->role === 'admin')
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('instructors') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Instructors') }"
            >
            <x-icons.instructor />
            
            

              Instructors
            </a>
          </li>
          @endif

          @if(auth()->user()->role === 'admin')
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('vehicles') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Vehicles') }"
            >
            <x-icons.car />
            
            

              Vehicles
            </a>
          </li>
          @endif

          @if(auth()->user()->role === 'admin')
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('payments') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Payments') }"
            >
            <x-icons.money />
            
            

              Payments
            </a>
          </li>
          @endif

          @if(auth()->user()->role === 'instructor')
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('student-reports') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Student Reports') }"
            >
            <x-icons.student-report />

              Student Records
            </a>
          </li>
          @endif

        </ul>
      </div>
      {{-- Menu End --}}

      @if(auth()->user()->role === 'admin')
      {{-- Reports Start --}}
      <div>
        <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">REPORTS</h3>

        <ul class="mb-6 flex flex-col gap-1.5">
         
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('daily-sales') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Daily Sales') }"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            

              Daily Sales
            </a>
          </li>

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('schedule-reports') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Schedule Reports') }"
            >
            <x-icons.daily-booking />
            
            

              Schedule
            </a>
          </li>

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('student-reports') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Student Reports') }"
            >
            <x-icons.student-report />
            
            

              Student Records
            </a>
          </li>

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('theoretical-records') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Theoretical Records') }"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>            
            
              Theoretical Records
            </a>
          </li>

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('practical-records') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Practical Records') }"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
            </svg>            
            
              Practical Records
            </a>
          </li>

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('student-list') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Student List') }"
            >
            <x-icons.student />

              Student List
            </a>
          </li>

          {{-- <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('student-certificates') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Student Certificates') }"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
            </svg>
            
              Student Certificates
            </a>
          </li> --}}

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('payment-reports') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Payment Reports') }"
            >
            <x-icons.money />
            
            

              Payment Records
            </a>
          </li>

        </ul>
      </div>
      {{-- Reports End --}}
      @endif

      <div>
        <ul class="mb-6 flex flex-col gap-1.5">
          {{-- @if(auth()->user()->role === 'admin')
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('users') }}" wire:navigate
              @click="loaded = true"
              :class="{ 'bg-primary text-white': (title === 'Users') }"
            >
            <x-icons.users />



              Users
            </a>
          </li>
          @endif --}}

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="#" wire:click="logout"
            >
            <x-icons.logout />
            
  
              Logout
            </a>
          </li>
        </ul>
      </div>
    </nav>
    <!-- Sidebar Menu -->
  </div>
</aside>
