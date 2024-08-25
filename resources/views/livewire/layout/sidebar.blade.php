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
  class="absolute left-0 top-0 z-9999 flex h-screen w-72.5 flex-col overflow-y-hidden bg-white drop-shadow-1 duration-300 ease-linear dark:bg-boxdark lg:static lg:translate-x-0"
  @click.outside="sidebarToggle = false"
>
  <!-- SIDEBAR HEADER -->
  <div class="flex items-center justify-center gap-2 px-6 py-4 lg:py-4">
    <a href="{{ route('dashboard') }}" wire:navigate>
      {{-- <img src="{{ asset('build/assets/images/prime-logo.png') }}" class="w-35 h-24 object-cover object-center transition ease-linear" alt="Logo" /> --}}
      <img src="data:image/jpeg;base64,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" class="w-35 h-24 object-cover object-center transition ease-linear" alt="Logo" />
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
    class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear h-full"
  >
    <!-- Sidebar Menu -->
    <nav
      class="mt-5 px-4 py-4 lg:mt-5 lg:px-6 flex flex-col justify-between h-full"
    >
      <!-- Menu Group -->
      <div>
        <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">MENU</h3>

        <ul class="mb-6 flex flex-col gap-1.5">
         
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-gray-800 duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('dashboard') }}" wire:navigate
              :class="{ 'bg-primary  text-white': (title === 'Dashboard') }"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
            

              Dashboard
            </a>
          </li>

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('dashboard') }}" wire:navigate
              :class="{ 'bg-primary ': (title === 'Booking') }"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
            </svg>
            
            

              Booking
            </a>
          </li>

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('dashboard') }}" wire:navigate
              :class="{ 'bg-primary ': (title === 'Students') }"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>            
            


              Students
            </a>
          </li>

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('dashboard') }}" wire:navigate
              :class="{ 'bg-primary ': (title === 'Instructors') }"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            
            

              Instructors
            </a>
          </li>
        </ul>
      </div>
      {{-- Menu End --}}

      {{-- Reports Start --}}
      <div>
        <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">REPORTS</h3>

        <ul class="mb-6 flex flex-col gap-1.5">
         
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('dashboard') }}" wire:navigate
              :class="{ 'bg-primary ': (title === 'Daily Bookings') }"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
            </svg>
            
            

              Daily Bookings
            </a>
          </li>

          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('dashboard') }}" wire:navigate
              :class="{ 'bg-primary ': (title === 'Student Reports') }"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
            </svg>
            
            

              Student Reports
            </a>
          </li>

        </ul>
      </div>
      {{-- Reports End --}}
      
      <div>
        <ul class="mb-6 flex flex-col gap-1.5">
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="{{ route('dashboard') }}" wire:navigate
              :class="{ 'bg-primary ': (title === 'Users') }"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
            </svg>
            

  
              Users
            </a>
          </li>
  
          <li>
            <a
              class="group relative flex items-center gap-2.5 rounded-sm px-4 py-2 font-medium text-black hover:text-white duration-300 ease-in-out hover:bg-primary dark:text-white"
              href="#" wire:click="logout"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
            </svg>
            
            
  
              Logout
            </a>
          </li>
        </ul>
      </div>
    </nav>
    <!-- Sidebar Menu -->
  </div>
</aside>
