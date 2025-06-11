 @if (session('error'))
     <div id="flashModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
         <div
             class="bg-white rounded-2xl shadow-xl px-8 py-7 text-center w-[90vw] max-w-md border border-gray-200 animate-fade-in-down">
             <div class="text-red-600 text-xl font-bold mb-3">Gagal</div>
             <div class="mb-4">{{ session('error') }}</div>
             <button onclick="document.getElementById('flashModal').style.display='none'"
                 class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-full shadow hover:bg-blue-700 font-semibold">
                 OK
             </button>
         </div>
     </div>
 @endif
