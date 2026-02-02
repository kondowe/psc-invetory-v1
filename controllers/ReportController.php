<?php
/**
 * ReportController
 */
class ReportController
{
    public function index()
    {
        Auth::requireAuth();
        
        Response::view('reports/index');
    }

    public function inventory()
    {
        Auth::requireAuth();
        RBAC::require('inventory.view');
        Response::view('reports/inventory');
    }
}
