<?php 
session_start(); 
include 'db.php'; // Logout logic 
if (isset($_GET['logout'])) { 
    session_destroy(); 
    header("Location: login.php"); 
    exit(); 
} 

// Check if the user is logged in, else redirect to login page 
if (!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit(); 
} 

$device_added = false; 

// Handle device addition 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_device'])) { 
    // Get form data 
    $device_name = $_POST['device_name']; 
    $ip_url = $_POST['ip_url']; 
    $device_username = $_POST['device_username']; 
    $device_password = $_POST['device_password']; 
    $status = $_POST['status']; 
    $mobile_number = $_POST['mobile_number']; 

    // Validate inputs 
    if (!empty($device_name) && !empty($ip_url) && !empty($device_username) && !empty($device_password)) { 
        // Insert into database 
        $sql = "INSERT INTO devices (device_name, ip_url, username, password, status, mobile_number) VALUES ('$device_name', '$ip_url', '$device_username', '$device_password', '$status', '$mobile_number')"; 
        if ($conn->query($sql) === TRUE) { 
            $device_added = true; 
        } else { 
            echo "Error: " . $sql . "<br>" . $conn->error; 
        } 
    } else { 
        echo "All fields are required!"; 
    } 
} 

// Fetch devices for display
$sql_devices = "SELECT * FROM devices"; 
$result = $conn->query($sql_devices); 
?> 

<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link href="https://www.takerhatwifi.com/assets/images/favicon.png" rel="icon"> 
    <title>Device Management</title> 
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"> 
    <style> 
        /* General Styling */ 
        body { background: #f5f5f5; font-family: 'Arial', sans-serif; } 
        /* Navbar Styling */
        .navbar { background: linear-gradient(45deg, #1c1c1c, #3e3e3e); padding: 15px 30px; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2); } 
        .navbar .navbar-brand { font-size: 1.6rem; font-weight: bold; color: white; } 
        /* Table Styling */ 
        .table { border-radius: 0.5rem; overflow: hidden; } 
        .table thead th { background-color: #343a40; color: white; font-weight: bold; } 
        .table th, .table td { vertical-align: middle; } 
        .table-striped tbody tr:nth-of-type(odd) { background-color: #f9f9f9; } 
        /* Status Badge Styling */ 
        .status-badge { padding: 5px 10px; color: white; border-radius: 12px; font-weight: bold; text-transform: capitalize; } 
        .bg-success { background-color: #28a745 !important; } 
        .bg-danger { background-color: #dc3545 !important; } 
    </style> 
</head> 
<body> 
    <!-- Navbar --> 
    <nav class="navbar navbar-expand-lg navbar-dark"> 
        <a class="navbar-brand" href="crud.php"> 
            <img src="https://takerhatwifi.com/assets/images/white-logo.png" alt="Logo" class="rounded-circle" style="width: 60px;"> TKH Management 
        </a> 
        <div class="ml-auto"> 
            <a href="crud.php?logout=true" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a> 
        </div> 
    </nav> 
    <!-- Main Container --> 
    <div class="container mt-4"> 
        <div class="card p-4 shadow-lg"> 
            <div class="header-section d-flex justify-content-between mb-4"> 
                <button class="btn btn-success" data-toggle="modal" data-target="#addDeviceModal"><i class="fas fa-plus-circle"></i> Add New Device</button> 
            </div> 
            <!-- Device Table --> 
            <div class="table-responsive"> 
                <table id="devicesTable" class="table table-striped table-bordered"> 
                    <thead> 
                        <tr> 
                            <th>Device Name</th> 
                            <th>IP / URL</th> 
                            <th>Username</th> 
                            <th>Password</th> 
                            <th>Mobile No.</th> 
                            <th>Status</th> 
                            <th>Actions</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        <?php while ($row = $result->fetch_assoc()): ?> 
                            <tr> 
                                <td><?php echo $row['device_name']; ?></td> 
                                <td><a href="http://<?php echo $row['ip_url']; ?>" target="_blank"><?php echo $row['ip_url']; ?></a></td> 
                                <td><?php echo $row['username']; ?></td> 
                                <td><?php echo $row['password']; ?></td> 
                                <td><?php if (!empty($row['mobile_number'])): ?> 
                                    <a href="tel:<?php echo $row['mobile_number']; ?>" class="btn btn-link"><?php echo $row['mobile_number']; ?></a> 
                                <?php else: ?> N/A <?php endif; ?> 
                                </td> 
                                <td><span class="status-badge <?php echo $row['status'] == 'Online' ? 'bg-success' : 'bg-danger'; ?>"><?php echo $row['status']; ?></span></td> 
                                <td> 
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editDeviceModal" 
                                        data-id="<?php echo $row['id']; ?>" 
                                        data-name="<?php echo $row['device_name']; ?>" 
                                        data-ip="<?php echo $row['ip_url']; ?>" 
                                        data-username="<?php echo $row['username']; ?>" 
                                        data-password="<?php echo $row['password']; ?>" 
                                        data-mobile="<?php echo $row['mobile_number']; ?>" 
                                        data-status="<?php echo $row['status']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button> 
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteDeviceModal" 
                                        data-id="<?php echo $row['id']; ?>" 
                                        data-name="<?php echo $row['device_name']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button> 
                                </td> 
                            </tr> 
                        <?php endwhile; ?> 
                    </tbody> 
                </table> 
            </div> 
        </div> 
    </div> 

    <!-- Modal for Adding Device --> 
    <div class="modal fade" id="addDeviceModal" tabindex="-1" role="dialog" aria-labelledby="addDeviceModalLabel" aria-hidden="true"> 
        <div class="modal-dialog" role="document"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h5 class="modal-title" id="addDeviceModalLabel">Add New Device</h5> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">&times;</span> 
                    </button> 
                </div> 
                <form action="crud.php" method="POST"> 
                    <div class="modal-body"> 
                        <div class="form-group"> 
                            <label for="device_name">Device Name</label> 
                            <input type="text" class="form-control" id="device_name" name="device_name" required> 
                        </div> 
                        <div class="form-group"> 
                            <label for="ip_url">IP / URL</label> 
                            <input type="text" class="form-control" id="ip_url" name="ip_url" required> 
                        </div> 
                        <div class="form-group"> 
                            <label for="device_username">Username</label> 
                            <input type="text" class="form-control" id="device_username" name="device_username" required> 
                        </div> 
                        <div class="form-group"> 
                            <label for="device_password">Password</label> 
                            <input type="text" class="form-control" id="device_password" name="device_password" required> 
                        </div> 
                        <div class="form-group"> 
                            <label for="mobile_number">Mobile Number</label> 
                            <input type="tel" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter Mobile Number"> 
                        </div> 
                        <div class="form-group"> 
                            <label for="status">Status</label> 
                            <select class="form-control" id="status" name="status"> 
                                <option value="Online">Online</option> 
                                <option value="Offline">Offline</option> 
                            </select> 
                        </div> 
                    </div> 
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                        <button type="submit" name="add_device" class="btn btn-success">Save Device</button> 
                    </div> 
                </form> 
            </div> 
        </div> 
    </div> 

    <!-- Modal for Editing Device --> 
    <div class="modal fade" id="editDeviceModal" tabindex="-1" role="dialog" aria-labelledby="editDeviceModalLabel" aria-hidden="true"> 
        <div class="modal-dialog" role="document"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h5 class="modal-title" id="editDeviceModalLabel">Edit Device</h5> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">&times;</span> 
                    </button> 
                </div> 
                <form action="edit_device.php" method="POST"> 
                    <div class="modal-body"> 
                        <input type="hidden" id="edit_device_id" name="id"> 
                        <div class="form-group"> 
                            <label for="edit_device_name">Device Name</label> 
                            <input type="text" class="form-control" id="edit_device_name" name="device_name" required> 
                        </div> 
                        <div class="form-group"> 
                            <label for="edit_ip_url">IP / URL</label> 
                            <input type="text" class="form-control" id="edit_ip_url" name="ip_url" required> 
                        </div> 
                        <div class="form-group"> 
                            <label for="edit_device_username">Username</label> 
                            <input type="text" class="form-control" id="edit_device_username" name="device_username" required> 
                        </div> 
                        <div class="form-group"> 
                            <label for="edit_device_password">Password</label> 
                            <input type="text" class="form-control" id="edit_device_password" name="device_password" required> 
                        </div> 
                        <div class="form-group"> 
                            <label for="edit_mobile_number">Mobile Number</label> 
                            <input type="tel" class="form-control" id="edit_mobile_number" name="mobile_number" placeholder="Enter Mobile Number"> 
                        </div> 
                        <div class="form-group"> 
                            <label for="edit_status">Status</label> 
                            <select class="form-control" id="edit_status" name="status"> 
                                <option value="Online">Online</option> 
                                <option value="Offline">Offline</option> 
                            </select> 
                        </div> 
                    </div> 
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
                        <button type="submit" name="edit_device" class="btn btn-primary">Save Changes</button> 
                    </div> 
                </form> 
            </div> 
        </div> 
    </div> 

    <!-- Modal for Delete Confirmation --> 
    <div class="modal fade" id="deleteDeviceModal" tabindex="-1" role="dialog" aria-labelledby="deleteDeviceModalLabel" aria-hidden="true"> 
        <div class="modal-dialog" role="document"> 
            <div class="modal-content"> 
                <div class="modal-header"> 
                    <h5 class="modal-title" id="deleteDeviceModalLabel">Delete Device</h5> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
                        <span aria-hidden="true">&times;</span> 
                    </button> 
                </div> 
                <div class="modal-body"> Are you sure you want to delete the device "<span id="device_name_to_delete"></span>"? </div> 
                <div class="modal-footer"> 
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
                    <a href="#" id="confirm_delete" class="btn btn-danger">Delete</a> 
                </div> 
            </div> 
        </div> 
    </div> 

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script> 
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> 
    <script> 
        $(document).ready(function() {
            $('#devicesTable').DataTable({
                "searching": true,
                "ordering": true,
                "paging": true,
                "pageLength": 100, // Set default showing entries to 100
            });
        });

        $('#editDeviceModal').on('show.bs.modal', function (event) { 
            var button = $(event.relatedTarget); // Button that triggered the modal 
            var deviceId = button.data('id'); 
            var deviceName = button.data('name'); 
            var ipUrl = button.data('ip'); 
            var username = button.data('username'); 
            var password = button.data('password'); 
            var mobile = button.data('mobile'); 
            var status = button.data('status'); 
            var modal = $(this); 
            modal.find('#edit_device_id').val(deviceId); 
            modal.find('#edit_device_name').val(deviceName); 
            modal.find('#edit_ip_url').val(ipUrl); 
            modal.find('#edit_device_username').val(username); 
            modal.find('#edit_device_password').val(password); 
            modal.find('#edit_mobile_number').val(mobile); // Populate the mobile number
            modal.find('#edit_status').val(status); 
        }); 
        
        $('#deleteDeviceModal').on('show.bs.modal', function (event) { 
            var button = $(event.relatedTarget); // Button that triggered the modal 
            var deviceId = button.data('id'); 
            var deviceName = button.data('name'); 
            var modal = $(this); 
            modal.find('#device_name_to_delete').text(deviceName); 
            modal.find('#confirm_delete').attr('href', 'delete_device.php?id=' + deviceId); 
        }); 
    </script> 
</body> 
</html>
