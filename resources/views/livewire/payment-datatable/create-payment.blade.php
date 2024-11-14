<div class="w-full border-stroke dark:border-strokedark">
    <div class="w-full p-4 sm:p-12.5 xl:p-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-black dark:text-white sm:text-title-xl2" >
           Payment
        </h2>
        <button @click="show = false" class="hover:text-red-500" wire:click="formClose"> 
            <x-icons.close />
        </button>
    </div>
    @if($payments)
        <div class="flex flex-col text-black">
            <center>
                <img class="w-[80px] h-[60px] object-cover object-center mb-4" src="{{ asset('build/assets/images/prime-logo.png') }}" alt="Logo" />
                <h2 class="font-bold text-lg text-black-2 uppercase">Official Receipt</h2>
                <span class="text-sm">{{ now()->format('l, F d, Y - h:i A') }}</span>
            </center>
            <hr class="my-5">
            <div class="w-full flex flex-col justify-center items-center gap-2 mb-3">
                <h3><b class="uppercase">Invoice #:</b></h3>
                <span class="w-full text-sm tracking-[12px] border border-dashed p-3 text-center">{{ $payments['invoice_code'] }}</span>
            </div>
            <div class="flex justify-between">
                <span>Name</span>
                <span>{{$payments['student_name']}}</span>
            </div>
            <hr class="my-3">
            <div class="flex justify-between mb-3">
                <span>Schedule Code</span>
                <span>{{$payments['course_code']}}</span>
            </div>
            <div class="flex justify-between mb-3">
                <span>Course Name</span>
                <span>{{$payments['course_name']}}</span>
            </div>
            <div class="flex justify-between">
                <span>Course Type</span>
                <span class="capitalize">{{$payments['course_type']}}</span>
            </div>
            <hr class="my-3">
            <div class="flex justify-between mb-3">
                <span>Amount</span>
                <span>{{$payments['course_amount'] - $payments['paid_amount']}}</span>
            </div>
            <div class="flex justify-between mb-3">
                <span>VAT</span>
                <span>{{$payments['vat']}}</span>
            </div>
            <div class="flex justify-between">
                <span>Total</span>
                <span>{{($payments['course_amount'] + $payments['vat']) - $payments['paid_amount']}}</span>
            </div>
            <hr class="my-3">
            <form wire:submit.prevent="submit_payment" >
                <div x-data="{ amount: @entangle('amount'), minLength: 11 }" x-init="$nextTick(() => { $refs.amount.focus() })" class="mb-4">
                    <label for="amount" class="mb-2.5 block font-medium text-black dark:text-white">
                        Enter Amount
                    </label>
                    <div class="relative">
                        <x-elements.text-input x-model="amount" wire:model="amount" id="amount" type="text" name="amount" placeholder="Amount" focus autocomplete="amount" @input="amount = amount.replace(/[^0-9]/g, '')"
                        @keydown="if (amount.length >= minLength && event.keyCode !== 8 && event.keyCode !== 46) event.preventDefault()" x-ref="amount"/>
                        <x-elements.input-error :messages="$errors->get('amount')" class="mt-2" />
                        <span class="absolute right-4 top-4">
                            <x-icons.money />                     
                        </span>
                    </div>
                </div>
        
                <div class="mb-4">
                 <input
                     type="submit"
                     value="Submit Payment"
                     class="w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90"
                 />
                </div>
             </form>
        </div>
    @endif
    </div>
</div>


