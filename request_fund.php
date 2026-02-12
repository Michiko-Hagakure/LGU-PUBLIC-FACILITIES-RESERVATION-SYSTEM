<?php
session_start();
include 'db.php'; 

// --- API ENDPOINT: RECEIVE APPROVAL DATA FROM GOVERNMENT SYSTEM ---
// Updated to match the "receive_facility_approval" key from the documentation
if (isset($_POST['receive_facility_approval'])) {
    header('Content-Type: application/json');
    
    $fund_request_id = $_POST['facility_request_id'] ?? null;
    $status = $_POST['status'] ?? null;
    $feedback = $_POST['admin_feedback'] ?? '';
    $approved_amount = $_POST['approved_budget'] ?? null;
    
    // Facility details from government callback
    $facility_id = $_POST['facility_id'] ?? null;
    $facility_name = $_POST['facility_name'] ?? null;
    $facility_capacity = $_POST['facility_capacity'] ?? null;
    $equipment = $_POST['assigned_equipment'] ?? null;
    $schedule_date = $_POST['scheduled_date'] ?? null;
    $schedule_start_time = $_POST['scheduled_start_time'] ?? null;
    $schedule_end_time = $_POST['scheduled_end_time'] ?? null;
    $admin_notes = $_POST['admin_notes'] ?? null;
    $budget_breakdown = $_POST['budget_breakdown'] ?? null;
    $approved_by = $_POST['approved_by'] ?? null;
    $approved_at = $_POST['approved_at'] ?? null;
    
    if (!$fund_request_id || !$status) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    $status_db = mysqli_real_escape_string($conn, $status);
    $feedback_db = mysqli_real_escape_string($conn, $feedback);
    $approved_db = $approved_amount ? floatval($approved_amount) : 'NULL';
    $facility_id_db = $facility_id ? intval($facility_id) : 'NULL';
    $facility_db = mysqli_real_escape_string($conn, $facility_name ?? '');
    $facility_cap_db = $facility_capacity ? intval($facility_capacity) : 'NULL';
    $equipment_db = mysqli_real_escape_string($conn, $equipment ?? '');
    $date_db = $schedule_date ? "'".mysqli_real_escape_string($conn, $schedule_date)."'" : 'NULL';
    $start_time_db = $schedule_start_time ? "'".mysqli_real_escape_string($conn, $schedule_start_time)."'" : 'NULL';
    $end_time_db = $schedule_end_time ? "'".mysqli_real_escape_string($conn, $schedule_end_time)."'" : 'NULL';
    $notes_db = mysqli_real_escape_string($conn, $admin_notes ?? '');
    $breakdown_db = $budget_breakdown ? "'".mysqli_real_escape_string($conn, $budget_breakdown)."'" : 'NULL';
    $approved_by_db = mysqli_real_escape_string($conn, $approved_by ?? '');
    $approved_at_db = $approved_at ? "'".mysqli_real_escape_string($conn, $approved_at)."'" : 'NULL';
    
    $sql = "UPDATE my_fund_requests SET 
            status = '$status_db', 
            feedback = '$feedback_db',
            approved_amount = $approved_db,
            facility_id = $facility_id_db,
            facility_name = '$facility_db',
            facility_capacity = $facility_cap_db,
            equipment = '$equipment_db',
            schedule_date = $date_db,
            schedule_start_time = $start_time_db,
            schedule_end_time = $end_time_db,
            admin_notes = '$notes_db',
            budget_breakdown = $breakdown_db,
            approved_by = '$approved_by_db',
            approved_at = $approved_at_db
            WHERE government_id = '$fund_request_id'";
    
    $conn->query($sql);
    echo json_encode(['success' => true, 'message' => 'LGU Sync Complete']);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) die("Error: Please login.");

$user_query = $conn->query("SELECT first_name, last_name, email FROM users WHERE user_id = '$user_id'");
$user_data = $user_query->fetch_assoc();
$user_full_name = ($user_data['first_name'] ?? 'User') . " " . ($user_data['last_name'] ?? '');

$seminar_list = $conn->query("SELECT * FROM seminars WHERE is_archived = 0 ORDER BY seminar_date DESC");

// --- AJAX FETCH HISTORY ---
if (isset($_GET['fetch_history'])) {
    $all = $conn->query("SELECT * FROM my_fund_requests WHERE user_id = '$user_id' ORDER BY id DESC");
    while($row = $all->fetch_assoc()){
        $status_css = ($row['status'] == 'approved') ? "bg-emerald-100 text-emerald-700 border-emerald-200" : 
                     (($row['status'] == 'rejected') ? "bg-rose-100 text-rose-700 border-rose-200" : "bg-amber-100 text-amber-700 border-amber-200");
        
        $view_data = json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT);
        
        echo "<tr class='border-b border-slate-50 hover:bg-slate-50 transition-all'>
                <td class='p-5 font-mono text-blue-600 font-bold text-xs'>#LGU-{$row['government_id']}</td>
                <td class='p-5'>
                    <div class='font-black uppercase text-slate-700 text-[11px]'>{$row['purpose']}</div>
                    <div class='text-[10px] text-slate-400 mt-1'>{$row['seminar_info']}</div>
                </td>
                <td class='p-5 font-black text-slate-900'>₱" . number_format($row['amount'], 2) . "</td>
                <td class='p-5'><span class='px-3 py-1 rounded-md border text-[9px] font-black $status_css'>{$row['status']}</span></td>
                <td class='p-5'><button onclick='viewDetails($view_data)' class='px-3 py-1.5 bg-blue-600 text-white rounded-lg text-[10px] font-bold'><i class='fas fa-eye mr-1'></i>View</button></td>
              </tr>";
    }
    exit; 
}

// --- SUBMIT TO GOVERNMENT API ---
if(isset($_POST['send_fund'])){
    $api_url = "https://facilities.local-government-unit-1-ph.com/api/energy-efficiency/facility-request";
    
    $sem_id = $_POST['seminar_id'];
    $s_query = $conn->query("SELECT * FROM seminars WHERE seminar_id = '$sem_id'");
    $s = $s_query->fetch_assoc();

    // Map your local form to the Government API structure
    $post_data = [
        'event_title' => $s['seminar_title'],
        'purpose' => $_POST['purpose_text'],
        'organizer_office' => "Energy Efficiency Division",
        'point_person' => $user_full_name,
        'contact_number' => "09123456789", // Replace with actual user contact if available
        'contact_email' => $user_data['email'] ?? 'energy@local.gov',
        'preferred_date' => $s['seminar_date'],
        'start_time' => $s['start_time'],
        'end_time' => $s['end_time'],
        'audience_type' => $_POST['audience_type'],
        'session_type' => 'orientation',
        'facility_type' => $_POST['facility_size'],
        'needs_projector' => isset($_POST['needs_projector']) ? true : false,
        'needs_sound_system' => true,
        'needs_wifi' => true,
        'user_id' => $user_id,
        'seminar_id' => $sem_id
    ];

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response_raw = curl_exec($ch);
    $response = json_decode($response_raw, true);
    curl_close($ch);

    if (isset($response['data']['id'])) {
        $gov_id = $response['data']['id'];
        $amt = $_POST['amount'];
        $purp = mysqli_real_escape_string($conn, $_POST['purpose_text']);
        $s_info = mysqli_real_escape_string($conn, $s['seminar_title']);

        $conn->query("INSERT INTO my_fund_requests (government_id, user_id, amount, purpose, status, seminar_info, seminar_id) 
                      VALUES ('$gov_id', '$user_id', '$amt', '$purp', 'pending', '$s_info', '$sem_id')");
        echo "success";
    } else {
        echo "LGU_API_ERROR: " . ($response['message'] ?? "Connection Failed");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Facility & Fund Integration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 font-sans">
    <?php include 'admin-sidebar.php'; ?>

    <div class="flex-1 p-8 lg:ml-64">
        <div class="w-full grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-5">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-white">
                    <h2 class="text-xl font-bold text-slate-800 mb-6">Facility & Budget Request</h2>
                    <form id="fundForm" class="space-y-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 block ml-2">Select Linked Seminar</label>
                            <select name="seminar_id" class="w-full bg-slate-50 border-2 p-4 rounded-2xl font-bold text-xs outline-none focus:border-emerald-500" required>
                                <?php 
                                $seminar_list->data_seek(0);
                                while($sem = $seminar_list->fetch_assoc()): ?>
                                    <option value="<?php echo $sem['seminar_id']; ?>"><?php echo $sem['seminar_title']; ?> (<?php echo $sem['seminar_date']; ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 block ml-2">Target Audience</label>
                                <select name="audience_type" class="w-full bg-slate-50 border-2 p-4 rounded-2xl font-bold text-xs outline-none">
                                    <option value="public">General Public</option>
                                    <option value="employees">LGU Employees</option>
                                    <option value="students">Students/Youth</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 block ml-2">Facility Size</label>
                                <select name="facility_size" class="w-full bg-slate-50 border-2 p-4 rounded-2xl font-bold text-xs outline-none">
                                    <option value="small">Small (<50 pax)</option>
                                    <option value="medium">Medium (50-99 pax)</option>
                                    <option value="large">Large (100+ pax)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-slate-400 block ml-2">Estimated Budget Needed (₱)</label>
                            <input type="number" name="amount" class="w-full bg-slate-50 border-2 p-4 rounded-2xl font-bold text-lg outline-none focus:border-emerald-500" required>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-slate-400 block ml-2">Proposal/Purpose Details</label>
                            <textarea name="purpose_text" placeholder="Explain why this facility and funding is needed..." class="w-full bg-slate-50 border-2 p-4 rounded-2xl h-24 text-xs outline-none focus:border-emerald-500" required></textarea>
                        </div>

                        <div class="flex items-center gap-2 p-2">
                            <input type="checkbox" name="needs_projector" id="proj" class="rounded">
                            <label for="proj" class="text-xs font-bold text-slate-600">Request High-End Projector & Audio</label>
                        </div>

                        <button type="submit" class="w-full bg-emerald-900 text-white p-5 rounded-2xl font-bold hover:bg-emerald-700 transition-all shadow-lg">
                            Submit to Government Gateway
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-7">
                <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden flex flex-col h-full">
                    <div class="p-6 bg-slate-900 text-white font-bold">Request Tracking</div>
                    <div class="overflow-y-auto max-h-[600px]">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 sticky top-0">
                                <tr class="text-[11px] font-bold text-slate-400 border-b">
                                    <th class="p-5">ID</th>
                                    <th class="p-5">Seminar</th>
                                    <th class="p-5">Budget</th>
                                    <th class="p-5">Status</th>
                                    <th class="p-5">Action</th>
                                </tr>
                            </thead>
                            <tbody id="historyTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="statusModal" class="fixed inset-0 z-[999] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-sm rounded-[3rem] p-10 text-center">
            <div id="modalLoading">
                <div class="w-16 h-16 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <h3 class="text-xl font-bold">Communicating with LGU...</h3>
            </div>
        </div>
    </div>

    <script>
        async function load() {
            const res = await fetch('request_fund.php?fetch_history=1');
            document.getElementById('historyTable').innerHTML = await res.text();
        }

        document.getElementById('fundForm').onsubmit = async function(e) {
            e.preventDefault();
            document.getElementById('statusModal').classList.replace('hidden', 'flex');
            
            const fd = new FormData(this);
            fd.append('send_fund', '1');

            const r = await fetch('request_fund.php', { method: 'POST', body: fd });
            const result = await r.text();

            if(result.trim() === 'success') { 
                Swal.fire('Submitted!', 'Your request is now with the LGU Admin.', 'success');
                this.reset();
                load();
            } else { 
                Swal.fire('Error', result, 'error');
            }
            document.getElementById('statusModal').classList.replace('flex', 'hidden');
        }

        function viewDetails(data) {
            let html = `<div class="text-left space-y-2 text-sm">`;
            
            if (data.status === 'approved') {
                html += `
                <div class="bg-emerald-50 p-3 rounded-lg border border-emerald-200">
                    <p class="font-bold text-emerald-800"><i class="fas fa-check-circle mr-1"></i> Approved Budget: ₱${parseFloat(data.approved_amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2})}</p>
                    <p class="text-xs text-emerald-600">LGU has allocated these funds for your seminar.</p>
                    ${data.approved_by ? `<p class="text-[10px] text-emerald-500 mt-1">Approved by: ${data.approved_by} ${data.approved_at ? '— ' + data.approved_at : ''}</p>` : ''}
                </div>
                <div class="bg-blue-50 p-3 rounded-lg border border-blue-200 mt-2">
                    <p class="font-bold text-blue-800"><i class="fas fa-building mr-1"></i> Venue: ${data.facility_name || 'TBD'}</p>
                    ${data.facility_capacity ? `<p class="text-[10px] text-blue-500">Capacity: ${data.facility_capacity} pax</p>` : ''}
                    <p class="text-xs text-blue-700"><i class="fas fa-tools mr-1"></i> Equipment: ${data.equipment || 'None specified'}</p>
                    <p class="text-xs font-bold mt-1"><i class="fas fa-calendar mr-1"></i> ${data.schedule_date || 'TBD'} (${data.schedule_start_time || '?'} - ${data.schedule_end_time || '?'})</p>
                </div>`;

                // Budget Breakdown
                if (data.budget_breakdown) {
                    let breakdown;
                    try {
                        breakdown = typeof data.budget_breakdown === 'string' ? JSON.parse(data.budget_breakdown) : data.budget_breakdown;
                    } catch(e) { breakdown = null; }

                    if (breakdown) {
                        const categories = {food: 'Food & Refreshments', materials: 'Materials & Handouts', other: 'Other Expenses'};
                        const catIcons = {food: 'fa-utensils', materials: 'fa-book', other: 'fa-receipt'};
                        const catColors = {food: 'emerald', materials: 'blue', other: 'purple'};
                        let hasItems = false;

                        let breakdownHtml = `<div class="mt-2 border border-slate-200 rounded-lg overflow-hidden">
                            <div class="bg-slate-800 text-white px-3 py-2 text-xs font-bold"><i class="fas fa-receipt mr-1"></i> Budget Breakdown</div>
                            <table class="w-full text-xs">
                                <thead class="bg-slate-50"><tr>
                                    <th class="text-left px-3 py-1.5 text-[10px] font-bold text-slate-400">CATEGORY</th>
                                    <th class="text-left px-3 py-1.5 text-[10px] font-bold text-slate-400">ITEM</th>
                                    <th class="text-center px-3 py-1.5 text-[10px] font-bold text-slate-400">QTY</th>
                                    <th class="text-right px-3 py-1.5 text-[10px] font-bold text-slate-400">UNIT COST</th>
                                    <th class="text-right px-3 py-1.5 text-[10px] font-bold text-slate-400">SUBTOTAL</th>
                                </tr></thead><tbody>`;

                        let grandTotal = 0;
                        for (const [catKey, catLabel] of Object.entries(categories)) {
                            if (breakdown[catKey] && breakdown[catKey].length > 0) {
                                hasItems = true;
                                breakdown[catKey].forEach(item => {
                                    const sub = (item.qty || 1) * (item.unit_cost || 0);
                                    grandTotal += sub;
                                    breakdownHtml += `<tr class="border-t border-slate-100">
                                        <td class="px-3 py-1.5"><span class="text-${catColors[catKey]}-600"><i class="fas ${catIcons[catKey]} mr-1"></i>${catLabel}</span></td>
                                        <td class="px-3 py-1.5 font-semibold text-slate-700">${item.name || 'N/A'}</td>
                                        <td class="px-3 py-1.5 text-center">${item.qty || 1}</td>
                                        <td class="px-3 py-1.5 text-right">₱${parseFloat(item.unit_cost || 0).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                                        <td class="px-3 py-1.5 text-right font-bold">₱${sub.toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                                    </tr>`;
                                });
                            }
                        }

                        breakdownHtml += `</tbody></table>`;
                        if (hasItems) {
                            breakdownHtml += `<div class="bg-emerald-50 px-3 py-2 flex justify-between items-center border-t border-emerald-200">
                                <span class="font-bold text-xs text-emerald-800">TOTAL BUDGET</span>
                                <span class="font-black text-sm text-emerald-700">₱${grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                            </div>`;
                        }
                        breakdownHtml += `</div>`;

                        if (hasItems) html += breakdownHtml;
                    }
                }

                if (data.admin_notes) html += `<div class="mt-2 p-2 bg-amber-50 rounded border border-amber-200"><b class="text-[10px] text-amber-600 uppercase">Admin Notes:</b><br><span class="text-xs">${data.admin_notes}</span></div>`;

            } else if (data.status === 'rejected') {
                html += `<div class="bg-rose-50 p-3 rounded-lg border border-rose-200">
                    <p class="font-bold text-rose-800"><i class="fas fa-times-circle mr-1"></i> Request Rejected</p>
                    <p class="text-xs text-rose-600 mt-1">Your facility request was not approved.</p>
                </div>`;
            } else {
                html += `<p class="bg-amber-50 p-3 rounded border border-amber-200"><i class="fas fa-clock mr-1"></i> Status: <b>${data.status.toUpperCase()}</b><br>Please wait for the LGU Admin to review your seminar details.</p>`;
            }

            if(data.feedback) html += `<div class="mt-2 p-2 bg-slate-100 rounded"><b class="text-[10px] text-slate-500 uppercase">LGU Feedback:</b><br><span class="text-xs">${data.feedback}</span></div>`;
            html += `</div>`;

            Swal.fire({
                title: data.status === 'approved' ? '<span class="text-emerald-700">Request Approved</span>' : data.status === 'rejected' ? '<span class="text-rose-700">Request Rejected</span>' : 'Request Details',
                html: html,
                width: '550px',
                confirmButtonColor: '#065f46'
            });
        }

        load();
        setInterval(load, 10000); // Sync every 10 seconds
    </script>
</body>
</html>