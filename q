[1mdiff --git a/resources/views/dashboard.blade.php b/resources/views/dashboard.blade.php[m
[1mindex 332f324..6ddbda9 100644[m
[1m--- a/resources/views/dashboard.blade.php[m
[1m+++ b/resources/views/dashboard.blade.php[m
[36m@@ -53,9 +53,9 @@[m
             <h2 class="font-semibold text-slate-800 dark:text-slate-100">Balances</h2>[m
         </header>[m
         <!-- Table -->[m
[31m-        <table class="table-autodark:text-slate-300 mx-auto w-full">[m
[32m+[m[32m        <table class="table-autodark:text-slate-300 mx-auto w-full sortable">[m
             <!-- Table header -->[m
[31m-            <thead class="text-xs uppercase text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-700 dark:bg-opacity-50 rounded-sm">[m
[32m+[m[32m            <thead class="text-xs uppercase text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-700 dark:bg-opacity-50 rounded-sm cursor-pointer">[m
                 <tr>[m
                     <th class="p-2">[m
                         <div class="font-semibold text-left">Name</div>[m
[36m@@ -97,7 +97,7 @@[m
                             <div class="text-right text-yellow-300">{{ number_format($token->balance, 3) }}</div>[m
                         </td>[m
                         <td class="p-2">[m
[31m-                            <div class="text-right text-red-300">{{ number_format($token->price_eur, 2) }}€</div>[m
[32m+[m[32m                            <div class="text-right text-red-300">{{ number_format($token->price_eur, 2) }}</div>[m
                         </td>[m
                         <td class="p-2">[m
                             <div class="text-right text-red-300">[m
[36m@@ -115,7 +115,7 @@[m
                             </div>[m
                         </td>[m
                         <td class="p-2">[m
[31m-                            <div class="text-right text-emerald-300">{{ number_format($token->price_eur * $token->balance, 2) }}€</div>[m
[32m+[m[32m                            <div class="text-right text-emerald-300">{{ number_format($token->price_eur * $token->balance, 2) }}</div>[m
                         </td>[m
                         <td class="p-2">[m
                             <div class="text-right text-emerald-300">${{ number_format($token->total, 2) }}</div>[m
[1mdiff --git a/resources/views/layouts/app.blade.php b/resources/views/layouts/app.blade.php[m
[1mindex 182420a..d25a4bc 100644[m
[1m--- a/resources/views/layouts/app.blade.php[m
[1m+++ b/resources/views/layouts/app.blade.php[m
[36m@@ -15,6 +15,7 @@[m
         @vite(['resources/css/app.css', 'resources/js/app.js'])[m
 [m
         <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>[m
[32m+[m[32m        <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>[m
     </head>[m
     <body class="font-sans antialiased">[m
         <div class="min-h-screen bg-gray-100 dark:bg-slate-800">[m
