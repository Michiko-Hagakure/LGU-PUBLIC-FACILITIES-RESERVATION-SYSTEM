<?php
session_start();
require_once 'db.php';

// ============================================================================
// API ENDPOINT: Receive approval data from Government System
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receive_approval'])) {
    header('Content-Type: application/json');
    
    $road_request_id = $_POST['road_request_id'] ?? null;
    $status = $_POST['status'] ?? null;
    $feedback = $_POST['feedback'] ?? null;
    $assigned_personnel = $_POST['assigned_personnel'] ?? null;
    $assigned_equipment = $_POST['assigned_equipment'] ?? null;
    $traffic_plan = $_POST['traffic_plan'] ?? null;
    $deployment_date = $_POST['deployment_date'] ?? null;
    $deployment_start_time = $_POST['deployment_start_time'] ?? null;
    $deployment_end_time = $_POST['deployment_end_time'] ?? null;
    $admin_notes = $_POST['admin_notes'] ?? null;
    
    if (!$road_request_id) {
        echo json_encode(['success' => false, 'message' => 'Missing road_request_id']);
        exit;
    }
    
    // Sanitize inputs
    $status_db = $conn->real_escape_string($status);
    $feedback_db = $conn->real_escape_string($feedback ?? '');
    $personnel_db = $conn->real_escape_string($assigned_personnel ?? '');
    $equipment_db = $conn->real_escape_string($assigned_equipment ?? '');
    $traffic_plan_db = $conn->real_escape_string($traffic_plan ?? '');
    $deploy_date_db = $deployment_date ? "'" . $conn->real_escape_string($deployment_date) . "'" : "NULL";
    $deploy_start_db = $deployment_start_time ? "'" . $conn->real_escape_string($deployment_start_time) . "'" : "NULL";
    $deploy_end_db = $deployment_end_time ? "'" . $conn->real_escape_string($deployment_end_time) . "'" : "NULL";
    $notes_db = $conn->real_escape_string($admin_notes ?? '');
    
    // Update the local record
    $sql = "UPDATE my_road_assistance_requests SET 
            status = '$status_db', 
            feedback = '$feedback_db',
            assigned_personnel = '$personnel_db',
            assigned_equipment = '$equipment_db',
            traffic_plan = '$traffic_plan_db',
            deployment_date = $deploy_date_db,
            deployment_start_time = $deploy_start_db,
            deployment_end_time = $deploy_end_db,
            admin_notes = '$notes_db'
            WHERE government_id = '$road_request_id'";
    
    $conn->query($sql);
    
    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    exit;
}

// ============================================================================
// Check if user is logged in
// ============================================================================
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

// ============================================================================
// Government API Configuration
// ============================================================================
$government_api_url = 'https://facilities.local-government-unit-1-ph.com/api/road-assistance';

// ============================================================================
// Handle Form Submission - Submit Road Assistance Request
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    $event_name = $_POST['event_name'] ?? '';
    $event_description = $_POST['event_description'] ?? '';
    $event_location = $_POST['event_location'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $event_start_time = $_POST['event_start_time'] ?? '';
    $event_end_time = $_POST['event_end_time'] ?? '';
    $expected_attendees = $_POST['expected_attendees'] ?? 0;
    $affected_roads = $_POST['affected_roads'] ?? '';
    $assistance_type = $_POST['assistance_type'] ?? '';
    $special_requirements = $_POST['special_requirements'] ?? '';
    $contact_phone = $_POST['contact_phone'] ?? '';
    $contact_email = $_POST['contact_email'] ?? '';

    // Send request to Government API
    $post_data = [
        'requester_name' => $user['full_name'] ?? $user['username'],
        'user_id' => $user_id,
        'event_name' => $event_name,
        'event_description' => $event_description,
        'event_location' => $event_location,
        'event_date' => $event_date,
        'event_start_time' => $event_start_time,
        'event_end_time' => $event_end_time,
        'expected_attendees' => $expected_attendees,
        'affected_roads' => $affected_roads,
        'assistance_type' => $assistance_type,
        'special_requirements' => $special_requirements,
        'contact_phone' => $contact_phone,
        'contact_email' => $contact_email,
    ];

    $ch = curl_init($government_api_url . '/request');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200 || $http_code == 201) {
        $result = json_decode($response, true);
        if (isset($result['id'])) {
            // Save to local database with government_id reference
            $gov_id = $conn->real_escape_string($result['id']);
            $event_name_db = $conn->real_escape_string($event_name);
            $event_desc_db = $conn->real_escape_string($event_description);
            $event_loc_db = $conn->real_escape_string($event_location);
            $affected_roads_db = $conn->real_escape_string($affected_roads);
            $assistance_type_db = $conn->real_escape_string($assistance_type);
            $special_req_db = $conn->real_escape_string($special_requirements);
            $phone_db = $conn->real_escape_string($contact_phone);
            $email_db = $conn->real_escape_string($contact_email);
            
            $insert_sql = "INSERT INTO my_road_assistance_requests 
                (user_id, government_id, event_name, event_description, event_location, 
                event_date, event_start_time, event_end_time, expected_attendees,
                affected_roads, assistance_type, special_requirements, 
                contact_phone, contact_email, status, created_at) 
                VALUES 
                ($user_id, '$gov_id', '$event_name_db', '$event_desc_db', '$event_loc_db',
                '$event_date', '$event_start_time', '$event_end_time', $expected_attendees,
                '$affected_roads_db', '$assistance_type_db', '$special_req_db',
                '$phone_db', '$email_db', 'pending', NOW())";
            
            $conn->query($insert_sql);
            $success_message = "Road assistance request submitted successfully!";
        }
    } else {
        $error_message = "Failed to submit request. Please try again.";
    }
}

// ============================================================================
// Sync status from Government API
// ============================================================================
$my_requests = $conn->query("SELECT * FROM my_road_assistance_requests WHERE user_id = $user_id ORDER BY created_at DESC");
while ($req = $my_requests->fetch_assoc()) {
    if ($req['government_id'] && $req['status'] === 'pending') {
        $ch = curl_init($government_api_url . '/status/' . $req['government_id']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $status_data = json_decode($response, true);
        if (isset($status_data['data']['approval_status']) && $status_data['data']['approval_status'] !== 'pending') {
            $new_status = $conn->real_escape_string($status_data['data']['approval_status']);
            $feedback = $conn->real_escape_string($status_data['data']['feedback'] ?? '');
            $conn->query("UPDATE my_road_assistance_requests SET status = '$new_status', feedback = '$feedback' WHERE id = " . $req['id']);
        }
    }
}

// Refresh the requests list
$my_requests = $conn->query("SELECT * FROM my_road_assistance_requests WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Road Assistance Request - Road and Transportation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/lucide-static@latest/font/lucide.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-600 to-amber-500 rounded-xl p-6 mb-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="traffic-cone" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Road Assistance Request</h1>
                        <p class="text-orange-100">Request traffic management support for your events</p>
                    </div>
                </div>
            </div>

            <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <?php echo $success_message; ?>
            </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?php echo $error_message; ?>
            </div>
            <?php endif; ?>

            <!-- Request Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="file-plus" class="w-5 h-5 text-orange-600"></i>
                    New Road Assistance Request
                </h2>
                
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="submit_request" value="1">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Event Name *</label>
                            <input type="text" name="event_name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="e.g., Energy Conservation Seminar">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Event Location *</label>
                            <input type="text" name="event_location" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="e.g., Caloocan City Hall">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event Description</label>
                        <textarea name="event_description" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="Brief description of the event..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Event Date *</label>
                            <input type="date" name="event_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                            <input type="time" name="event_start_time"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                            <input type="time" name="event_end_time"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Attendees</label>
                            <input type="number" name="expected_attendees" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="Estimated number of attendees">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assistance Type</label>
                            <select name="assistance_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <option value="">-- Select Type --</option>
                                <option value="Traffic Management">Traffic Management</option>
                                <option value="Road Closure">Temporary Road Closure</option>
                                <option value="Vehicle Escort">Vehicle Escort Service</option>
                                <option value="Traffic Signage">Traffic Signage & Cones</option>
                                <option value="Personnel Deployment">Traffic Personnel Deployment</option>
                                <option value="Traffic Rerouting">Traffic Rerouting Plan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Affected Roads/Streets</label>
                        <textarea name="affected_roads" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="List roads that may be affected by the event..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Special Requirements</label>
                        <textarea name="special_requirements" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="Any special traffic management requirements..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                            <input type="tel" name="contact_phone"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="Your contact number">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                            <input type="email" name="contact_email"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="Your email address">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="w-full bg-orange-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-orange-700 transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="send" class="w-5 h-5"></i>
                            Submit Road Assistance Request
                        </button>
                    </div>
                </form>
            </div>

            <!-- Transaction History -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                    <h2 class="text-white font-semibold flex items-center gap-2">
                        <i data-lucide="history" class="w-5 h-5"></i>
                        Request History
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Event</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Location</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">Date</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php 
                            $my_requests->data_seek(0);
                            while ($row = $my_requests->fetch_assoc()): 
                                $status_class = match(strtolower($row['status'])) {
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    default => 'bg-yellow-100 text-yellow-800'
                                };
                                
                                $view_data = json_encode([
                                    'id' => $row['id'],
                                    'event_name' => $row['event_name'],
                                    'event_description' => $row['event_description'],
                                    'event_location' => $row['event_location'],
                                    'event_date' => $row['event_date'],
                                    'event_start_time' => $row['event_start_time'],
                                    'event_end_time' => $row['event_end_time'],
                                    'expected_attendees' => $row['expected_attendees'],
                                    'affected_roads' => $row['affected_roads'],
                                    'assistance_type' => $row['assistance_type'],
                                    'status' => $row['status'],
                                    'feedback' => $row['feedback'] ?? '',
                                    'assigned_personnel' => $row['assigned_personnel'] ?? '',
                                    'assigned_equipment' => $row['assigned_equipment'] ?? '',
                                    'traffic_plan' => $row['traffic_plan'] ?? '',
                                    'deployment_date' => $row['deployment_date'] ?? '',
                                    'deployment_start_time' => $row['deployment_start_time'] ?? '',
                                    'deployment_end_time' => $row['deployment_end_time'] ?? '',
                                    'admin_notes' => $row['admin_notes'] ?? '',
                                ]);
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars($row['event_name']); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo htmlspecialchars($row['assistance_type'] ?? 'N/A'); ?></p>
                                </td>
                                <td class="px-4 py-3 text-gray-700"><?php echo htmlspecialchars(substr($row['event_location'], 0, 30)); ?></td>
                                <td class="px-4 py-3 text-gray-700"><?php echo date('M d, Y', strtotime($row['event_date'])); ?></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $status_class; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick='viewDetails(<?php echo $view_data; ?>)'
                                            class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                                        <i data-lucide="eye" class="w-3 h-3 inline"></i> View
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if ($my_requests->num_rows === 0): ?>
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    No road assistance requests yet.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function viewDetails(data) {
            let content = '';
            
            if (data.status.toLowerCase() === 'pending') {
                Swal.fire({
                    icon: 'info',
                    title: 'Awaiting Approval',
                    html: `
                        <div class="text-left">
                            <p class="text-gray-600 mb-4">Your road assistance request is currently being reviewed by the Local Government Unit.</p>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="font-semibold text-gray-800">${data.event_name}</p>
                                <p class="text-sm text-gray-600">${data.event_location}</p>
                                <p class="text-sm text-gray-500 mt-2">Submitted: ${new Date(data.event_date).toLocaleDateString()}</p>
                            </div>
                        </div>
                    `,
                    confirmButtonColor: '#f97316'
                });
            } else if (data.status.toLowerCase() === 'rejected') {
                Swal.fire({
                    icon: 'error',
                    title: 'Request Rejected',
                    html: `
                        <div class="text-left">
                            <div class="bg-red-50 p-4 rounded-lg mb-4">
                                <p class="font-semibold text-red-800">Reason for Rejection:</p>
                                <p class="text-red-700">${data.feedback || 'No feedback provided'}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="font-semibold text-gray-800">${data.event_name}</p>
                                <p class="text-sm text-gray-600">${data.event_location}</p>
                            </div>
                        </div>
                    `,
                    confirmButtonColor: '#dc2626'
                });
            } else {
                // Approved - show all details
                let deploymentTime = '';
                if (data.deployment_start_time) {
                    deploymentTime = data.deployment_start_time;
                    if (data.deployment_end_time) {
                        deploymentTime += ' - ' + data.deployment_end_time;
                    }
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Request Approved',
                    html: `
                        <div class="text-left space-y-4">
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="font-semibold text-green-800">Your request has been approved!</p>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="font-semibold text-gray-800">${data.event_name}</p>
                                <p class="text-sm text-gray-600">${data.event_location}</p>
                            </div>

                            ${data.assigned_personnel ? `
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Assigned Personnel:</p>
                                <p class="text-gray-600">${data.assigned_personnel}</p>
                            </div>
                            ` : ''}

                            ${data.assigned_equipment ? `
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Assigned Equipment:</p>
                                <p class="text-gray-600">${data.assigned_equipment}</p>
                            </div>
                            ` : ''}

                            ${data.deployment_date ? `
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Deployment Schedule:</p>
                                <p class="text-gray-600">${new Date(data.deployment_date).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' })}${deploymentTime ? ' (' + deploymentTime + ')' : ''}</p>
                            </div>
                            ` : ''}

                            ${data.traffic_plan ? `
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Traffic Management Plan:</p>
                                <p class="text-gray-600">${data.traffic_plan}</p>
                            </div>
                            ` : ''}

                            ${data.admin_notes ? `
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Admin Notes:</p>
                                <p class="text-gray-600">${data.admin_notes}</p>
                            </div>
                            ` : ''}
                        </div>
                    `,
                    confirmButtonColor: '#16a34a',
                    width: '500px'
                });
            }
        }
    </script>
</body>
</html>
