<?php
session_start();
include 'db.php'; 

// --- API ENDPOINT: RECEIVE APPROVAL DATA FROM GOVERNMENT SYSTEM ---
if (isset($_POST['receive_approval'])) {
    header('Content-Type: application/json');
    
    $fund_request_id = $_POST['fund_request_id'] ?? null;
    $status = $_POST['status'] ?? null;
    $feedback = $_POST['feedback'] ?? '';
    $approved_amount = $_POST['approved_amount'] ?? null;
    
    // Facility details from government
    $facility_name = $_POST['facility_name'] ?? null;
    $equipment = $_POST['equipment'] ?? null;
    $schedule_date = $_POST['schedule_date'] ?? null;
    $schedule_start_time = $_POST['schedule_start_time'] ?? null;
    $schedule_end_time = $_POST['schedule_end_time'] ?? null;
    $admin_notes = $_POST['admin_notes'] ?? null;
    
    if (!$fund_request_id || !$status) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Update the fund request in my_fund_requests table
    $status_db = mysqli_real_escape_string($conn, $status);
    $feedback_db = mysqli_real_escape_string($conn, $feedback);
    $approved_db = $approved_amount ? floatval($approved_amount) : 'NULL';
    $facility_db = mysqli_real_escape_string($conn, $facility_name ?? '');
    $equipment_db = mysqli_real_escape_string($conn, $equipment ?? '');
    $date_db = $schedule_date ? "'".mysqli_real_escape_string($conn, $schedule_date)."'" : 'NULL';
    $start_time_db = $schedule_start_time ? "'".mysqli_real_escape_string($conn, $schedule_start_time)."'" : 'NULL';
    $end_time_db = $schedule_end_time ? "'".mysqli_real_escape_string($conn, $schedule_end_time)."'" : 'NULL';
    $notes_db = mysqli_real_escape_string($conn, $admin_notes ?? '');
    
    $sql = "UPDATE my_fund_requests SET 
            status = '$status_db', 
            feedback = '$feedback_db',
            approved_amount = $approved_db,
            facility_name = '$facility_db',
            equipment = '$equipment_db',
            schedule_date = $date_db,
            schedule_start_time = $start_time_db,
            schedule_end_time = $end_time_db,
            admin_notes = '$notes_db'
            WHERE government_id = '$fund_request_id'";
    
    $conn->query($sql);
    
    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    exit;
}

// 1. Siguraduhin na ang user ay naka-login
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("Error: Please login to access this portal.");
}

// 2. KUNIN ANG TUNAY NA PANGALAN NG USER MULA SA DATABASE
$user_query = $conn->query("SELECT first_name, middle_name, last_name FROM users WHERE user_id = '$user_id'");
$user_data = $user_query->fetch_assoc();

$fname = $user_data['first_name'] ?? 'Unknown';
$mname = !empty($user_data['middle_name']) ? substr($user_data['middle_name'], 0, 1) . ". " : "";
$lname = $user_data['last_name'] ?? 'User';
$user_full_name = "$fname $mname$lname"; 

// 3. KUNIN ANG MGA AVAILABLE SEMINARS PARA SA DROPDOWN
$seminar_list = $conn->query("SELECT * FROM seminars WHERE is_archived = 0 ORDER BY seminar_date DESC");

// --- FETCH HISTORY & REAL-TIME STATUS SYNC (AJAX CALLS) ---
if (isset($_GET['fetch_history'])) {
    $pending_requests = $conn->query("SELECT government_id FROM my_fund_requests WHERE user_id = '$user_id' AND status = 'pending'");
    
    while($p = $pending_requests->fetch_assoc()){
        $gov_id = $p['government_id'];
        $api_url = "https://facilities.local-government-unit-1-ph.com/api/check-status/" . $gov_id;
        
        $json = @file_get_contents($api_url);
        if($json){
            $res = json_decode($json, true);
            if(isset($res['status']) && $res['status'] !== 'pending'){
                $new_status = mysqli_real_escape_string($conn, $res['status']);
                $feedback = mysqli_real_escape_string($conn, $res['feedback'] ?? '');
                $conn->query("UPDATE my_fund_requests SET status = '$new_status', feedback = '$feedback' WHERE government_id = $gov_id");
            }
        }
    }

    $all = $conn->query("SELECT * FROM my_fund_requests WHERE user_id = '$user_id' ORDER BY id DESC");
    while($row = $all->fetch_assoc()){
        $status_css = ($row['status'] == 'Approved') ? "bg-emerald-100 text-emerald-700 border-emerald-200" : 
                     (($row['status'] == 'Rejected') ? "bg-rose-100 text-rose-700 border-rose-200" : "bg-amber-100 text-amber-700 border-amber-200");
        
        // Prepare data for View button (JSON encode for JavaScript)
        $view_data = json_encode([
            'status' => $row['status'],
            'amount' => number_format($row['amount'], 2),
            'approved_amount' => $row['approved_amount'] ? number_format($row['approved_amount'], 2) : null,
            'facility_name' => $row['facility_name'] ?? null,
            'equipment' => $row['equipment'] ?? null,
            'schedule_date' => $row['schedule_date'] ?? null,
            'schedule_start_time' => $row['schedule_start_time'] ?? null,
            'schedule_end_time' => $row['schedule_end_time'] ?? null,
            'admin_notes' => $row['admin_notes'] ?? null,
            'feedback' => $row['feedback'] ?? null,
            'purpose' => $row['purpose'],
            'seminar_info' => $row['seminar_info'] ?? null
        ], JSON_HEX_APOS | JSON_HEX_QUOT);
        
        echo "<tr class='border-b border-slate-50 hover:bg-slate-50 transition-all'>
                <td class='p-5 font-mono text-blue-600 font-bold text-xs'>#REF-{$row['government_id']}</td>
                <td class='p-5'>
                    <div class='font-black uppercase text-slate-700 text-[11px] leading-tight'>{$row['purpose']}</div>
                    <div class='text-[10px] text-slate-400 italic truncate max-w-[180px] mt-1'>{$row['logistics']}</div>
                    " . (!empty($row['seminar_info']) ? "<div class='mt-2 inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 rounded text-[9px] font-bold text-slate-500 uppercase'><i class='fas fa-link'></i> {$row['seminar_info']}</div>" : "") . "
                </td>
                <td class='p-5 font-black text-slate-900'>₱" . number_format($row['amount'], 2) . "</td>
                <td class='p-5'><span class='px-3 py-1 rounded-md border text-[9px] font-black $status_css'>{$row['status']}</span></td>
                <td class='p-5'><button onclick='viewDetails($view_data)' class='px-3 py-1.5 bg-blue-600 text-white rounded-lg text-[10px] font-bold hover:bg-blue-700 transition-all'><i class='fas fa-eye mr-1'></i>View</button></td>
              </tr>";
    }
    exit; 
}

// --- SUBMIT REQUEST VIA SERVER-SIDE CURL ---
if(isset($_POST['send_fund'])){
    $url = "https://facilities.local-government-unit-1-ph.com/api/receive-funds";
    
    $sem_info = ""; $sem_img = "";
    if(!empty($_POST['seminar_id'])){
        $s_id = $_POST['seminar_id'];
        $s_query = $conn->query("SELECT * FROM seminars WHERE seminar_id = '$s_id'");
        $s_data = $s_query->fetch_assoc();
        $sem_info = $s_data['seminar_title'] . " (" . $s_data['seminar_date'] . ")";
        $sem_img = $s_data['seminar_image_url'];
    }

    $log_items = isset($_POST['logistics_check']) ? implode(", ", $_POST['logistics_check']) : "";
    $full_log = "Categories: $log_items | Specifics: " . $_POST['logistics_extra'];

    $post_data = [
        'requester_name' => $user_full_name,
        'user_id' => $user_id,
        'amount' => $_POST['amount'],
        'purpose' => $_POST['purpose'],
        'logistics' => $full_log,
        'seminar_info' => $sem_info,
        'seminar_image' => $sem_img,
        'seminar_id' => $_POST['seminar_id'] ?? null
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response_raw = curl_exec($ch);
    $response = json_decode($response_raw, true);
    curl_close($ch);

    if (isset($response['id'])) {
        $gov_id = $response['id'];
        $amt = $_POST['amount'];
        $purp = mysqli_real_escape_string($conn, $_POST['purpose']);
        $log_db = mysqli_real_escape_string($conn, $full_log);
        $s_info_db = mysqli_real_escape_string($conn, $sem_info);
        $s_img_db = mysqli_real_escape_string($conn, $sem_img);

        $sem_id = !empty($_POST['seminar_id']) ? intval($_POST['seminar_id']) : 'NULL';
        $conn->query("INSERT INTO my_fund_requests (government_id, user_id, amount, purpose, logistics, status, seminar_info, seminar_image, seminar_id) 
                      VALUES ('$gov_id', '$user_id', '$amt', '$purp', '$log_db', 'pending', '$s_info_db', '$s_img_db', $sem_id)");
        echo "success";
    } else {
        echo "API_ERROR: " . ($response_raw ?: "Connection Timeout");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Facilities Integration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-up { animation: slideUp 0.3s ease-out forwards; }
    </style>
</head>
<body class="bg-slate-50 font-sans">

    <?php include 'admin-sidebar.php'; ?>

    <div class="flex-1 p-8 lg:ml-64">
        <header class="md:hidden fixed top-0 left-0 right-0 bg-white shadow-lg p-4 flex justify-start items-center space-x-3 z-40" id="mobile-header">
                <button id="sidebar-toggle" class="text-lgu-headline text-2xl p-1 rounded-md hover:bg-lgu-highlight/20 transition">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-lg font-bold text-lgu-headline">Energy Infrastructure</h1>
                
                
            </header>
            <header class="mb-6 hidden lg:block flex items-center space-x-3 border-b pb-4"> 
                <h1 class="text-3xl font-bold text-lgu-headline">Energy Infrastructure</h1>
            </header>
        

        <div class="w-full grid grid-cols-12 gap-6 pt-16 lg:pt-0 px-2 lg:px-8">
            <div class="col-span-12 lg:col-span-5">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-white">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-6 w-1.5 bg-emerald-600 rounded-full"></div>
                        <h2 class="text-[20px] font-bold text-slate-800">New Proposal</h2>
                    </div>

                    <form id="fundForm" class="space-y-5">
                        <div>
                            <label class="text-[10px] font-bold  text-slate-400 ml-2 mb-1 block">Link Seminar</label>
                            <select name="seminar_id" class="w-full bg-slate-50 border-2 border-slate-50 p-4 rounded-2xl font-bold text-xs outline-none focus:border-blue-500 focus:bg-white transition-all">
                                <option value="">Optional: Choose Seminar</option>
                                <?php 
                                // Reset pointer for secondary use
                                $seminar_list->data_seek(0);
                                while($sem = $seminar_list->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $sem['seminar_id']; ?>"><?php echo $sem['seminar_title']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 ml-2 mb-1 block">Amount (₱)</label>
                                <input type="number" name="amount" step="0.01" class="w-full bg-slate-50 border-2 border-slate-50 p-4 rounded-2xl font-bold text-lg outline-none focus:border-blue-500 focus:bg-white transition-all shadow-inner" required>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 ml-2 mb-1 block">Category</label>
                                <select name="purpose" class="w-full bg-slate-50 border-2 border-slate-50 p-4 rounded-2xl font-bold text-xs outline-none">
                                    <option>Seminar Logistics</option>
                                    <option>IEC Materials</option>
                                    <option>Tech Services</option>
                                    <option>EEC Program Req</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold uppercase text-slate-400 ml-2 mb-1 block">Checklist (A-L)</label>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 max-h-36 overflow-y-auto custom-scrollbar space-y-2">
                                <?php 
                                $opts = ["A. Venue & Physical", "B. Audio-Visual", "C. Speakers/Services", "D. IEC Materials", "E. Food & Welfare", "F. IT Systems", "H. Demo Items", "L. Contingency"];
                                foreach($opts as $opt): 
                                ?>
                                <label class="flex items-center gap-2 text-5px font-bold text-slate-600 cursor-pointer">
                                    <input type="checkbox" name="logistics_check[]" value="<?php echo $opt; ?>" class="rounded text-blue-600"> <?php echo $opt; ?>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold uppercase text-slate-400 ml-2 mb-1 block">Detailed Specifications</label>
                            <textarea name="logistics_extra" placeholder="Enter full itemized list..." class="w-full bg-slate-50 border-2 border-slate-50 p-4 rounded-2xl h-24 text-xs outline-none focus:border-blue-500 focus:bg-white transition-all shadow-inner" required></textarea>
                        </div>

                        <button type="submit" id="subBtn" class="w-full bg-emerald-900 text-white p-5 rounded-2xl font-bold hover:bg-emerald-600 transition-all shadow-lg active:scale-95">
                            Submit Proposal
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-7">
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-white overflow-hidden flex flex-col h-full">
                    <div class="p-6 bg-emerald-900 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 bg-blue-500 rounded-full animate-ping"></span>
                            <span class="font-bold text-[20px] tracking-widest text-white">Transaction History</span>
                        </div>
                    </div>
                    
                    <div class="overflow-y-auto max-h-[600px] custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/80 sticky top-0 backdrop-blur-md">
                                <tr class="text-[12px] font-bold text-slate-400 border-b border-slate-100">
                                    <th class="p-5">Reference</th>
                                    <th class="p-5">Details</th>
                                    <th class="p-5">Budget</th>
                                    <th class="p-5">Status</th>
                                    <th class="p-5">Action</th>
                                </tr>
                            </thead>
                            <tbody id="historyTable" class="divide-y divide-slate-50"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="statusModal" class="fixed inset-0 z-[999] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-sm rounded-[3rem] shadow-2xl p-10 text-center animate-slide-up">
            
            <div id="modalLoading" class="block">
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <div class="absolute inset-0 border-4 border-blue-500/20 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    <div class="absolute inset-4 bg-blue-50 rounded-full flex items-center justify-center">
                        <i class="fas fa-server text-blue-600 animate-pulse"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 uppercase italic tracking-tighter">Transmitting</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-2">Linking to Government Gateway...</p>
            </div>

            <div id="modalSuccess" class="hidden">
                <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-double text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 uppercase italic tracking-tighter">Verified!</h3>
                <p class="text-[10px] font-bold text-slate-500 mt-2 px-4 leading-relaxed">Proposal has been logged into the secure ledger for executive review.</p>
                <button onclick="closeModal()" class="mt-8 w-full bg-slate-900 text-white p-4 rounded-2xl font-bold uppercase italic tracking-widest hover:bg-emerald-500 transition-all shadow-lg">
                    Return to Portal
                </button>
            </div>

            <div id="modalError" class="hidden">
                <div class="w-24 h-24 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 uppercase italic tracking-tighter">Link Failed</h3>
                <p id="errorText" class="text-[10px] font-bold text-slate-500 mt-2 px-4">Connection Timeout</p>
                <button onclick="closeModal()" class="mt-8 w-full bg-slate-900 text-white p-4 rounded-2xl font-bold uppercase italic tracking-widest hover:bg-rose-500 transition-all shadow-lg">
                    Retry Transaction
                </button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('statusModal');
        const viewLoading = document.getElementById('modalLoading');
        const viewSuccess = document.getElementById('modalSuccess');
        const viewError = document.getElementById('modalError');

        async function load() {
            try {
                const res = await fetch('request_fund.php?fetch_history=1');
                const html = await res.text();
                document.getElementById('historyTable').innerHTML = html;
            } catch (err) { console.log("Sync error..."); }
        }

        function openModal() {
            modal.classList.replace('hidden', 'flex');
            viewLoading.classList.replace('hidden', 'block');
            viewSuccess.classList.add('hidden');
            viewError.classList.add('hidden');
        }

        function closeModal() {
            modal.classList.replace('flex', 'hidden');
        }

        document.getElementById('fundForm').onsubmit = async function(e) {
            e.preventDefault();
            
            // Show Modal Instead of Button Spinner alone
            openModal();

            const fd = new FormData(this);
            fd.append('send_fund', '1');

            try {
                // Artificial delay to show the nice animation
                await new Promise(r => setTimeout(r, 1000));

                const r = await fetch('request_fund.php', { method: 'POST', body: fd });
                const result = await r.text();

                if(result.trim() === 'success') { 
                    this.reset(); 
                    load();
                    viewLoading.classList.replace('block', 'hidden');
                    viewSuccess.classList.remove('hidden');
                } else { 
                    document.getElementById('errorText').innerText = result;
                    viewLoading.classList.replace('block', 'hidden');
                    viewError.classList.remove('hidden');
                }
            } catch (err) { 
                document.getElementById('errorText').innerText = "Network Connection Error";
                viewLoading.classList.replace('block', 'hidden');
                viewError.classList.remove('hidden');
            }
        }

        // View Details Modal with SweetAlert2
        function viewDetails(data) {
            if (data.status === 'pending' || data.status === 'Pending') {
                Swal.fire({
                    icon: 'info',
                    title: 'Awaiting Approval',
                    html: `
                        <div class="text-left">
                            <p class="text-gray-600 mb-4">Your fund request is currently being reviewed by the Local Government Unit.</p>
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <p class="text-amber-700 text-sm font-medium"><i class="fas fa-clock mr-2"></i>Status: <span class="font-bold">Pending Review</span></p>
                                <p class="text-amber-600 text-xs mt-2">You will receive facility assignment details and approval information once your request has been processed.</p>
                            </div>
                        </div>
                    `,
                    confirmButtonColor: '#0ea5e9',
                    confirmButtonText: 'Understood'
                });
                return;
            }
            
            if (data.status === 'Rejected' || data.status === 'rejected') {
                Swal.fire({
                    icon: 'error',
                    title: 'Request Rejected',
                    html: `
                        <div class="text-left">
                            <div class="bg-rose-50 border border-rose-200 rounded-lg p-4 mb-4">
                                <p class="text-rose-700 text-sm font-medium"><i class="fas fa-times-circle mr-2"></i>Status: <span class="font-bold">Rejected</span></p>
                            </div>
                            ${data.feedback ? `<div class="bg-gray-50 rounded-lg p-4"><p class="text-gray-600 text-sm"><i class="fas fa-comment-alt mr-2"></i><strong>Feedback:</strong> ${data.feedback}</p></div>` : ''}
                        </div>
                    `,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Close'
                });
                return;
            }
            
            // Approved - Show all details
            let scheduleText = '';
            if (data.schedule_date) {
                const dateObj = new Date(data.schedule_date);
                const formattedDate = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                scheduleText = formattedDate;
                if (data.schedule_start_time && data.schedule_end_time) {
                    scheduleText += ` @ ${data.schedule_start_time} - ${data.schedule_end_time}`;
                } else if (data.schedule_start_time) {
                    scheduleText += ` @ ${data.schedule_start_time}`;
                }
            }
            
            Swal.fire({
                icon: 'success',
                title: 'Request Approved',
                html: `
                    <div class="text-left space-y-3">
                        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                            <p class="text-emerald-700 text-sm font-medium"><i class="fas fa-check-circle mr-2"></i>Status: <span class="font-bold">Approved</span></p>
                            ${data.approved_amount ? `<p class="text-emerald-600 text-lg font-bold mt-2"><i class="fas fa-peso-sign mr-1"></i>₱${data.approved_amount}</p>` : ''}
                        </div>
                        
                        ${data.facility_name ? `
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-blue-800 font-bold text-sm mb-2"><i class="fas fa-building mr-2"></i>Assigned Facility</p>
                            <p class="text-blue-700 font-medium">${data.facility_name}</p>
                        </div>
                        ` : ''}
                        
                        ${data.equipment ? `
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <p class="text-purple-800 font-bold text-sm mb-2"><i class="fas fa-tools mr-2"></i>Assigned Equipment</p>
                            <p class="text-purple-700 text-sm">${data.equipment}</p>
                        </div>
                        ` : ''}
                        
                        ${scheduleText ? `
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <p class="text-orange-800 font-bold text-sm mb-2"><i class="fas fa-calendar-alt mr-2"></i>Schedule</p>
                            <p class="text-orange-700 text-sm">${scheduleText}</p>
                        </div>
                        ` : ''}
                        
                        ${data.admin_notes ? `
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-gray-800 font-bold text-sm mb-2"><i class="fas fa-sticky-note mr-2"></i>Admin Notes</p>
                            <p class="text-gray-600 text-sm italic">${data.admin_notes}</p>
                        </div>
                        ` : ''}
                        
                        ${data.feedback ? `
                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                            <p class="text-slate-800 font-bold text-sm mb-2"><i class="fas fa-comment-alt mr-2"></i>Feedback</p>
                            <p class="text-slate-600 text-sm">${data.feedback}</p>
                        </div>
                        ` : ''}
                    </div>
                `,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Close',
                width: '500px'
            });
        }

        load();
        setInterval(load, 5000);
    </script>
</body>
</html>