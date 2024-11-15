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

<header
  class="sticky top-0 z-999 flex w-full bg-white drop-shadow-1 dark:bg-boxdark dark:drop-shadow-none"
>
  <div
    class="flex flex-grow items-center justify-between px-4 py-4 shadow-2 md:px-6 2xl:px-11"
  >
    <div class="flex items-center gap-2 sm:gap-4 lg:hidden">
      <!-- Hamburger Toggle BTN -->
      <button
        class="z-99999 block rounded-sm border border-stroke bg-white p-1.5 shadow-sm dark:border-strokedark dark:bg-boxdark lg:hidden"
        @click.stop="sidebarToggle = !sidebarToggle"
      >
        <span class="relative block h-5.5 w-5.5 cursor-pointer">
          <span class="du-block absolute right-0 h-full w-full">
            <span
              class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-[0] duration-200 ease-in-out dark:bg-white"
              :class="{ '!w-full delay-300': !sidebarToggle }"
            ></span>
            <span
              class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-150 duration-200 ease-in-out dark:bg-white"
              :class="{ '!w-full delay-400': !sidebarToggle }"
            ></span>
            <span
              class="relative left-0 top-0 my-1 block h-0.5 w-0 rounded-sm bg-black delay-200 duration-200 ease-in-out dark:bg-white"
              :class="{ '!w-full delay-500': !sidebarToggle }"
            ></span>
          </span>
          <span class="du-block absolute right-0 h-full w-full rotate-45">
            <span
              class="absolute left-2.5 top-0 block h-full w-0.5 rounded-sm bg-black delay-300 duration-200 ease-in-out dark:bg-white"
              :class="{ '!h-0 delay-[0]': !sidebarToggle }"
            ></span>
            <span
              class="delay-400 absolute left-0 top-2.5 block h-0.5 w-full rounded-sm bg-black duration-200 ease-in-out dark:bg-white"
              :class="{ '!h-0 dealy-200': !sidebarToggle }"
            ></span>
          </span>
        </span>
      </button>
      <!-- Hamburger Toggle BTN -->
      <a class="block flex-shrink-0 lg:hidden" href="index.html">
        <img src="./images/logo/logo-icon.svg" alt="Logo" />
      </a>
    </div>
    <div class="hidden sm:block">
      <h1 x-text="title" class="text-black font-semibold uppercase dark:text-white"></h1>
    </div>

    <div class="flex items-center gap-3 2xsm:gap-7">
      <ul class="flex items-center gap-2 2xsm:gap-4">
        {{-- Darkmode --}}
        {{-- <li>
          <!-- Dark Mode Toggler -->
          <label
            :class="darkMode ? 'bg-primary' : 'bg-stroke'"
            class="relative m-0 block h-7.5 w-14 rounded-full"
          >
            <input
              type="checkbox"
              :value="darkMode"
              @change="darkMode = !darkMode"
              class="absolute top-0 z-50 m-0 h-full w-full cursor-pointer opacity-0"
            />
            <span
              :class="darkMode && '!right-1 !translate-x-full'"
              class="absolute left-1 top-1/2 flex h-6 w-6 -translate-y-1/2 translate-x-0 items-center justify-center rounded-full bg-white shadow-switcher duration-75 ease-linear"
            >
              <span class="dark:hidden">
                <x-icons.sun />
              </span>
              <span class="hidden dark:inline-block">
                <x-icons.moon />
              </span>
            </span>
          </label>
          <!-- Dark Mode Toggler -->
        </li> --}}

        <!-- Notification Menu Area -->
        {{-- <li
          class="relative"
          x-data="{ dropdownOpen: false, notifying: true }"
          @click.outside="dropdownOpen = false"
        >
          <a
            class="relative flex h-8.5 w-8.5 items-center justify-center rounded-full border-[0.5px] border-stroke bg-gray hover:text-primary dark:border-strokedark dark:bg-meta-4 dark:text-white"
            href="#"
            @click.prevent="dropdownOpen = ! dropdownOpen; notifying = false"
          >
            <span
              :class="!notifying && 'hidden'"
              class="absolute -top-0.5 right-0 z-1 h-2 w-2 rounded-full bg-meta-1"
            >
              <span
                class="absolute -z-1 inline-flex h-full w-full animate-ping rounded-full bg-meta-1 opacity-75"
              ></span>
            </span>

            <x-icons.notification />
            
          </a>

          <!-- Dropdown Start -->
          <div
            x-cloak
            x-show="dropdownOpen"
            class="absolute -right-27 mt-2.5 flex h-90 w-75 flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark sm:right-0 sm:w-80"
          >
            <div class="px-4.5 py-3">
              <h5 class="text-sm font-medium text-bodydark2">Notification</h5>
            </div>

            <ul class="flex h-auto flex-col overflow-y-auto">
              <li>
                <a
                  class="flex flex-col gap-2.5 border-t border-stroke px-4.5 py-3 hover:bg-gray-2 dark:border-strokedark dark:hover:bg-meta-4"
                  href="#"
                >
                  <p class="text-sm">
                    <span class="text-black dark:text-white"
                      >Edit your information in a swipe</span
                    >
                    Sint occaecat cupidatat non proident, sunt in culpa qui
                    officia deserunt mollit anim.
                  </p>

                  <p class="text-xs">12 May, 2025</p>
                </a>
              </li>
            </ul>
          </div>
          <!-- Dropdown End -->
        </li> --}}
        <!-- Notification Menu Area -->
      </ul>

      <!-- User Area -->
      <div
        class="relative"
        x-data="{ dropdownOpen: false }"
        @click.outside="dropdownOpen = false"
      >
        <a
          class="flex items-center gap-4"
          href="#"
          @click.prevent="dropdownOpen = ! dropdownOpen"
        >
          <span class="hidden text-right lg:block">
            <span class="block text-sm font-medium text-black dark:text-white"
              >{{ auth()->user()->name }}</span
            >
            <span class="block text-xs font-medium">{{ auth()->user()->email }}</span>
          </span>

          <span class="h-12 w-12 rounded-full">
            @if (auth()->user()->role !== 'admin')
              @php
                $id = auth()->user()->user_id;
          
                // Fetch the appropriate user type
                $user = auth()->user()->role === 'student'
                        ? \App\Models\Students::where('user_id', $id)->first()
                        : \App\Models\Instructor::where('user_id', $id)->first();
          
                // Determine the image path
                $image = $user->image_path ?? asset('build/assets/images/profile.avif');
              @endphp
          
              <!-- Display image -->
              <img class="w-full h-full object-cover rounded-full" src="{{ $image ? Storage::url($image) : $image }}" alt="profile">
            @endif
          </span>

          <x-icons.arrow-down />
        </a>

        <!-- Dropdown Start -->
        <div
          x-cloak
          x-show="dropdownOpen"
          class="absolute right-0 mt-4 flex w-62.5 flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
          <ul
            class="flex flex-col gap-5 border-b border-stroke px-6 py-7.5 dark:border-strokedark"
          >
            <li>
              <a
                href="profile.html"
                class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base"
              >
              <x-icons.student />
                My Profile
              </a>
            </li>
          </ul>
          <button
            wire:click="logout"
            class="flex items-center gap-3.5 px-6 py-4 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base"
          >
            <x-icons.logout />
            Log Out
          </button>
        </div>
        <!-- Dropdown End -->
      </div>
      <!-- User Area -->
    </div>
  </div>
</header>

