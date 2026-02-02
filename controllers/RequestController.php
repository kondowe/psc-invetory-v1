<?php
/**
 * RequestController
 */
require_once __DIR__ . '/../models/Request.php';
require_once __DIR__ . '/../models/RequestItem.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/ItemCategory.php';
require_once __DIR__ . '/../models/SystemConfig.php';
require_once __DIR__ . '/../services/WorkflowService.php';

class RequestController
{
    public function index()
    {
        Auth::requireAuth();
        
        // Use permissions instead of hardcoded role checks
        if (!RBAC::canAny(['request.view_all', 'request.view_department'])) {
            Response::redirect(Security::url('/requests/own'));
        }
        
        $userId = Auth::id();
        $deptId = Auth::departmentId();
        $roleId = Auth::roleId();

        if (RBAC::can('request.view_all')) {
            // System-wide view
            $sql = "SELECT r.*, u.full_name as requester_name 
                    FROM requests r 
                    JOIN users u ON r.requester_user_id = u.user_id 
                    WHERE r.deleted_at IS NULL 
                    ORDER BY r.created_at DESC";
            $requests = Database::fetchAll($sql);
            
            Response::view('requests/index', [
                'requests' => $requests,
                'isOwnOnly' => false,
                'isTabbed' => false
            ]);
        } else {
            // Department-wide tabbed view (Assumes request.view_department)
            $sql = "SELECT r.*, u.full_name as requester_name, ws.step_name
                    FROM requests r 
                    JOIN users u ON r.requester_user_id = u.user_id
                    JOIN workflow_instances wi ON r.workflow_instance_id = wi.workflow_instance_id
                    JOIN workflow_step_instances wsi ON wi.workflow_instance_id = wsi.workflow_instance_id
                    JOIN workflow_steps ws ON wsi.workflow_step_id = ws.workflow_step_id
                    WHERE r.department_id = ? 
                    AND wsi.status = 'pending'
                    AND wsi.assigned_role_id = ?
                    AND wsi.step_order = wi.current_step_order
                    AND r.deleted_at IS NULL
                    ORDER BY r.created_at DESC";
            $needsApproval = Database::fetchAll($sql, [$deptId, $roleId]);

            // 2. Pending (In progress but not at current user's step)
            $sql = "SELECT r.*, u.full_name as requester_name
                    FROM requests r 
                    JOIN users u ON r.requester_user_id = u.user_id
                    WHERE r.department_id = ? 
                    AND r.status = 'pending'
                    AND r.request_id NOT IN (
                        SELECT req.request_id FROM requests req
                        JOIN workflow_instances wi ON req.workflow_instance_id = wi.workflow_instance_id
                        JOIN workflow_step_instances wsi ON wi.workflow_instance_id = wsi.workflow_instance_id
                        WHERE wsi.assigned_role_id = ? AND wsi.step_order = wi.current_step_order AND wsi.status = 'pending'
                    )
                    AND r.deleted_at IS NULL
                    ORDER BY r.created_at DESC";
            $pendingOther = Database::fetchAll($sql, [$deptId, $roleId]);

            // 3. Approved/Completed
            $sql = "SELECT r.*, u.full_name as requester_name 
                    FROM requests r 
                    JOIN users u ON r.requester_user_id = u.user_id 
                    WHERE r.department_id = ? 
                    AND r.status IN ('approved', 'partially_issued', 'issued', 'closed')
                    AND r.deleted_at IS NULL 
                    ORDER BY r.created_at DESC";
            $approved = Database::fetchAll($sql, [$deptId]);

            Response::view('requests/index', [
                'needsApproval' => $needsApproval,
                'pendingOther' => $pendingOther,
                'approved' => $approved,
                'isOwnOnly' => false,
                'isTabbed' => true
            ]);
        }
    }

    /**
     * View only own requests
     */
    public function own()
    {
        Auth::requireAuth();
        
        $userId = Auth::id();
        $sql = "SELECT r.*, u.full_name as requester_name 
                FROM requests r 
                JOIN users u ON r.requester_user_id = u.user_id 
                WHERE r.requester_user_id = ? AND r.deleted_at IS NULL 
                ORDER BY r.created_at DESC";
        $requests = Database::fetchAll($sql, [$userId]);

        Response::view('requests/index', [
            'requests' => $requests,
            'isOwnOnly' => true,
            'isTabbed' => false
        ]);
    }

    public function create()
    {
        Auth::requireAuth();
        RBAC::require('request.create');

        $categories = ItemCategory::getAllForDropdown();
        $items = Item::getActiveItems();
        $vehicles = Database::fetchAll("SELECT * FROM vehicles WHERE status = 'active' AND deleted_at IS NULL");
        $fuelTypes = Database::fetchAll("SELECT * FROM fuel_types");

        Response::view('requests/create', [
            'categories' => $categories,
            'items' => $items,
            'vehicles' => $vehicles,
            'fuelTypes' => $fuelTypes
        ]);
    }

    public function store()
    {
        Auth::requireAuth();
        RBAC::require('request.create');
        Security::checkCsrfToken();

        $requestType = $_POST['request_type'] ?? 'item';
        Logger::info("Processing request store. Type: $requestType. POST data: " . json_encode($_POST));
        
        $fuelItems = []; 

        $data = [
            'request_number' => Request::generateNumber(),
            'request_type' => $requestType,
            'requester_user_id' => Auth::id(),
            'department_id' => Auth::departmentId(),
            'purpose' => Security::sanitize($_POST['purpose'] ?? '', 'string'),
            'priority' => $_POST['priority'] ?? 'medium',
            'status' => 'draft',
            'date_required' => $_POST['date_required'] ?? date('Y-m-d')
        ];

        if ($requestType === 'fuel') {
            $data['departure_point'] = Security::sanitize($_POST['departure_point'] ?? '', 'string');
            $data['destination_point'] = Security::sanitize($_POST['destination_point'] ?? '', 'string');
            $data['departure_date'] = $_POST['departure_date'] ?? null;
            $data['is_round_trip'] = isset($_POST['is_round_trip']) ? 1 : 0;
            $data['request_company_vehicle'] = isset($_POST['request_company_vehicle']) ? 1 : 0;
            $data['vehicle_id'] = !empty($_POST['vehicle_id']) ? (int)$_POST['vehicle_id'] : null;
            $data['fuel_type_id'] = !empty($_POST['fuel_type_id']) ? (int)$_POST['fuel_type_id'] : null;

            $fuelQty = (float)($_POST['fuel_quantity'] ?? 0);
            Logger::info("Fuel data sanitized: Qty $fuelQty, Type ID {$data['fuel_type_id']}");

            if (empty($data['departure_point']) || empty($data['destination_point']) || empty($data['fuel_type_id']) || $fuelQty <= 0) {
                Logger::warning("Fuel validation failed. Missing required fuel fields.");
                Session::flash('error', 'Departure, Destination, Fuel Type, and a valid Quantity are required for fuel requests.');
                Response::redirect(Security::url('/requests/create'));
            }

            $fuelType = Database::fetchOne("SELECT fuel_code FROM fuel_types WHERE fuel_type_id = ?", [$data['fuel_type_id']]);
            $sku = 'FUEL-' . ($fuelType['fuel_code'] ?? 'PETROL');
            $item = Item::findBySku($sku);
            
            if (!$item) {
                Logger::error("Generic fuel item for SKU '$sku' not found in database.");
                throw new Exception("Generic fuel item for SKU '$sku' not found. Please contact administrator.");
            }

            $fuelItems[] = [
                'item_id' => $item['item_id'],
                'quantity' => $fuelQty,
                'justification' => 'Requested total liters'
            ];
        }

        $items = ($requestType === 'fuel') ? $fuelItems : ($_POST['items'] ?? []);
        if (empty($items)) {
            Logger::warning("Final items list is empty.");
            Session::flash('error', 'At least one item/quantity is required');
            Response::redirect(Security::url('/requests/create'));
        }

        // --- ENFORCE STOCK RULES ---
        $enforce = SystemConfig::get('enforce_stock_restrictions', true);
        if ($enforce && $requestType === 'item') {
            $userRole = Auth::roleKey();
            $allowedBelowReorder = SystemConfig::get('roles_can_request_below_reorder', []);
            $allowedBelowMin = SystemConfig::get('roles_can_request_below_min', []);

            foreach ($items as $item) {
                if (isset($item['is_custom']) && $item['is_custom'] == 1) continue;
                
                $itemData = Item::find($item['item_id']);
                if (!$itemData) continue;

                // Get total available stock across all stores
                $stockSql = "SELECT SUM(quantity_available) as total_avail FROM stock_levels WHERE item_id = ?";
                $stockResult = Database::fetchOne($stockSql, [$item['item_id']]);
                $totalAvail = (float)($stockResult['total_avail'] ?? 0);

                // Check Minimum Level first (stricter)
                if ($totalAvail <= $itemData['minimum_stock_level']) {
                    if (!in_array($userRole, $allowedBelowMin)) {
                        Session::flash('error', "Blocked: '{$itemData['item_name']}' is at critical minimum stock level. Only authorized managers can request this item currently.");
                        Response::redirect(Security::url('/requests/create'));
                    }
                } 
                // Check Reorder Level
                elseif ($totalAvail <= $itemData['reorder_level']) {
                    if (!in_array($userRole, $allowedBelowReorder)) {
                        Session::flash('error', "Blocked: '{$itemData['item_name']}' has reached its reorder level. Your role is not authorized to request items in low stock.");
                        Response::redirect(Security::url('/requests/create'));
                    }
                }
            }
        }
        // --- END STOCK RULES ---

        Database::beginTransaction();
        try {
            $requestId = Request::create($data);
            Logger::info("Created request header ID: $requestId");

            foreach ($items as $item) {
                $isCustom = isset($item['is_custom']) && $item['is_custom'] == 1;
                
                if (!$isCustom && empty($item['item_id'])) continue;
                if ($isCustom && empty($item['custom_item_name'])) continue;
                if (empty($item['quantity'])) continue;

                RequestItem::create([
                    'request_id' => $requestId,
                    'item_id' => !$isCustom ? (int)$item['item_id'] : null,
                    'is_custom' => $isCustom ? 1 : 0,
                    'custom_item_name' => $isCustom ? Security::sanitize($item['custom_item_name'], 'string') : null,
                    'quantity_requested' => (float)$item['quantity'],
                    'justification' => Security::sanitize($item['justification'] ?? '', 'string'),
                    'status' => 'pending'
                ]);
            }

            Database::commit();
            Logger::info("Committed transaction for request ID: $requestId");

            if (isset($_POST['action_submit'])) {
                Logger::info("Submitting for approval...");
                $this->submit($requestId);
            }

            Session::flash('success', 'Request created successfully');
            Response::redirect(Security::url('/requests/view/' . $requestId));

        } catch (Exception $e) {
            Database::rollBack();
            Logger::error('Request creation failed inside transaction: ' . $e->getMessage());
            Session::flash('error', 'Failed to create request: ' . $e->getMessage());
            Response::redirect(Security::url('/requests/create'));
        }
    }

    public function view($requestId)
    {
        Auth::requireAuth();
        $request = Request::getWithDetails($requestId);
        if (!$request) Response::notFound();

        $items = RequestItem::getByRequest($requestId);
        
        // Get workflow history
        $workflowSteps = [];
        $currentPendingStep = null;

        if ($request['workflow_instance_id']) {
            $sql = "SELECT wsi.*, r.role_name, u.full_name as action_by_name, ws.step_name
                    FROM workflow_step_instances wsi
                    JOIN roles r ON wsi.assigned_role_id = r.role_id
                    LEFT JOIN users u ON wsi.action_taken_by_user_id = u.user_id
                    JOIN workflow_steps ws ON wsi.workflow_step_id = ws.workflow_step_id
                    WHERE wsi.workflow_instance_id = ?
                    ORDER BY wsi.step_order ASC";
            $workflowSteps = Database::fetchAll($sql, [$request['workflow_instance_id']]);

            // Check if there's a step pending for the current user
            $sql = "SELECT wsi.* FROM workflow_step_instances wsi
                    JOIN workflow_instances wi ON wsi.workflow_instance_id = wi.workflow_instance_id
                    WHERE wi.request_id = ? 
                    AND wsi.status = 'pending'
                    AND (wsi.assigned_user_id = ? OR (wsi.assigned_user_id IS NULL AND wsi.assigned_role_id = ?))
                    AND wsi.step_order = wi.current_step_order
                    LIMIT 1";
            $currentPendingStep = Database::fetchOne($sql, [$requestId, Auth::id(), Auth::roleId()]);
        }

        Response::view('requests/view', [
            'request' => $request,
            'items' => $items,
            'workflowSteps' => $workflowSteps,
            'currentPendingStep' => $currentPendingStep
        ]);
    }

    public function submit($requestId)
    {
        Auth::requireAuth();
        try {
            WorkflowService::initialize($requestId);
            Session::flash('success', 'Request submitted for approval');
        } catch (Exception $e) {
            Logger::error('Workflow init failed: ' . $e->getMessage());
            Session::flash('error', 'Failed to submit request: ' . $e->getMessage());
        }
        Response::redirect(Security::url('/requests/view/' . $requestId));
    }

    /**
     * Pending approvals list for supervisors/managers
     */
    public function pending()
    {
        Auth::requireAuth();
        
        $pendingActions = WorkflowStepInstance::getPendingForUser(Auth::id(), Auth::roleId());
        
        Response::view('requests/pending', [
            'pendingActions' => $pendingActions
        ]);
    }

    public function approve($stepInstanceId)
    {
        Auth::requireAuth();
        Security::checkCsrfToken();
        
        try {
            WorkflowService::approve($stepInstanceId, Auth::id(), $_POST['comments'] ?? '');
            Session::flash('success', 'Request approved');
        } catch (Exception $e) {
            Session::flash('error', 'Approval failed: ' . $e->getMessage());
        }
        Response::back();
    }

    public function reject($stepInstanceId)
    {
        Auth::requireAuth();
        Security::checkCsrfToken();
        
        try {
            WorkflowService::reject($stepInstanceId, Auth::id(), $_POST['comments'] ?? '');
            Session::flash('success', 'Request rejected');
        } catch (Exception $e) {
            Session::flash('error', 'Rejection failed: ' . $e->getMessage());
        }
        Response::back();
    }

    /**
     * Force approve a request (General Admin Override)
     */
    public function override($requestId)
    {
        Auth::requireAuth();
        if (!Auth::isGeneralAdminManager()) {
            Response::error('Unauthorized. Only General Administration Manager can override.');
        }

        Security::checkCsrfToken();

        try {
            Database::beginTransaction();
            
            $request = Request::find($requestId);
            if (!$request) throw new Exception("Request not found");

            // 1. Mark current workflow steps as skipped/override
            if ($request['workflow_instance_id']) {
                Database::query(
                    "UPDATE workflow_step_instances SET status = 'skipped', comments = 'System Override' 
                     WHERE workflow_instance_id = ? AND status = 'pending'",
                    [$request['workflow_instance_id']]
                );
                
                Database::query(
                    "UPDATE workflow_instances SET status = 'completed', completed_at = NOW() 
                     WHERE workflow_instance_id = ?",
                    [$request['workflow_instance_id']]
                );
            }

            // 2. Approve Request
            Request::update($requestId, ['status' => 'approved']);

            // 3. Notify
            NotificationService::send($request['requester_user_id'], 'success', 'Request Overridden', "Your request #{$request['request_number']} was manually approved by the General Manager.", 'request', $requestId);

            Database::commit();
            Session::flash('success', 'Request has been manually approved via Override.');
            Response::redirect(Security::url('/requests/view/' . $requestId));

        } catch (Exception $e) {
            Database::rollBack();
            Logger::error("Override failed: " . $e->getMessage());
            Session::flash('error', 'Override failed: ' . $e->getMessage());
            Response::back();
        }
    }

    /**
     * Update a specific item in a request (for managers)
     */
    public function updateItem($reqItemId)
    {
        Auth::requireAuth();
        // Only Admin Manager or General Admin can edit items
        if (!Auth::isAdminManager() && !Auth::isGeneralAdminManager()) {
            Response::error('Unauthorized');
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $quantity = (float)($data['quantity'] ?? 0);

        if ($quantity <= 0) {
            Response::error('Quantity must be greater than zero');
        }

        try {
            RequestItem::update($reqItemId, ['quantity_requested' => $quantity]);
            Logger::logActivity(Auth::id(), 'request_item_update', "Updated request item $reqItemId to quantity $quantity");
            Response::success(['message' => 'Quantity updated']);
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    /**
     * Remove a specific item from a request
     */
    public function removeItem($reqItemId)
    {
        Auth::requireAuth();
        if (!Auth::isAdminManager() && !Auth::isGeneralAdminManager()) {
            Response::error('Unauthorized');
        }

        try {
            RequestItem::delete($reqItemId);
            Logger::logActivity(Auth::id(), 'request_item_delete', "Removed request item $reqItemId");
            Response::success(['message' => 'Item removed']);
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }
}
