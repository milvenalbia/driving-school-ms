<div x-show="loaded"
     x-init="
         setTimeout(() => {
             loaded = false;
         }, 500);
     "
     class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-black">
     <div class="w-20 h-20 border-4 border-transparent text-blue-400 text-4xl animate-spin flex items-center justify-center border-t-blue-400 rounded-full ">
         <div class="w-16 h-16 border-4 border-transparent text-red-400 text-2xl animate-spin flex items-center justify-center border-t-red-400 rounded-full "></div>
     </div>
</div>
