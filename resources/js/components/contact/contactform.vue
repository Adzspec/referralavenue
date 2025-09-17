<template>
    <div class="bg-gray-50 py-20 px-4">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-[40%_60%] gap-8">

            <!-- LEFT PANEL: Info -->
            <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-white rounded-xl shadow-lg p-8 md:p-10">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-4">Get in touch</h2>
                <p class="text-sm text-slate-600 mb-8">
                    We’re here to help and answer any questions you might have. Reach out to us through the details below.
                </p>

                <ul class="space-y-6">
                    <!-- Address -->
                    <li class="flex items-start">
                        <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-white shadow-md mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 11.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/>
                                <path d="M12 2C8 2 5 5 5 9c0 5.25 6.5 12 7 12s7-6.75 7-12c0-4-3-7-7-7z" opacity="0.15"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-700">Head Office</div>
                            <div class="text-sm text-slate-500">
                                Jalan Cempaka Wangi No 22<br/>Jakarta - Indonesia
                            </div>
                        </div>
                    </li>

                    <!-- Email -->
                    <li class="flex items-start">
                        <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-white shadow-md mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M3 7.5l9 6 9-6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <rect x="3" y="5" width="18" height="14" rx="2" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-700">Email Us</div>
                            <div class="text-sm text-slate-500">
                                support@yourdomain.tld<br/>hello@yourdomain.tld
                            </div>
                        </div>
                    </li>

                    <!-- Phone -->
                    <li class="flex items-start">
                        <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-white shadow-md mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.86 19.86 0 01-8.63-3.07
                         19.5 19.5 0 01-6-6A19.86 19.86 0 013 4.18
                         2 2 0 015 2h3a2 2 0 012 1.72c.12.96.38
                         1.9.78 2.78a2 2 0 01-.45 2.11L9.91
                         10.09a14 14 0 006 6l1.48-1.48a2 2
                         0 012.11-.45c.88.4 1.82.66
                         2.78.78A2 2 0 0122 16.92z"
                                      opacity="0.9"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-700">Call Us</div>
                            <div class="text-sm text-slate-500">
                                Phone : +6221.2002.2012<br/>Fax : +6221.2002.2013
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- RIGHT PANEL: Form -->
            <div class="bg-white rounded-xl shadow-lg p-8 md:p-10">
                <h3 class="text-2xl md:text-3xl font-bold text-slate-800 mb-6">Send us a message</h3>

                <form class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-slate-500 mb-2">Name</label>
                            <input type="text" placeholder="Your Name"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-300"/>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-2">Company</label>
                            <input type="text" placeholder="Company"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-300"/>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-slate-500 mb-2">Phone</label>
                            <input type="text" placeholder="Phone"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-300"/>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-2">Email</label>
                            <input type="email" placeholder="Email"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-300"/>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-500 mb-2">Subject</label>
                        <input type="text" placeholder="Subject"
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-300"/>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-500 mb-2">Message</label>
                        <textarea rows="5" placeholder="Type your message..."
                                  class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-300"></textarea>
                    </div>

                    <div>
                        <button type="submit"
                                class="group w-full md:w-auto flex items-center justify-center gap-2 px-8 py-3
                     bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500
                     hover:from-blue-600 hover:via-indigo-600 hover:to-purple-600
                     text-white font-semibold rounded-full
                     shadow-lg hover:shadow-xl
                     transform hover:scale-105 transition-all duration-300">
                            Send Message

                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</template>
