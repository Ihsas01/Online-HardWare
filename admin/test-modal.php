<?php
require_once '../php/config.php';
$pageTitle = 'Test Modal';
$currentPage = 'test';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

include '../includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Test Modal Functionality</h1>
            <button class="btn btn-primary" onclick="testModal()">Test Modal</button>
        </div>
    </div>
</div>

<!-- Test Modal -->
<div class="modal fade" id="testModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>This is a test modal to verify Bootstrap modal functionality.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function testModal() {
    console.log('Testing modal...');
    const modal = new bootstrap.Modal(document.getElementById('testModal'));
    modal.show();
}

// Test if Bootstrap is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined') {
        console.log('Bootstrap is loaded');
    } else {
        console.error('Bootstrap is not loaded');
    }
});
</script>

<?php include '../includes/footer.php'; ?> 