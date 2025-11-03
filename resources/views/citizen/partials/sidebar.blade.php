<div id="citizen-sidebar" class="fixed left-0 top-0 h-full w-64 bg-lgu-headline shadow-2xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50 overflow-hidden flex flex-col">
    <div class="flex items-center justify-between p-4 border-b border-lgu-stroke">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-lgu-highlight">
                <img src="{{ asset('image/logo.jpg') }}" alt="LGU Logo" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-white font-bold text-sm">Local Government Unit</h2>
                <p class="text-gray-300 text-xs">LGU1</p>
            </div>
        </div>
        <div class="relative">
            <button id="citizen-settings-button" class="p-2 text-lgu-paragraph text-white">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.947c-1.543-.993-3.33 1.554-1.636 3.018a1.532 1.532 0 01-.197 2.222c-1.259 1.488-.04 3.242 1.48 3.242.84 0 1.636-.312 2.286-.947a1.532 1.532 0 012.98 0c.649.635 1.445.947 2.286.947 1.52 0 2.74-1.754 1.48-3.242a1.532 1.532 0 01-.197-2.222c1.694-1.464-.093-4.01-.636-3.018a1.532 1.532 0 01-2.286-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
            </button>
            <div id="citizen-settings-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 hidden z-50">
                <a href="{{ route('citizen.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user-cog mr-2"></i> Profile Settings
                </a>
                <button id="citizen-logout-button" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
                <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
        <a href="{{ route('citizen.dashboard') }}" 
           class="citizen-sidebar-link flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home w-5 h-5 mr-3"></i>
            Dashboard
        </a>

        <a href="{{ route('citizen.reservations.create') }}" 
           class="citizen-sidebar-link flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('citizen.reservations.create') ? 'active' : '' }}">
            <i class="fas fa-calendar-plus w-5 h-5 mr-3"></i>
            Make a Reservation
        </a>
        
        <a href="{{ route('citizen.reservations.history') }}" 
           class="citizen-sidebar-link flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('citizen.reservations.history') ? 'active' : '' }}">
            <i class="fas fa-history w-5 h-5 mr-3"></i>
            Reservation History
        </a>
        
        <h3 class="text-xs font-semibold uppercase text-gray-500 mt-4 mb-2 px-4">Facilities</h3>
        
        <a href="{{ route('citizen.availability') }}" 
           class="citizen-sidebar-link flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('citizen.availability') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
            Check Availability
        </a>

        <h3 class="text-xs font-semibold uppercase text-gray-500 mt-4 mb-2 px-4">Info & Support</h3>
        
        <a href="{{ route('citizen.bulletin-board') }}" 
           class="citizen-sidebar-link flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('citizen.bulletin-board') ? 'active' : '' }}">
            <i class="fas fa-bullhorn w-5 h-5 mr-3"></i>
            Bulletin Board
        </a>
        
        <a href="{{ route('citizen.help-faq') }}" 
           class="citizen-sidebar-link flex items-center px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('citizen.help-faq') ? 'active' : '' }}">
            <i class="fas fa-question-circle w-5 h-5 mr-3"></i>
            Help & FAQ
        </a>
    </nav>
    
    <div class="p-4 border-t border-lgu-stroke mt-auto">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-white text-sm font-medium">
                {{ substr(Auth::user()->full_name ?? 'C', 0, 1) }}
            </div>
            <div class="truncate">
                <p class="text-white text-sm font-semibold truncate">{{ Auth::user()->full_name ?? 'Citizen User' }}</p>
                <p class="text-gray-400 text-xs truncate">{{ Auth::user()->email ?? 'user@example.com' }}</p>
            </div>
        </div>
    </div>
</div>

<div id="citizen-sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden lg:hidden"></div>

<button id="citizen-sidebar-toggle" class="fixed top-4 left-4 p-2 bg-lgu-headline text-white rounded-full shadow-lg lg:hidden z-50">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
    </svg>
</button>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('citizen-sidebar');
        const overlay = document.getElementById('citizen-sidebar-overlay');
        const toggleBtn = document.getElementById('citizen-sidebar-toggle');
        const settingsBtn = document.getElementById('citizen-settings-button');
        const settingsDropdown = document.getElementById('citizen-settings-dropdown');
        const logoutBtn = document.getElementById('citizen-logout-button');

        // Toggle Sidebar (Mobile)
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });
        
        // Close sidebar when clicking overlay
        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Toggle Settings Dropdown
        settingsBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            settingsDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            settingsDropdown.classList.add('hidden');
        });
        
        // Handle Logout
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out of your account.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00473e',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, log out!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the logout form
                    document.getElementById('logoutForm').submit();
                }
            });
        });

        // Responsive sidebar behavior
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // CSS for active states and transitions
        const style = document.createElement('style');
        style.textContent = `
            .citizen-sidebar-link {
                color: #9CA3AF;
            }
            
            .citizen-sidebar-link:hover {
                color: #FFFFFF;
                background-color: #00332c;
            }
            
            .citizen-sidebar-link.active {
                color: #faae2b;
                background-color: #00332c;
                border-left: 3px solid #faae2b;
            }
            
            /* Custom scrollbar for sidebar */
            #citizen-sidebar nav::-webkit-scrollbar {
                width: 4px;
            }
            
            #citizen-sidebar nav::-webkit-scrollbar-track {
                background: #00332c;
            }
            
            #citizen-sidebar nav::-webkit-scrollbar-thumb {
                background: #faae2b;
                border-radius: 2px;
            }
            
            #citizen-sidebar nav::-webkit-scrollbar-thumb:hover {
                background: #e09900;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush